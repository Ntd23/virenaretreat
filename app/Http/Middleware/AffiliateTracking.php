<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;

class AffiliateTracking
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $ref = $request->query('ref');
        
        if ($ref && is_numeric($ref)) {
            // Kiểm tra xem User giới thiệu có tồn tại trong hệ thống hay không
            $referrerExists = DB::table('users')->where('id', $ref)->exists();
            
            if ($referrerExists) {
                // Xác định đối tượng đang xem (Tour/Hotel...) để ghi nhận click
                $route = $request->route();
                if ($route) {
                    $actionName = $route->getActionName();
                    
                    $modelMap = [
                        'Modules\Tour\Controllers\TourController' => [
                            'model' => 'tour',
                            'class' => \Modules\Tour\Models\Tour::class
                        ],
                        'Modules\Hotel\Controllers\HotelController' => [
                            'model' => 'hotel',
                            'class' => \Modules\Hotel\Models\Hotel::class
                        ],
                        'Modules\Space\Controllers\SpaceController' => [
                            'model' => 'space',
                            'class' => \Modules\Space\Models\Space::class
                        ],
                        'Modules\Car\Controllers\CarController' => [
                            'model' => 'car',
                            'class' => \Modules\Car\Models\Car::class
                        ],
                        'Modules\Boat\Controllers\BoatController' => [
                            'model' => 'boat',
                            'class' => \Modules\Boat\Models\Boat::class
                        ],
                    ];

                    $controller = ltrim(explode('@', $actionName)[0] ?? '', '\\');
                    if (isset($modelMap[$controller])) {
                        $slug = $route->parameter('slug');
                        if ($slug) {
                            $class = $modelMap[$controller]['class'];
                            if (class_exists($class)) {
                                $item = $class::where('slug', $slug)->first();
                                
                                if ($item && $item->is_affiliate) {
                                    $objectId = $item->id;
                                    $objectModel = $modelMap[$controller]['model'];
                                    $ip = $request->ip();
                                    $ua = $request->userAgent();

                                    // Kiểm tra xem IP này đã click vào sản phẩm này qua ref này chưa trong vòng 1 giờ để tránh spam click
                                    $lastClick = DB::table('affiliate_clicks')
                                        ->where('referrer_id', $ref)
                                        ->where('object_id', $objectId)
                                        ->where('object_model', $objectModel)
                                        ->where('ip_address', $ip)
                                        ->where('created_at', '>=', now()->subHour())
                                        ->exists();

                                    if (!$lastClick) {
                                        DB::table('affiliate_clicks')->insert([
                                            'referrer_id' => $ref,
                                            'object_id' => $objectId,
                                            'object_model' => $objectModel,
                                            'ip_address' => $ip,
                                            'user_agent' => $ua,
                                            'created_at' => now(),
                                        ]);
                                    }
                                    
                                    // Thiết lập Cookie lưu ID người giới thiệu (thời hạn 30 ngày = 43200 phút)
                                    Cookie::queue('affiliate_referrer_id', $ref, 43200);
                                }
                            }
                        }
                    }
                }
            }
        }

        return $next($request);
    }
}
