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
<div class="container d-none d-lg-block mt-lg-6" style="position: relative; z-index: 1; margin-top: 100px;">
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
                </div>
            @endif
        </div>
    </div>
</div>

@push('css')
    <style>
        .bravo_wrap .page-template-content .bravo-form-search-all {
            padding: 20px 0;
        }
        /* Chiều cao động cho slider trên mobile/tablet */
        @media (max-width: 991px) {
            .bravo_wrap .page-template-content .bravo-form-search-all {
                padding: 0 !important;
                height: 280px !important; /* Màn hình iPad / Máy tính bảng */
            }
            .bravo_wrap .page-template-content .bravo-form-search-all .effect,
            .bravo_wrap .page-template-content .bravo-form-search-all .effect * {
                height: 100% !important;
            }
        }
        @media (max-width: 768px) {
            .bravo_wrap .page-template-content .bravo-form-search-all {
                height: 200px !important; /* Màn hình điện thoại lớn / ngang */
            }
        }
        @media (max-width: 576px) {
            .bravo_wrap .page-template-content .bravo-form-search-all {
                height: 150px !important; /* Màn hình điện thoại nhỏ đứng */
            }
        }
        /* Màn hình siêu lớn (iMac/Desktop lớn) */
        .responsive-search-tabs {
            margin-top: 500px !important;
        }
        /* Màn hình desktop phụ (1600px trở xuống) */
        @media (max-width: 1600px) {
            .responsive-search-tabs {
                margin-top: 430px !important;
            }
        }
        /* Màn hình laptop lớn (1400px trở xuống) */
        @media (max-width: 1400px) {
            .responsive-search-tabs {
                margin-top: 390px !important;
            }
        }
        /* Màn hình laptop trung bình (1200px trở xuống) */
        @media (max-width: 1200px) {
            .responsive-search-tabs {
                margin-top: 300px !important;
            }
        }
        /* Màn hình laptop nhỏ / máy tính bảng lớn (1024px trở xuống) */
        @media (max-width: 1024px) {
            .responsive-search-tabs {
                margin-top: 260px !important;
            }
        }
        /* Màn hình máy tính bảng trung bình (991px trở xuống) */
        @media (max-width: 991px) {
            .responsive-search-tabs {
                margin-top: 120px !important;
            }
        }
        /* Màn hình máy tính bảng dọc / Điện thoại lớn (768px trở xuống) */
        @media (max-width: 768px) {
            .responsive-search-tabs {
                margin-top: 80px !important;
            }
        }
        /* Màn hình điện thoại di động (576px trở xuống) */
        @media (max-width: 576px) {
            .responsive-search-tabs {
                margin-top: 40px !important;
            }
        }
    </style>
@endpush