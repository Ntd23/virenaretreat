@php
    $title = $title ?? '';
    $subTitle = $sub_title ?? '';
    $imageUrl = $banner_image_url ?? '';
    $linkUrl = $banner_link_url ?: '#';
    $targetBlank = !empty($advertisement) && $linkUrl !== '#';
@endphp

<div class="bravo-advertisement-banner">
    <div class="container">
        <div class="advertisement-banner-heading">
            <div>
                @if($title)
                    <h2>{{$title}}</h2>
                @endif
                @if($subTitle)
                    <p>{{$subTitle}}</p>
                @endif
            </div>
            <a href="{{url('/hotel')}}" class="advertisement-banner-more">{{__("Xem tất cả")}} <i class="fa fa-angle-right"></i></a>
        </div>

        @if($imageUrl)
            <a href="{{$linkUrl}}" class="advertisement-banner-media" style="height: {{$height}}px;" @if($targetBlank) target="_blank" rel="noopener" @endif>
                <img src="{{$imageUrl}}" alt="{{$title ?: __('Banner quảng cáo')}}">
            </a>
        @endif
    </div>
</div>

@push('css')
    <style>
        .bravo-advertisement-banner {
            margin: 34px 0 28px;
        }

        .bravo-advertisement-banner .advertisement-banner-heading {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 18px;
            margin-bottom: 16px;
            padding: 0 2px;
        }

        .bravo-advertisement-banner .advertisement-banner-heading h2 {
            margin: 0 0 6px;
            color: #1a2b48;
            font-size: 28px;
            font-weight: 500;
            line-height: 1.2;
        }

        .bravo-advertisement-banner .advertisement-banner-heading p {
            margin: 0;
            color: #6b7280;
            font-size: 15px;
            line-height: 1.5;
        }

        .bravo-advertisement-banner .advertisement-banner-more {
            flex: 0 0 auto;
            color: #1a2b48;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            white-space: nowrap;
        }

        .bravo-advertisement-banner .advertisement-banner-media {
            display: block;
            overflow: hidden;
            width: 100%;
            border-radius: 4px;
            background: #f3f6fb;
        }

        .bravo-advertisement-banner .advertisement-banner-media img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .25s ease;
        }

        .bravo-advertisement-banner .advertisement-banner-media:hover img {
            transform: scale(1.02);
        }

        @media (max-width: 767px) {
            .bravo-advertisement-banner {
                margin: 24px 0 22px;
            }

            .bravo-advertisement-banner .advertisement-banner-heading {
                align-items: flex-start;
                flex-direction: column;
                gap: 8px;
            }

            .bravo-advertisement-banner .advertisement-banner-heading h2 {
                font-size: 22px;
            }

            .bravo-advertisement-banner .advertisement-banner-media {
                height: 110px !important;
            }
        }
    </style>
@endpush
