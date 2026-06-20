@if(!empty($style) and $style == "carousel" and !empty($list_slider))
    <div class="effect">
        <div class="owl-carousel">
            @foreach($list_slider as $item)
                @php $img = get_file_url($item['bg_image'],'full') @endphp
                <div class="item">
                    <div class="item-bg" style="background-image: linear-gradient(0deg,rgba(0, 0, 0, 0.0),rgba(0, 0, 0, 0.0)),url('{{$img}}') !important"></div>
                </div>
            @endforeach
        </div>
    </div>
@endif
<div class="container d-none d-lg-block" style="position: relative; z-index: 1; margin-top: 550px !important; max-width: 1380px !important;">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="text-heading">{{$title}}</h1>
            <div class="sub-heading">{{$sub_title}}</div>
            @if(empty($hide_form_search))
                <div class="g-form-control" style="">
                    <ul class="nav nav-tabs responsive-search-tabs" role="tablist">
                        @if(!empty($service_types))
                            @php $number = 0; @endphp
                            @foreach ($service_types as $service_type)
                                @php
                                    $allServices = get_bookable_services();
                                    if(empty($allServices[$service_type])) continue;
                                    $module = new $allServices[$service_type];
                                @endphp
                                <li role="bravo_{{$service_type}}">
                                    <a href="#bravo_{{$service_type}}" class="@if($number == 0) active @endif" aria-controls="bravo_{{$service_type}}" role="tab" data-toggle="tab">
                                        <i class="{{ $module->getServiceIconFeatured() }}"></i>
                                        {{ !empty($modelBlock["title_for_".$service_type]) ? $modelBlock["title_for_".$service_type] : $module->getModelName() }}
                                    </a>
                                </li>
                                @php $number++; @endphp
                            @endforeach
                        @endif
                    </ul>
                    <div class="tab-content">
                        @if(!empty($service_types))
                            @php $number = 0; @endphp
                            @foreach ($service_types as $service_type)
                                @php
                                    $allServices = get_bookable_services();
                                    if(empty($allServices[$service_type])) continue;
                                    $module = new $allServices[$service_type];
                                @endphp
                                <div role="tabpanel" class="tab-pane @if($number == 0) active @endif" id="bravo_{{$service_type}}">
                                    @include(ucfirst($service_type).'::frontend.layouts.search.form-search')
                                </div>
                                @php $number++; @endphp
                            @endforeach
                        @endif
                    </div>
                    
                    <!-- Thanh thông tin USPs dưới Form Tìm kiếm (Thiết kế mới) -->
                    <div class="search-usps-row d-none d-lg-flex">
                        <div class="usp-item">
                            <i class="usp-icon icofont-badge"></i>
                            <div class="usp-text">
                                <span class="usp-title">Giá tốt nhất</span>
                                <span class="usp-desc">Cam kết giá tốt nhất</span>
                            </div>
                        </div>
                        <div class="usp-item">
                            <i class="usp-icon icofont-ui-calendar"></i>
                            <div class="usp-text">
                                <span class="usp-title">Xác nhận nhanh</span>
                                <span class="usp-desc">Đặt phòng dễ dàng</span>
                            </div>
                        </div>
                        <div class="usp-item">
                            <i class="usp-icon icofont-headphone-alt-2"></i>
                            <div class="usp-text">
                                <span class="usp-title">Hỗ trợ 24/7</span>
                                <span class="usp-desc">Luôn sẵn sàng hỗ trợ</span>
                            </div>
                        </div>
                        <div class="usp-item">
                            <i class="usp-icon icofont-credit-card"></i>
                            <div class="usp-text">
                                <span class="usp-title">Thanh toán linh hoạt</span>
                                <span class="usp-desc">Đa dạng phương thức</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@push('css')
    <style>
        .bravo_wrap .page-template-content .bravo-form-search-all .g-form-control .tab-content #bravo_car, .bravo_wrap .page-template-content .bravo-form-search-all .g-form-control .tab-content #bravo_event, .bravo_wrap .page-template-content .bravo-form-search-all .g-form-control .tab-content #bravo_tour {
    max-width: 100% !important;
}
        /* Container cha (Slider) - Đẩy nội dung xuống sát đáy màn hình */
        .bravo_wrap .page-template-content .bravo-form-search-all {
            padding: 0 !important;
            position: relative !important;
            height: 100vh !important;
            min-height: 650px !important;
            display: flex !important;
            flex-direction: column !important;
            justify-content: flex-end !important;
        }

        /* Tiêu đề & Subtitle */
        .bravo-form-search-all .text-heading {
            font-size: 44px;
            font-weight: 800;
            color: #ffffff;
            text-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            text-align: center;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }

        .bravo-form-search-all .sub-heading {
            font-size: 18px;
            color: rgba(255, 255, 255, 0.95);
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
            text-align: center;
            font-weight: 400;
            margin-bottom: 15px;
        }

        /* Glassmorphism Hộp bao quanh toàn bộ Tab + Form + USPs */
        .bravo-form-search-all .g-form-control {
            background: rgba(18, 18, 18, 0.72) !important;
            backdrop-filter: blur(20px) !important;
            -webkit-backdrop-filter: blur(20px) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            border-radius: 24px !important;
            padding: 24px 32px 24px 32px !important;
            box-shadow: 0 24px 60px rgba(0, 0, 0, 0.4) !important;
            max-width: 1380px !important;
            margin: 0 auto;
            transition: all 0.3s ease;
        }

        /* Tabs phẳng tối mờ, căn lề trái */
        .bravo-form-search-all .responsive-search-tabs {
            border-bottom: none !important;
            display: flex !important;
            justify-content: flex-start !important;
            gap: 4px !important;
            padding: 0 !important;
            background: transparent !important;
            border-radius: 0 !important;
            width: 100% !important;
            margin: 0 0 -1px 0 !important;
            box-shadow: none !important;
        }

        .bravo-form-search-all .responsive-search-tabs li {
            margin: 0 !important;
            display: inline-block;
        }

        .bravo-form-search-all .responsive-search-tabs li a {
            border: none !important;
            border-radius: 12px 12px 0 0 !important;
            padding: 12px 24px !important;
            font-size: 13px !important;
            font-weight: 700 !important;
            color: rgba(255, 255, 255, 0.6) !important;
            background: rgba(0, 0, 0, 0.2) !important;
            display: flex !important;
            align-items: center !important;
            gap: 8px !important;
            transition: all 0.3s ease !important;
            text-transform: uppercase !important;
            letter-spacing: 0.5px !important;
        }

        .bravo-form-search-all .responsive-search-tabs li a i {
            font-size: 16px !important;
            color: rgba(255, 255, 255, 0.5) !important;
            transition: transform 0.25s ease;
        }

        .bravo-form-search-all .responsive-search-tabs li a:hover:not(.active) {
            color: #ffffff !important;
            background: rgba(255, 255, 255, 0.05) !important;
        }

        /* Active Tab - Màu Vàng Gold luxury phẳng */
        .bravo-form-search-all .responsive-search-tabs li a.active {
            color: #c5a880 !important;
            background: rgba(255, 255, 255, 0.08) !important;
            box-shadow: none !important;
        }

        .bravo-form-search-all .responsive-search-tabs li a.active i {
            color: #c5a880 !important;
        }

        /* Dọn dẹp hoàn toàn đốm trắng/border của tab panel */
        .bravo-form-search-all .tab-content {
            background: transparent !important;
            border: none !important;
            padding: 12px 0 0 0 !important;
            box-shadow: none !important;
            width: 100% !important;
        }
        
        .bravo-form-search-all .tab-content::before,
        .bravo-form-search-all .tab-content::after,
        .bravo-form-search-all .tab-pane::before,
        .bravo-form-search-all .tab-pane::after {
            display: none !important;
            content: none !important;
        }

        .bravo-form-search-all .tab-pane {
            background: transparent !important;
            border: none !important;
            padding: 0 !important;
            margin: 0 !important;
            box-shadow: none !important;
            width: 100% !important;
        }

        .bravo-form-search-all .tab-pane.active {
            display: block !important;
            width: 100% !important;
        }

        /* Form tìm kiếm màu trắng */
        .bravo-form-search-all .bravo_form {
            display: flex !important;
            flex-direction: row !important;
            align-items: center !important;
            background: #ffffff !important;
            border-radius: 16px !important;
            padding: 8px 8px 8px 24px !important;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08) !important;
            width: 100% !important;
            gap: 10px !important;
        }

        .bravo-form-search-all .bravo_form .g-field-search {
            flex: 1 !important;
            padding: 0 !important;
        }

        .bravo-form-search-all .bravo_form .g-field-search .row {
            margin: 0 !important;
            align-items: center !important;
            width: 100% !important;
        }

        /* Phân chia các cột */
        .bravo-form-search-all .bravo_form .g-field-search .row > div {
            padding: 4px 28px 4px 15px !important; /* Giảm padding để tăng chiều rộng hiển thị chữ */
            border-right: 1px solid #ededed !important;
            position: relative !important;
            overflow: visible !important; /* Đảm bảo ngày tháng không bị cắt khuất */
        }

        @media (min-width: 992px) {
            .bravo-form-search-all .bravo_form .g-field-search .row {
                display: flex !important;
                flex-wrap: nowrap !important;
            }
            .bravo-form-search-all .bravo_form .g-field-search .row > div {
                flex: 1 1 0% !important; /* Tự động chia đều chiều rộng trên desktop */
                max-width: none !important;
            }
        }

        .bravo-form-search-all .bravo_form .g-field-search .row > div:last-child {
            border-right: none !important;
        }

        /* Ẩn triệt để toàn bộ các mũi tên cũ chồng chéo */
        .bravo-form-search-all .bravo_form i.fa-angle-down,
        .bravo-form-search-all .bravo_form .arrow,
        .bravo-form-search-all .bravo_form .dropdown-toggle::after,
        .bravo-form-search-all .bravo_form .form-group::after,
        .bravo-form-search-all .bravo_form .form-content::after,
        .bravo-form-search-all .bravo_form select,
        .bravo-form-search-all .bravo_form .smart-search::after,
        .bravo-form-search-all .bravo_form .smart-search:after {
            display: none !important;
            content: none !important;
        }

        /* Một mũi tên màu vàng duy nhất thẳng hàng - Chỉ hiển thị cho cột Địa điểm (cột 1) và cột Khách (cột 3) */
        .bravo-form-search-all .bravo_form .g-field-search .row > div:nth-child(1)::after,
        .bravo-form-search-all .bravo_form .g-field-search .row > div:nth-child(3)::after {
            content: "\f107" !important;
            font-family: "FontAwesome" !important;
            color: #c5a880 !important;
            font-size: 14px !important;
            position: absolute !important;
            right: 15px !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            pointer-events: none !important;
        }

        /* Căn chỉnh form-group */
        .bravo-form-search-all .bravo_form .form-group {
            display: flex !important;
            align-items: center !important;
            gap: 14px !important;
            margin-bottom: 0 !important;
            width: 100% !important;
        }

        .bravo-form-search-all .bravo_form .form-group .field-icon {
            position: static !important;
            font-size: 24px !important;
            color: #c5a880 !important;
            margin: 0 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            width: 24px !important;
            height: 24px !important;
        }

        .bravo-form-search-all .bravo_form .form-group .form-content {
            padding: 0 !important;
            flex: 1 !important;
        }

        .bravo-form-search-all .bravo_form .form-group .form-content label {
            font-size: 12px !important;
            color: #8c8c8c !important;
            font-weight: 500 !important;
            margin-bottom: 2px !important;
            text-transform: none !important;
            letter-spacing: 0 !important;
            display: block !important;
            line-height: 1.2 !important;
        }

        .bravo-form-search-all .bravo_form .form-group .form-content .form-control,
        .bravo-form-search-all .bravo_form .form-group .form-content input {
            font-size: 15px !important;
            color: {{ setting_item('style_main_color','#5191fa') }} !important;
            -webkit-text-fill-color: {{ setting_item('style_main_color','#5191fa') }} !important;
            font-weight: 600 !important;
            background: transparent !important;
            border: none !important;
            padding: 0 !important;
            height: auto !important;
            line-height: 1.2 !important;
            width: auto !important;
            max-width: none !important;
            text-overflow: clip !important;
            overflow: visible !important;
            white-space: nowrap !important;
        }

        /* Đồng bộ màu chữ placeholder trực tiếp cho từng trình duyệt */
        .bravo-form-search-all .bravo_form .form-group .form-content input::-webkit-input-placeholder,
        .bravo-form-search-all .bravo_form .form-group .form-content input::-moz-placeholder,
        .bravo-form-search-all .bravo_form .form-group .form-content input:-ms-input-placeholder,
        .bravo-form-search-all .bravo_form .form-group .form-content input::placeholder,
        .bravo-form-search-all .bravo_form .form-group .form-content .smart-search-location::-webkit-input-placeholder,
        .bravo-form-search-all .bravo_form .form-group .form-content .smart-search-location::-moz-placeholder,
        .bravo-form-search-all .bravo_form .form-group .form-content .smart-search-location:-ms-input-placeholder,
        .bravo-form-search-all .bravo_form .form-group .form-content .smart-search-location::placeholder,
        .bravo-form-search-all .bravo_form .form-group .form-content .parent_text::-webkit-input-placeholder,
        .bravo-form-search-all .bravo_form .form-group .form-content .parent_text::-moz-placeholder,
        .bravo-form-search-all .bravo_form .form-group .form-content .parent_text:-ms-input-placeholder,
        .bravo-form-search-all .bravo_form .form-group .form-content .parent_text::placeholder {
            color: {{ setting_item('style_main_color','#5191fa') }} !important;
            -webkit-text-fill-color: {{ setting_item('style_main_color','#5191fa') }} !important;
            opacity: 0.8 !important;
        }

        /* Xử lý căn ngang check-in-out ngày tháng không bị xuống hàng */
        .bravo-form-search-all .bravo_form .check-in-wrapper {
            display: flex !important;
            flex-direction: row !important;
            align-items: center !important;
            gap: 8px !important;
            width: 100% !important;
            overflow: visible !important;
        }

        .bravo-form-search-all .bravo_form .check-in-wrapper .render {
            display: inline-block !important;
            white-space: nowrap !important;
            font-size: 15px !important;
            color: #1a1a1a !important;
            font-weight: 600 !important;
            text-overflow: clip !important;
            overflow: visible !important;
            max-width: none !important;
            width: auto !important;
        }

        .bravo-form-search-all .bravo_form .check-in-wrapper span {
            color: #8c8c8c !important;
            font-weight: 500 !important;
        }

        .bravo-form-search-all .bravo_form .check-in-out {
            position: absolute !important;
            left: 0 !important;
            top: 0 !important;
            width: 100% !important;
            height: 100% !important;
            opacity: 0 !important;
            cursor: pointer !important;
            z-index: 2 !important;
        }

        /* Nút TÌM KIẾM */
        .bravo-form-search-all .bravo_form .g-button-submit {
            padding: 0 !important;
            margin: 0 !important;
            min-width: 140px !important;
        }

        .bravo-form-search-all .bravo_form .g-button-submit .btn-search {
            background: #c5a880 !important;
            color: #1a1a1a !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            border-radius: 12px !important;
            border: none !important;
            font-size: 14px !important;
            letter-spacing: 0.5px !important;
            height: 52px !important;
            width: 100% !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            transition: all 0.3s ease !important;
            box-shadow: 0 4px 14px rgba(197, 168, 128, 0.25) !important;
        }

        .bravo-form-search-all .bravo_form .g-button-submit .btn-search:hover {
            background: #b3966e !important;
            box-shadow: 0 6px 20px rgba(197, 168, 128, 0.4) !important;
            transform: translateY(-1px) !important;
        }

        /* Style cho khối USPs (Cam kết dưới form) */
        .bravo-form-search-all .search-usps-row {
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
            margin-top: 24px !important;
            padding-top: 20px !important;
            border-top: 1px solid rgba(255, 255, 255, 0.08) !important;
        }

        .bravo-form-search-all .usp-item {
            display: flex !important;
            align-items: center !important;
            gap: 12px !important;
            flex: 1 !important;
            justify-content: center !important;
            border-right: 1px solid rgba(255, 255, 255, 0.08) !important;
            padding: 0 15px !important;
        }

        .bravo-form-search-all .usp-item:last-child {
            border-right: none !important;
        }

        .bravo-form-search-all .usp-icon {
            color: #c5a880 !important;
            font-size: 24px !important;
        }

        .bravo-form-search-all .usp-text {
            display: flex !important;
            flex-direction: column !important;
            align-items: flex-start !important;
        }

        .bravo-form-search-all .usp-title {
            color: #ffffff !important;
            font-size: 13px !important;
            font-weight: 600 !important;
        }

        .bravo-form-search-all .usp-desc {
            color: rgba(255, 255, 255, 0.5) !important;
            font-size: 11px !important;
            font-weight: 400 !important;
        }

        /* Responsive Settings for Mobile/Tablet */
        @media (max-width: 991px) {
            .bravo_wrap .page-template-content .bravo-form-search-all {
                padding: 0 !important;
                height: auto !important;
                min-height: auto !important;
                display: block !important;
            }
            .bravo_wrap .page-template-content .bravo-form-search-all .effect,
            .bravo_wrap .page-template-content .bravo-form-search-all .effect * {
                height: 300px !important;
            }
            
            /* Giao diện dọc trên mobile */
            .bravo-form-search-all .bravo_form {
                flex-direction: column !important;
                padding: 15px !important;
                border-radius: 12px !important;
                gap: 15px !important;
            }
            .bravo-form-search-all .bravo_form .g-field-search .row > div {
                border-right: none !important;
                border-bottom: 1px solid #f0f0f0 !important;
                padding: 10px 0 !important;
            }
            .bravo-form-search-all .bravo_form .g-field-search .row > div:last-child {
                border-bottom: none !important;
            }
            .bravo-form-search-all .bravo_form .g-field-search .row > div::after {
                right: 5px !important;
            }
            .bravo-form-search-all .bravo_form .g-button-submit {
                width: 100% !important;
            }
            .bravo-form-search-all .bravo_form .g-button-submit .btn-search {
                width: 100% !important;
                height: 48px !important;
            }
            .bravo-form-search-all .responsive-search-tabs li a {
                border-radius: 8px 8px 0 0 !important;
                padding: 8px 16px !important;
                font-size: 11px !important;
            }
        }
        @media (max-width: 768px) {
            .bravo_wrap .page-template-content .bravo-form-search-all .effect,
            .bravo_wrap .page-template-content .bravo-form-search-all .effect * {
                height: 240px !important;
            }
        }
        @media (max-width: 576px) {
            .bravo_wrap .page-template-content .bravo-form-search-all .effect,
            .bravo_wrap .page-template-content .bravo-form-search-all .effect * {
                height: 180px !important;
            }
        /* Loại bỏ giới hạn max-width của các module khác trên trang chủ */
        .bravo_wrap .page-template-content .bravo-form-search-all .g-form-control .tab-content #bravo_car,
        .bravo_wrap .page-template-content .bravo-form-search-all .g-form-control .tab-content #bravo_event,
        .bravo_wrap .page-template-content .bravo-form-search-all .g-form-control .tab-content #bravo_tour,
        .bravo_wrap .page-template-content .bravo-form-search-all .g-form-control .tab-content #bravo_flight,
        .bravo_wrap .page-template-content .bravo-form-search-all .g-form-control .tab-content #bravo_boat,
        .bravo_wrap .page-template-content .bravo-form-search-all .g-form-control .tab-content #bravo_hotel {
            max-width: none !important;
        }
    </style>
@endpush