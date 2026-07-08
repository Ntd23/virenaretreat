@php
    $runningSearchAds = $running_search_ads ?? [];
@endphp
@if(empty($style) && count($runningSearchAds) > 1)
    <div class="effect">
        <div class="owl-carousel bravo-large-banner-carousel">
            @foreach($runningSearchAds as $advertisement)
                @php
                    $adImageUrl = $advertisement['image_url'] ?? '';
                    $adLinkUrl = $advertisement['link_url'] ?? '#';
                    $hasAdLink = $adLinkUrl && $adLinkUrl !== '#';
                @endphp
                <div class="item">
                    @if($hasAdLink)
                        <a href="{{$adLinkUrl}}" target="_blank" rel="noopener" class="item-bg advertisement-hero-slide-link" style="background-image: linear-gradient(0deg,rgba(0, 0, 0, 0.2),rgba(0, 0, 0, 0.2)),url('{{$adImageUrl}}') !important"></a>
                    @else
                        <div class="item-bg" style="background-image: linear-gradient(0deg,rgba(0, 0, 0, 0.2),rgba(0, 0, 0, 0.2)),url('{{$adImageUrl}}') !important"></div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
@elseif(!empty($style) and $style == "carousel" and !empty($list_slider))
    <div class="effect">
        <div class="owl-carousel">
            @foreach($list_slider as $item)
                @php $img = get_file_url($item['bg_image'],'full') @endphp
                <div class="item">
                    <div class="item-bg" style="background-image: linear-gradient(0deg,rgba(0, 0, 0, 0.2),rgba(0, 0, 0, 0.2)),url('{{$img}}') !important"></div>
                </div>
            @endforeach
        </div>
    </div>
@endif
<div class="container d-block">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="text-heading">{{$title}}</h1>
            <div class="sub-heading">{{$sub_title}}</div>
            @if(empty($hide_form_search))
                <div class="g-form-control">
                    <ul class="nav nav-tabs" role="tablist">
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

@if(empty($style) && count($runningSearchAds) > 1)
    @push('css')
        <style>
            .bravo-form-search-all .effect,
            .bravo-form-search-all .effect .owl-carousel,
            .bravo-form-search-all .effect .owl-stage-outer,
            .bravo-form-search-all .effect .owl-stage,
            .bravo-form-search-all .effect .owl-item,
            .bravo-form-search-all .effect .item,
            .bravo-form-search-all .effect .item-bg {
                height: 100%;
            }

            .bravo-form-search-all .effect .item-bg {
                display: block;
                width: 100%;
                background-position: center;
                background-size: cover;
            }
        </style>
    @endpush
    @push('js')
        <script>
            $(function () {
                $('.bravo-large-banner-carousel').each(function () {
                    var carousel = $(this);
                    if (carousel.hasClass('owl-loaded')) {
                        return;
                    }
                    carousel.owlCarousel({
                        items: 1,
                        loop: true,
                        margin: 0,
                        nav: false,
                        dots: true,
                        autoplay: true,
                        autoplayTimeout: 5000,
                        autoplayHoverPause: false,
                        animateOut: 'fadeOut'
                    });
                });
            });
        </script>
    @endpush
@endif
