@php
    $banner_left_url = !empty($banner_left_img) ? get_file_url($banner_left_img, 'full') : '';
    $banner_right_url = !empty($banner_right_img) ? get_file_url($banner_right_img, 'full') : '';
@endphp
<div class="container-fluid px-0">
    <div class="row align-items-stretch list-locations-banner-wrapper no-gutters">
        <!-- Left Banner -->
        <div class="col-xl-2 col-lg-3 d-none d-lg-block js-placeholder-left">
            <a href="{{ $banner_left_link ?: '#' }}" class="location-side-banner location-side-banner-left d-block">
                <div class="banner-bg"
                    style="background-image: url('{{ $banner_left_url ?: 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=600&q=80' }}');">
                </div>
                <div class="banner-overlay"></div>
            </a>
        </div>

        <!-- Central Grid -->
        <div class="col-xl-8 col-lg-6 col-md-12 px-4 px-md-5">
            <div class="bravo-list-locations @if (!empty($layout)) {{ $layout }} @endif">
                <div class="title">
                    {{ $title }}
                </div>
                @if (!empty($desc))
                    <div class="sub-title">
                        {{ $desc }}
                    </div>
                @endif
                @if (!empty($rows))
                    <div class="list-item">
                        <div class="row">
                            @foreach ($rows as $key => $row)
                                <?php
                                $size_col = 4;
                                if (!empty($layout) and ($layout == 'style_2' or $layout == 'style_3' or $layout == 'style_4')) {
                                    $size_col = 4;
                                } else {
                                    if ($key == 0) {
                                        $size_col = 8;
                                    }
                                }
                                ?>
                                <div class="col-lg-{{ $size_col }} col-md-6">
                                    @include('Location::frontend.blocks.list-locations.loop')
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Right Banner -->
        <div class="col-xl-2 col-lg-3 d-none d-lg-block js-placeholder-right">
            <a href="{{ $banner_right_link ?: '#' }}" class="location-side-banner location-side-banner-right d-block">
                <div class="banner-bg"
                    style="background-image: url('{{ $banner_right_url ?: 'https://images.unsplash.com/photo-1476514525535-07fb3b4ae5f1?auto=format&fit=crop&w=600&q=80' }}');">
                </div>
                <div class="banner-overlay"></div>
            </a>
        </div>
    </div>
</div>

@push('css')
    <style>
        .list-locations-banner-wrapper {
            margin-left: 0 !important;
            margin-right: 0 !important;
            width: 100% !important;
        }

        .list-locations-banner-wrapper>.col-xl-2,
        .list-locations-banner-wrapper>.col-lg-3 {
            padding-left: 10px !important;
            padding-right: 10px !important;
        }

        .location-side-banner {
            position: relative;
            overflow: hidden;
            height: 100%;
            min-height: 450px;
            display: flex;
            align-items: flex-end;
            padding: 30px 24px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
            transition: transform 0.4s cubic-bezier(0.25, 0.8, 0.25, 1), box-shadow 0.4s ease, opacity 0.4s ease;
            text-decoration: none !important;
            border-radius: 6px;
        }

        /* Khi banner chuyển sang trạng thái Fixed bám màn hình */
        .location-side-banner.is-fixed {
            position: fixed !important;
            top: 130px !important; /* Tăng khoảng cách từ menu ra */
            height: calc(100vh - 180px) !important; /* Giảm chiều cao tương ứng để tránh bị tràn màn hình */
            z-index: 9999 !important;
        }

        .location-side-banner.is-hidden {
            opacity: 0 !important;
            pointer-events: none !important;
        }

        .location-side-banner .banner-bg {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-size: cover;
            background-position: center;
            transition: transform 0.6s cubic-bezier(0.25, 0.8, 0.25, 1);
            z-index: 0;
        }

        .location-side-banner .banner-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            transition: background 0.4s ease;
            z-index: 1;
        }

        .location-side-banner:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .location-side-banner:hover .banner-bg {
            transform: scale(1.1);
        }

        .location-side-banner .banner-content {
            position: relative;
            z-index: 2;
            color: #fff;
            width: 100%;
        }

        .location-side-banner .banner-badge {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            color: #fff;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: inline-block;
            margin-bottom: 12px;
        }

        .location-side-banner .banner-title {
            font-size: 24px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 20px;
            line-height: 1.3;
        }

        .location-side-banner .banner-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: #fff;
            color: #1A2B48;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .location-side-banner:hover .banner-btn {
            background: #5191FA;
            color: #fff;
            transform: translateX(4px);
        }

        .list-locations-banner-wrapper .bravo-list-locations {
            margin: 0 !important;
        }
    </style>
@endpush

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const leftPlaceholder = document.querySelector('.js-placeholder-left');
            const rightPlaceholder = document.querySelector('.js-placeholder-right');
            const leftBanner = document.querySelector('.location-side-banner-left');
            const rightBanner = document.querySelector('.location-side-banner-right');
            const footer = document.querySelector('.bravo_footer');
            
            function updateBanners() {
                // Chỉ kích hoạt trên màn hình máy tính (min-width: 992px)
                if (window.innerWidth < 992) {
                    if (leftBanner) {
                        leftBanner.classList.remove('is-fixed', 'is-hidden');
                        leftBanner.style.left = '';
                        leftBanner.style.width = '';
                    }
                    if (rightBanner) {
                        rightBanner.classList.remove('is-fixed', 'is-hidden');
                        rightBanner.style.right = '';
                        rightBanner.style.width = '';
                    }
                    return;
                }

                if (!leftPlaceholder) return;
                const leftRect = leftPlaceholder.getBoundingClientRect();

                // Khi chưa cuộn tới block (đỉnh cột placeholder cách mép trên màn hình > 130px)
                if (leftRect.top > 130) {
                    // Giữ vị trí tĩnh mặc định của banner trong block của nó
                    if (leftBanner) {
                        leftBanner.classList.remove('is-fixed', 'is-hidden');
                        leftBanner.style.left = '';
                        leftBanner.style.width = '';
                    }
                    if (rightBanner) {
                        rightBanner.classList.remove('is-fixed', 'is-hidden');
                        rightBanner.style.left = '';
                        rightBanner.style.width = '';
                    }
                } else {
                    // Khi cuộn tới block: Kích hoạt trạng thái Fixed trượt theo màn hình
                    if (leftBanner) {
                        leftBanner.classList.add('is-fixed');
                        leftBanner.classList.remove('is-hidden');
                        leftBanner.style.left = (leftRect.left + 10) + 'px'; // +10px padding
                        leftBanner.style.width = (leftRect.width - 20) + 'px';
                    }

                    if (rightPlaceholder && rightBanner) {
                        const rightRect = rightPlaceholder.getBoundingClientRect();
                        rightBanner.classList.add('is-fixed');
                        rightBanner.classList.remove('is-hidden');
                        rightBanner.style.left = (rightRect.left + 10) + 'px'; // +10px padding
                        rightBanner.style.width = (rightRect.width - 20) + 'px';
                    }

                    // Ẩn banner khi cuộn chạm tới Footer để không đè lên Footer
                    if (footer && leftBanner) {
                        const footerRect = footer.getBoundingClientRect();
                        const bannerHeight = leftBanner.offsetHeight;
                        
                        // Nếu mép trên Footer bắt đầu chạm tới mép dưới của Banner fixed
                        if (footerRect.top < bannerHeight + 150) {
                            leftBanner.classList.add('is-hidden');
                            if (rightBanner) rightBanner.classList.add('is-hidden');
                        }
                    }
                }
            }

            window.addEventListener('scroll', updateBanners);
            window.addEventListener('resize', updateBanners);
            
            // Chạy ngay sau khi load
            setTimeout(updateBanners, 300);
        });
    </script>
@endpush
