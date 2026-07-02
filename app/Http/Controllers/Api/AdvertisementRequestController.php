<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AdvertisementRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdvertisementRequestController extends Controller
{
    public function index(Request $request)
    {
        $requests = AdvertisementRequest::query()
            ->with('payment')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate((int) $request->input('per_page', 15));

        return response()->json($requests);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'target_url' => 'nullable|url|max:255',
            'customer_name' => 'nullable|string|max:255',
            'customer_email' => 'nullable|email|max:255',
            'customer_phone' => 'nullable|string|max:50',
            'customer_address' => 'nullable|string|max:255',
            'media_urls' => 'nullable',
            'media_files' => 'nullable|array|max:10',
            'media_files.*' => 'file|mimes:jpg,jpeg,png,gif,webp|max:51200',
        ]);
        $data['media_urls'] = $this->storeMediaFiles($request) ?: $this->parseMediaUrls($data['media_urls'] ?? []);
        unset($data['media_files']);

        $adRequest = DB::transaction(function () use ($data, $request) {
            return AdvertisementRequest::create(array_merge($data, [
                'user_id' => $request->user()->id,
                'status' => AdvertisementRequest::STATUS_PENDING_REVIEW,
            ]));
        });

        return response()->json([
            'message' => 'Advertisement request created.',
            'data' => $adRequest->load('payment'),
        ], 201);
    }

    public function show(Request $request, AdvertisementRequest $advertisementRequest)
    {
        if ((int) $advertisementRequest->user_id !== (int) $request->user()->id) {
            abort(403);
        }

        return response()->json([
            'data' => $advertisementRequest->load('payment'),
        ]);
    }

    protected function parseMediaUrls($value)
    {
        if (is_array($value)) {
            return array_values(array_filter(array_map('trim', $value)));
        }

        if (empty($value)) {
            return [];
        }

        return array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n|,/', (string) $value))));
    }

    protected function storeMediaFiles(Request $request)
    {
        if (!$request->hasFile('media_files')) {
            return [];
        }

        $urls = [];

        foreach ($request->file('media_files', []) as $file) {
            if (!$file || !$file->isValid()) {
                continue;
            }

            $folder = 'advertisements/' . date('Y/m');
            $filename = Str::uuid() . '.' . strtolower($file->getClientOriginalExtension());
            $path = $file->storeAs($folder, $filename, 'uploads');
            $urls[] = 'uploads/' . ltrim($path, '/');
        }

        return $urls;
    }
}
