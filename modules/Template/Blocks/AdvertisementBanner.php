<?php

namespace Modules\Template\Blocks;

use App\Models\AdvertisementRequest;
use Modules\Media\Helpers\FileHelper;

class AdvertisementBanner extends BaseBlock
{
    public function getName()
    {
        return __('Banner quảng cáo');
    }

    public function getOptions()
    {
        return [
            'settings' => [
                [
                    'id' => 'title',
                    'type' => 'input',
                    'inputType' => 'text',
                    'label' => __('Tiêu đề'),
                    'std' => __('Danh Sách Phổ Biến Nhất'),
                ],
                [
                    'id' => 'sub_title',
                    'type' => 'input',
                    'inputType' => 'text',
                    'label' => __('Mô tả ngắn'),
                    'std' => __('Khách sạn được đánh giá cao với thiết kế tinh tế'),
                ],
                [
                    'id' => 'placement',
                    'type' => 'select',
                    'label' => __('Vị trí quảng cáo'),
                    'values' => [
                        [
                            'value' => 'advertisement_banner',
                            'name' => __('Banner quảng cáo'),
                        ],
                        [
                            'value' => 'large_banner',
                            'name' => __('Banner lớn'),
                        ],
                        [
                            'value' => 'left_sidebar',
                            'name' => __('Sidebar trái'),
                        ],
                        [
                            'value' => 'right_sidebar',
                            'name' => __('Sidebar phải'),
                        ],
                    ],
                    'std' => 'advertisement_banner',
                ],
                [
                    'id' => 'fallback_image',
                    'type' => 'uploader',
                    'label' => __('Ảnh dự phòng'),
                ],
                [
                    'id' => 'fallback_link',
                    'type' => 'input',
                    'inputType' => 'text',
                    'label' => __('Link dự phòng'),
                ],
                [
                    'id' => 'height',
                    'type' => 'input',
                    'inputType' => 'number',
                    'label' => __('Chiều cao banner'),
                    'std' => 150,
                ],
            ],
            'category' => __('Other Block'),
        ];
    }

    public function content($model = [])
    {
        $placement = $model['placement'] ?? 'advertisement_banner';
        $advertisement = AdvertisementRequest::runningAds($placement, 1)->first();

        $model['advertisement'] = $advertisement;
        $model['banner_image_url'] = $advertisement
            ? $advertisement->firstMediaUrl()
            : (FileHelper::url($model['fallback_image'] ?? '', 'full') ?? '');
        $model['banner_link_url'] = $advertisement
            ? ($advertisement->link_url ?: $advertisement->target_url ?: '#')
            : ($model['fallback_link'] ?? '#');
        $model['height'] = max(90, (int) ($model['height'] ?? 150));

        return $this->view('Template::frontend.blocks.advertisement-banner.index', $model);
    }
}
