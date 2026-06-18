@php $main_color = setting_item('style_main_color','#5191fa');
$style_typo = json_decode(setting_item_with_lang('style_typo',false,"{}"),true);
@endphp
    a,
    .bravo-news .btn-readmore,
    .bravo_wrap .bravo_header .content .header-left .bravo-menu ul li:hover > a,
    .bravo_wrap .bravo_search_tour .bravo_form_search .bravo_form .field-icon,
    .bravo_wrap .bravo_search_tour .bravo_form_search .bravo_form .render,
    .bravo_wrap .bravo_search_tour .bravo_form_search .bravo_form .field-detination #dropdown-destination .form-control,
    .bravo_wrap .bravo_search_tour .bravo_filter .g-filter-item .item-content .btn-apply-price-range,
    .bravo_wrap .bravo_search_tour .bravo_filter .g-filter-item .item-content .btn-more-item,
    .input-number-group i,
    .bravo_wrap .page-template-content .bravo-form-search-tour .bravo_form_search_tour .field-icon,
    .bravo_wrap .page-template-content .bravo-form-search-tour .bravo_form_search_tour .field-detination #dropdown-destination .form-control,
    .bravo_wrap .page-template-content .bravo-form-search-tour .bravo_form_search_tour .render,
    .hotel_rooms_form .form-search-rooms .form-search-row>div .form-group .render,
    .bravo_wrap .bravo_form .form-content .render,
    a:hover {
        color: {{$main_color}};
    }
    .bravo-pagination ul li.active a, .bravo-pagination ul li.active span
    {
        color:{{$main_color}}!important;
    }
    .bravo-news .widget_category ul li span,
    .bravo_wrap .bravo_search_tour .bravo_form_search .bravo_form .g-button-submit button,
    .bravo_wrap .bravo_search_tour .bravo_filter .filter-title:before,
    .bravo_wrap .bravo_search_tour .bravo_filter .g-filter-item .item-content .bravo-filter-price .irs--flat .irs-bar,
    .bravo_wrap .bravo_search_tour .bravo_filter .g-filter-item .item-content .bravo-filter-price .irs--flat .irs-from, .bravo_wrap .bravo_search_tour .bravo_filter .g-filter-item .item-content .bravo-filter-price .irs--flat .irs-to, .bravo_wrap .bravo_search_tour .bravo_filter .g-filter-item .item-content .bravo-filter-price .irs--flat .irs-single,
    .bravo_wrap .bravo_search_tour .bravo_filter .g-filter-item .item-content .bravo-filter-price .irs--flat .irs-handle>i:first-child,
    .bravo-news .header .cate ul li,
    .bravo_wrap .page-template-content .bravo-form-search-tour .bravo_form_search_tour .g-button-submit button,
    .bravo_wrap .page-template-content .bravo-list-locations .list-item .destination-item .image .content .desc,
    .bravo_wrap .bravo_detail_space .bravo_content .g-attributes h3:after,
    .bravo_wrap .bravo_form .g-button-submit button,
    .btn.btn-primary,
    .bravo_wrap .bravo_form .g-button-submit button:active,
    .btn.btn-primary:active,
    .bravo_wrap .bravo_detail_space .bravo-list-hotel-related-widget .heading:after,
    .btn-primary:not(:disabled):not(.disabled):active
    {
        background: {{$main_color}};
    }

    .bravo-pagination ul li.active a, .bravo-pagination ul li.active span
    {
        border-color:{{$main_color}}!important;
    }
    .bravo_wrap .bravo_search_tour .bravo_filter .g-filter-item .item-content .bravo-filter-price .irs--flat .irs-from:before, .bravo_wrap .bravo_search_tour .bravo_filter .g-filter-item .item-content .bravo-filter-price .irs--flat .irs-to:before, .bravo_wrap .bravo_search_tour .bravo_filter .g-filter-item .item-content .bravo-filter-price .irs--flat .irs-single:before,
    .bravo-reviews .review-form .form-wrapper,
    .bravo_wrap .bravo_detail_tour .bravo_content .bravo_tour_book
    {
        border-top-color:{{$main_color}};
    }

    .bravo_wrap .bravo_footer .main-footer .nav-footer .context .contact{
        border-left-color:{{$main_color}};
    }
    .hotel_rooms_form .form-search-rooms{
        border-bottom-color:{{$main_color}};
    }

    .bravo_wrap .bravo_form .field-icon,
    .bravo_wrap .bravo_form .smart-search .parent_text,
    .bravo_wrap .bravo_form .smart-search:after,
    .bravo_wrap .bravo_form .dropdown-toggle:after,
    .bravo_wrap .page-template-content .bravo-list-space .item-loop .service-review .rate,
    .bravo_wrap .bravo_search_space .bravo_filter .g-filter-item .item-content .btn-more-item,
    .bravo_wrap .bravo_detail_space .bravo_content .g-header .review-score .head .left .text-rating,
    .bravo-reviews .review-box .review-box-score .review-score,
    .bravo-reviews .review-box .review-box-score .review-score-base span,
    .bravo_wrap .bravo_detail_tour .bravo_content .g-header .review-score .head .left .text-rating
    {
        color: {{$main_color}};
    }

    .bravo_wrap .bravo_form .smart-search .parent_text::-webkit-input-placeholder{

        color: {{$main_color}};
    }
    .bravo_wrap .bravo_form .smart-search .parent_text::-moz-placeholder{

        color: {{$main_color}};
    }
    .bravo_wrap .bravo_form .smart-search .parent_text::-ms-input-placeholder{

        color: {{$main_color}};
    }
    .bravo_wrap .bravo_form .smart-search .parent_text::-moz-placeholder{

        color: {{$main_color}};
    }
    .bravo_wrap .bravo_form .smart-search .parent_text::placeholder{

        color: {{$main_color}};
    }


    .bravo_wrap .bravo_search_space .bravo-list-item .list-item .item-loop .service-review .rate,
    .bravo_wrap .bravo_search_space .bravo_filter .g-filter-item .item-content .btn-apply-price-range{

        color: {{$main_color}};
    }
    .bravo_wrap .page-template-content .bravo-list-locations.style_2 .list-item .destination-item:hover .title,
    .bravo_wrap .page-template-content .bravo-list-space .item-loop .sale_info,
    .bravo_wrap .bravo_search_space .bravo-list-item .list-item .item-loop .sale_info,
    .bravo_wrap .bravo_search_space .bravo_filter .filter-title:before,
    .bravo_wrap .bravo_detail_space .bravo_content .g-header .review-score .head .score,
    .bravo-reviews .review-form .btn,
    .bravo_wrap .bravo_search_space .bravo_filter .g-filter-item .item-content .bravo-filter-price .irs--flat .irs-bar,
    .bravo_wrap .bravo_search_space .bravo_filter .g-filter-item .item-content .bravo-filter-price .irs--flat .irs-from,
    .bravo_wrap .bravo_search_space .bravo_filter .g-filter-item .item-content .bravo-filter-price .irs--flat .irs-to,
    .bravo_wrap .bravo_search_space .bravo_filter .g-filter-item .item-content .bravo-filter-price .irs--flat .irs-single,
    .bravo_wrap .bravo_search_space .bravo_filter .g-filter-item .item-content .bravo-filter-price .irs--flat .irs-from,
    .bravo_wrap .bravo_search_space .bravo_filter .g-filter-item .item-content .bravo-filter-price .irs--flat .irs-to,
    .bravo_wrap .bravo_search_space .bravo_filter .g-filter-item .item-content .bravo-filter-price .irs--flat .irs-single,
    .bravo_wrap .bravo_search_space .bravo_filter .g-filter-item .item-content .bravo-filter-price .irs--flat .irs-handle>i:first-child
    {
        background: {{$main_color}};
    }
    .bravo_wrap .bravo_search_space .bravo_filter .g-filter-item .item-content .bravo-filter-price .irs--flat .irs-from:before, .bravo_wrap .bravo_search_space .bravo_filter .g-filter-item .item-content .bravo-filter-price .irs--flat .irs-to:before, .bravo_wrap .bravo_search_space .bravo_filter .g-filter-item .item-content .bravo-filter-price .irs--flat .irs-single:before {
        border-top-color: {{$main_color}};
    }

    .bravo_wrap .bravo_detail_space .bravo_content .g-overview ul li:before {
        border: 1px solid {{$main_color}};
    }

    .bravo_wrap .bravo_detail_space .bravo-list-space-related .item-loop .sale_info {
        background-color: {{$main_color}};
    }

    .bravo_wrap .bravo_detail_space .bravo_content .g-header .review-score .head .score::after {
        border-bottom: 25px solid {{$main_color}};
    }

    .bravo_wrap .bravo_detail_space .bravo_content .bravo_space_book {
        border-top: 5px solid {{$main_color}};
    }

    body .daterangepicker.loading:after {
        color: {{$main_color}};
    }

    body .daterangepicker .drp-calendar .calendar-table tbody tr td.end-date {
        border-right: solid 2px {{$main_color}};
    }
    body .daterangepicker .drp-calendar .calendar-table tbody tr td.start-date {
        border-left: solid 2px {{$main_color}};
    }
    .bravo_wrap .bravo_detail_space .bravo-list-space-related .item-loop .service-review .rate {
        color: {{$main_color}};
    }

    .has-search-map .bravo-filter-price .irs--flat .irs-bar,
    .has-search-map .bravo-filter-price .irs--flat .irs-handle>i:first-child,
    .has-search-map .bravo-filter-price .irs--flat .irs-from, .has-search-map .bravo-filter-price .irs--flat .irs-to, .has-search-map .bravo-filter-price .irs--flat .irs-single {
        background-color: {{$main_color}};
    }

    .has-search-map .bravo-filter-price .irs--flat .irs-from:before, .has-search-map .bravo-filter-price .irs--flat .irs-to:before, .has-search-map .bravo-filter-price .irs--flat .irs-single:before {
        border-top-color: {{$main_color}};
    }

    .bravo_wrap .bravo_detail_tour .bravo_content .g-header .review-score .head .score {
        background: {{$main_color}};
    }
    .bravo_wrap .bravo_detail_tour .bravo_content .g-header .review-score .head .score::after {
        border-bottom: 25px solid {{$main_color}};
    }

    .bravo_wrap .bravo_detail_tour .bravo_content .g-overview ul li:before {
        border: 1px solid {{$main_color}};
    }

    .bravo_wrap .bravo_detail_location .bravo_content .g-location-module .location-module-nav li a.active {
        border-bottom: 1px solid {{$main_color}};
        color: {{$main_color}};
    }

    .bravo_wrap .bravo_detail_location .bravo_content .g-location-module .item-loop .sale_info {
        background-color: {{$main_color}};
    }
    .bravo_wrap .page-template-content .bravo-featured-item.style2 .number-circle {
        border: 2px solid {{$main_color}};
        color: {{$main_color}};
    }
    .bravo_wrap .page-template-content .bravo-featured-item.style3 .featured-item:hover {
        border-color: {{$main_color}};
    }

    .booking-success-notice .booking-info-detail {
        border-left: 3px solid {{$main_color}};
    }
    .bravo_wrap .bravo_detail_tour .bravo_single_book,
    .bravo_wrap .bravo_detail_space .bravo_single_book {
        border-top: 5px solid{{$main_color}};
    }
    .bravo_wrap .page-template-content .bravo-form-search-all .g-form-control .nav-tabs li a.active {
        background-color: {{$main_color}};
        border-color: {{$main_color}};
    }

    .bravo_wrap .bravo_detail_location .bravo_content .g-location-module .item-loop .service-review .rate,
    .bravo_wrap .bravo_detail_location .bravo_content .g-trip-ideas .trip-idea .trip-idea-category,
    .bravo_wrap .bravo_footer .main-footer .nav-footer .context ul li a:hover,
    .bravo_wrap .bravo_detail_tour .bravo_content .g-attributes .list-attributes .item i.icon-default,
    .bravo_wrap .bravo_detail_space .bravo_content .g-attributes .list-attributes .item i.icon-default,
    .bravo_wrap .page-template-content .bravo-list-hotel .item-loop .service-review .rate,
    .bravo_wrap .page-template-content .bravo-list-tour.box_shadow .list-item .item .caption .title-address .title a:hover,
    .bravo_wrap .bravo_search_hotel .bravo-list-item .list-item .item-loop .service-review .rate,
    .bravo_wrap .bravo_search_hotel .bravo_filter .g-filter-item .item-content .btn-apply-price-range {
        color: {{$main_color}};
    }

    .bravo_wrap .bravo_detail_tour .bravo-list-tour-related .item-tour .featured ,
    .bravo_wrap .bravo_search_tour .bravo-list-item .list-item .item-tour .featured,
    .bravo_wrap .page-template-content .bravo-list-tour .item-tour .featured,
    .bravo_wrap .bravo_search_hotel .bravo_filter .filter-title:before {
        background: {{$main_color}};
    }
    .bravo_wrap .page-template-content .bravo-list-tour.box_shadow .list-item .item .header-thumb .tour-book-now,
    .bravo_wrap .bravo_search_hotel .bravo-list-item .list-item .item-loop .sale_info,
    .bravo_wrap .bravo_search_hotel .bravo_filter .g-filter-item .item-content .bravo-filter-price .irs--flat .irs-bar,
    .bravo_wrap .bravo_search_hotel .bravo_filter .g-filter-item .item-content .bravo-filter-price .irs--flat .irs-from,
    .bravo_wrap .bravo_search_hotel .bravo_filter .g-filter-item .item-content .bravo-filter-price .irs--flat .irs-to,
    .bravo_wrap .bravo_search_hotel .bravo_filter .g-filter-item .item-content .bravo-filter-price .irs--flat .irs-single,
    .bravo_wrap .bravo_search_hotel .bravo_filter .g-filter-item .item-content .bravo-filter-price .irs--flat .irs-from,
    .bravo_wrap .bravo_search_hotel .bravo_filter .g-filter-item .item-content .bravo-filter-price .irs--flat .irs-to,
    .bravo_wrap .bravo_search_hotel .bravo_filter .g-filter-item .item-content .bravo-filter-price .irs--flat .irs-single,
    .bravo_wrap .bravo_search_hotel .bravo_filter .g-filter-item .item-content .bravo-filter-price .irs--flat .irs-handle>i:first-child {
        background-color: {{$main_color}};
    }
    .bravo_wrap .bravo_search_hotel .bravo_filter .g-filter-item .item-content .bravo-filter-price .irs--flat .irs-from:before,
    .bravo_wrap .bravo_search_hotel .bravo_filter .g-filter-item .item-content .bravo-filter-price .irs--flat .irs-to:before,
    .bravo_wrap .bravo_search_hotel .bravo_filter .g-filter-item .item-content .bravo-filter-price .irs--flat .irs-single:before {
        border-top-color: {{$main_color}};
    }

    .bravo_wrap .bravo_search_hotel .bravo-list-item .list-item .item-loop-list .service-review-pc .head .score,
    .bravo_wrap .bravo_search_hotel .bravo_content .g-header .review-score .head .score {
        background: {{$main_color}};
    }

    .bravo_wrap .bravo_search_hotel .bravo_content .g-overview ul li:before {
        border: 1px solid {{$main_color}};
    }
    .bravo_wrap .bravo_search_hotel .bravo_filter .g-filter-item .item-content .btn-more-item,
    .bravo_wrap .bravo_search_hotel .bravo_content .g-header .review-score .head .left .text-rating,
    .bravo_wrap .bravo_search_hotel .bravo-list-item .list-item .item-loop-list .service-review-pc .head .left .text-rating,
    .bravo_wrap .bravo_detail_hotel  .btn-show-all,
    .bravo_wrap .bravo_detail_hotel  .bravo-list-hotel-related .item-loop .service-review .rate,
    .bravo_wrap .bravo_form .select-guests-dropdown .dropdown-item-row .count-display{
        color: {{$main_color}};
    }

    .bravo_wrap .bravo_search_hotel .bravo-list-item .list-item .item-loop-list .service-review-pc .head .score::after {
        border-bottom: 15px solid {{$main_color}};
    }
    .bravo_wrap .bravo_detail_hotel .bravo_content .g-header .review-score .head .score:after {
        border-bottom: 25px solid {{$main_color}};
    }
    .bravo_wrap .bravo_detail_hotel .bravo_content .g-header .review-score .head .score {
        background: {{$main_color}};
    }

    .bravo_wrap .bravo_detail_hotel .bravo-list-hotel-related-widget .heading:after {
        background: {{$main_color}};
    }
    .bravo_wrap .bravo_detail_hotel .bravo_content .g-attributes h3:after {
        background: {{$main_color}};
    }
    .bravo_wrap .bravo_detail_hotel .bravo_content .g-header .review-score .head .left .text-rating {
        color: {{$main_color}};
    }
    .bravo_wrap .select-guests-dropdown .dropdown-item-row .count-display {
        color: {{$main_color}};
    }

    .bravo_wrap .bravo-checkbox input[type=checkbox]:checked+.checkmark:after {
        border: solid {{$main_color}};
        border-width: 0 2px 2px 0;
    }
    .bravo_wrap .bravo_form .input-search .form-control::-webkit-input-placeholder {
        color: {{$main_color}};
    }
    .bravo_wrap .bravo_form .input-search .form-control:-ms-input-placeholder {
        color: {{$main_color}};
    }
    .brav_wrap .bravo_form .input-search .form-control::placeholder {
        color: {{$main_color}};
    }

    .bravo_wrap .bravo_search_event .bravo_filter .g-filter-item .item-content .btn-apply-price-range{
        color: {{$main_color}};
    }
    .bravo_wrap .bravo_search_event .bravo_filter .filter-title:before,
    .bravo_wrap .bravo_search_event .bravo_filter .g-filter-item .item-content .bravo-filter-price .irs--flat .irs-bar,
    .bravo_wrap .bravo_search_event .bravo_filter .g-filter-item .item-content .bravo-filter-price .irs--flat .irs-from,
    .bravo_wrap .bravo_search_event .bravo_filter .g-filter-item .item-content .bravo-filter-price .irs--flat .irs-to,
    .bravo_wrap .bravo_search_event .bravo_filter .g-filter-item .item-content .bravo-filter-price .irs--flat .irs-single,
    .bravo_wrap .bravo_search_event .bravo_filter .g-filter-item .item-content .bravo-filter-price .irs--flat .irs-from,
    .bravo_wrap .bravo_search_event .bravo_filter .g-filter-item .item-content .bravo-filter-price .irs--flat .irs-to,
    .bravo_wrap .bravo_search_event .bravo_filter .g-filter-item .item-content .bravo-filter-price .irs--flat .irs-single,
    .bravo_wrap .bravo_search_event .bravo_filter .g-filter-item .item-content .bravo-filter-price .irs--flat .irs-handle>i:first-child
    {
        background: {{$main_color}};
    }

    .bravo_wrap .bravo_search_event .bravo_filter .g-filter-item .item-content .bravo-filter-price .irs--flat .irs-from:before,
    .bravo_wrap .bravo_search_event .bravo_filter .g-filter-item .item-content .bravo-filter-price .irs--flat .irs-to:before,
    .bravo_wrap .bravo_search_event .bravo_filter .g-filter-item .item-content .bravo-filter-price .irs--flat .irs-single:before {
        border-top-color: {{$main_color}};
    }

    .bravo_wrap .bravo_search_event .bravo_filter .g-filter-item .item-content .btn-more-item {
        color: {{$main_color}};
    }

    .bravo_wrap .bravo_detail_event .bravo_content .g-header .review-score .head .score:after {
        border-bottom: 25px solid {{$main_color}};
    }
    .bravo_wrap .bravo_detail_event .bravo_content .g-header .review-score .head .score {
        background: {{$main_color}};
    }
    .bravo_wrap .bravo_detail_event .bravo_content .g-header .review-score .head .left .text-rating {
        color: {{$main_color}};
    }
    .bravo_wrap .bravo_single_book .nav-enquiry .enquiry-item.active span {
        border-bottom: solid 1px {{$main_color}} !important;
        color: {{$main_color}} !important;
    }
    .bravo_wrap .bravo_detail_event .bravo_content .g-overview ul li:before {
        border: 1px solid {{$main_color}};
    }
    .bravo_wrap .bravo_detail_event .bravo_content .g-attributes .list-attributes .item i.icon-default {
        color: {{$main_color}};
    }
    .bravo_wrap .bravo_detail_event .bravo_single_book {
        border-top: 5px solid {{$main_color}};
    }

    .bravo_wrap .bravo_detail_hotel .bravo_single_book {
        border-top: 5px solid {{$main_color}};
    }
    .bravo_wrap .bravo_detail_car  .bravo_single_book {
        border-top: 5px solid {{$main_color}};
    }
    .bravo_wrap .bravo_detail_car .bravo_content .g-header .review-score .head .score:after {
        border-bottom: 25px solid {{$main_color}};
    }
    .bravo_wrap .bravo_detail_car .bravo_content .g-header .review-score .head .score {
        background: {{$main_color}};
    }
    .bravo_wrap .bravo_detail_car .bravo_content .g-header .review-score .head .left .text-rating {
        color: {{$main_color}};
    }

    body{
    @if(!empty($style_typo) && is_array($style_typo))
        @foreach($style_typo as $k=>$v)
            @if($v)
                {{str_replace('_','-',$k)}}:{!! $v !!};
            @endif
        @endforeach
    @endif
    }
    @if(!empty($style_h1_font_family = setting_item_with_lang("style_h1_font_family") ))
        h1{
            font-family: {{ $style_h1_font_family }}, sans-serif
        }
    @endif
    @if(!empty($style_h2_font_family = setting_item_with_lang("style_h2_font_family") ))
        h2{
            font-family: {{ $style_h2_font_family }}, sans-serif
        }
    @endif
    @if(!empty($style_h3_font_family = setting_item_with_lang("style_h3_font_family") ))
        h3{
            font-family: {{ $style_h3_font_family }}, sans-serif
        }
    @endif

    {!! (setting_item('style_custom_css')) !!}
    {!! (setting_item_with_lang_raw('style_custom_css')) !!}

    /* CUSTOM STYLING FOR MODERN HEADER MENU BY ANTIGRAVITY */
    @media (min-width: 1200px) {
        .bravo_wrap .bravo_header .container {
            max-width: 1380px !important; /* Mở rộng container để chứa đủ các phần tử */
        }
    }
    .bravo_wrap .bravo_header {
        background: #ffffff;
        border-bottom: 1px solid #f1f1f1;
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        padding: 12px 0;
    }
    .bravo_wrap .bravo_header .content {
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        width: 100%;
    }

    /* Header Left: Logo and Menu */
    .bravo_wrap .bravo_header .content .header-left {
        display: flex !important;
        align-items: center !important;
        gap: 20px !important;
        flex: 1 !important;
        min-width: 0;
    }
    .bravo_wrap .bravo_header .bravo-logo {
        flex-shrink: 0;
    }
    .bravo_wrap .bravo_header .bravo-logo img {
        max-height: 40px;
        width: auto !important;
    }

    /* Navigation Menu Items */
    .bravo_wrap .bravo_header .content .header-left .bravo-menu {
        flex: 1 !important;
    }
    .bravo_wrap .bravo_header .content .header-left .bravo-menu ul {
        display: flex !important;
        flex-direction: row !important;
        flex-wrap: nowrap !important; /* Không cho phép rớt dòng */
        align-items: center !important;
        margin: 0 !important;
        padding: 0 !important;
        list-style: none !important;
        gap: 6px !important; /* Thu hẹp khoảng cách */
    }
    .bravo_wrap .bravo_header .content .header-left .bravo-menu ul > li {
        float: none !important;
        display: inline-block !important;
    }
    .bravo_wrap .bravo_header .content .header-left .bravo-menu ul.main-menu > li > a {
        font-weight: 600 !important;
        font-size: 13px !important; /* Thu nhỏ font menu để vừa dòng */
        letter-spacing: 0.3px !important;
        text-transform: uppercase !important;
        padding: 8px 10px !important; /* Giảm padding */
        position: relative;
        transition: color 0.3s ease;
        white-space: nowrap !important; /* Không rớt chữ */
    }
    .bravo_wrap .bravo_header .content .header-left .bravo-menu ul.main-menu > li > a::after {
        content: '';
        position: absolute;
        width: 0;
        height: 2px;
        bottom: 0;
        left: 10px;
        background-color: #c5a880;
        transition: width 0.3s ease;
    }
    .bravo_wrap .bravo_header .content .header-left .bravo-menu ul.main-menu > li:hover > a {
        color: #c5a880 !important;
    }
    .bravo_wrap .bravo_header .content .header-left .bravo-menu ul.main-menu > li:hover > a::after {
        width: calc(100% - 20px);
    }

    /* Header Right: Topbar Items (Language, Currency, Account) and CTA Button */
    .bravo_wrap .bravo_header .content .header-right {
        display: flex !important;
        align-items: center !important;
        gap: 15px !important;
        flex-shrink: 0 !important;
    }
    .bravo_wrap .bravo_header .content .header-right .header-topbar-items {
        display: flex !important;
        align-items: center !important;
        margin: 0 !important;
        padding: 0 !important;
        list-style: none !important;
        gap: 10px !important;
    }
    .bravo_wrap .bravo_header .content .header-right .header-topbar-items > li {
        padding: 0 !important;
        display: inline-flex !important;
        align-items: center !important;
    }
    .bravo_wrap .bravo_header .content .header-right .header-topbar-items li a {
        font-weight: 500 !important;
        font-size: 12px !important;
        transition: color 0.3s ease;
        padding: 4px 6px !important;
        white-space: nowrap !important;
    }
    /* Bo tròn logo cờ ngôn ngữ */
    .bravo_wrap .bravo_header .content .header-right .header-topbar-items .language-dropdown span.flag-icon {
        display: inline-block !important;
        width: 20px !important;
        height: 20px !important;
        border-radius: 50% !important;
        background-size: cover !important;
        background-position: center !important;
        background-repeat: no-repeat !important;
        box-shadow: 0 1px 3px rgba(0,0,0,0.15) !important;
        vertical-align: middle !important;
    }
    .bravo_wrap .bravo_header .content .header-right .header-topbar-items .language-dropdown a {
        display: inline-flex !important;
        align-items: center !important;
        gap: 4px !important;
    }

    /* User login info styling */
    .bravo_wrap .bravo_header .content .header-right .header-topbar-items .is_login {
        display: inline-flex !important;
        align-items: center !important;
        position: relative !important;
        gap: 8px !important;
        padding-right: 0 !important;
        padding-left: 0 !important;
    }
    .bravo_wrap .bravo_header .content .header-right .header-topbar-items .is_login img.avatar,
    .bravo_wrap .bravo_header .content .header-right .header-topbar-items .is_login .avatar-text {
        position: static !important;
        float: none !important;
        display: inline-block !important;
        width: 28px !important;
        height: 28px !important;
        line-height: 28px !important;
        border-radius: 50% !important;
        margin: 0 !important;
        object-fit: cover !important;
        border: 2px solid rgba(255,255,255,0.8) !important;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1) !important;
        vertical-align: middle !important;
    }
    .bravo_wrap .bravo_header .content .header-right .header-topbar-items .is_login .avatar-text {
        background: #c5a880 !important;
        color: #ffffff !important;
        text-align: center !important;
        font-weight: bold !important;
        font-size: 12px !important;
    }
    .bravo_wrap .bravo_header.header-sticky .content .header-right .header-topbar-items .is_login img.avatar {
        border-color: rgba(197, 168, 128, 0.5) !important;
    }

    /* Header in Home Page / Transparent state */
    .bravo_wrap .bravo_header.header-transparent {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        background: transparent;
        border-bottom: 1px solid rgba(255, 255, 255, 0.15);
        z-index: 999;
    }
    .bravo_wrap .bravo_header.header-transparent:not(.header-sticky) .bravo-menu ul.main-menu > li > a {
        color: #ffffff !important;
        text-shadow: 0 1px 2px rgba(0,0,0,0.2);
    }
    .bravo_wrap .bravo_header.header-transparent:not(.header-sticky) .bravo-logo img {
        filter: brightness(0) invert(1);
    }
    .bravo_wrap .bravo_header.header-transparent:not(.header-sticky) .header-topbar-items li a {
        color: #ffffff !important;
        text-shadow: 0 1px 2px rgba(0,0,0,0.2);
    }
    .bravo_wrap .bravo_header.header-transparent:not(.header-sticky) .header-topbar-items li.dropdown .dropdown-menu li a {
        color: #333333 !important;
        text-shadow: none;
    }
    .bravo_wrap .bravo_header.header-transparent:not(.header-sticky) .bravo-more-menu {
        color: #ffffff !important;
    }

    /* Header Sticky state (when scrolled) */
    .bravo_wrap .bravo_header.header-sticky {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        background: rgba(255, 255, 255, 0.88) !important;
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.08);
        border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        z-index: 9999;
        padding: 8px 0;
    }
    .bravo_wrap .bravo_header.header-sticky .bravo-menu ul.main-menu > li > a {
        color: #2D3748 !important;
    }
    .bravo_wrap .bravo_header.header-sticky .header-topbar-items li a {
        color: #2D3748 !important;
    }

    /* Nút CTA "Book Now" */
    .bravo_wrap .bravo_header .header-btn-book {
        display: flex !important;
        align-items: center !important;
    }
    .bravo_wrap .bravo_header .header-btn-book .btn-book-now {
        background: linear-gradient(135deg, #c5a880 0%, #b39268 100%) !important;
        color: #ffffff !important;
        font-weight: 600 !important;
        font-size: 13px !important;
        text-transform: uppercase !important;
        letter-spacing: 0.5px !important;
        padding: 8px 20px !important;
        border-radius: 30px !important;
        border: none !important;
        box-shadow: 0 4px 12px rgba(197, 168, 128, 0.3) !important;
        transition: all 0.3s ease !important;
        white-space: nowrap !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        width: auto !important;
        height: auto !important;
        line-height: 1.2 !important;
    }
    .bravo_wrap .bravo_header .header-btn-book .btn-book-now:hover {
        transform: translateY(-1px) !important;
        box-shadow: 0 6px 16px rgba(197, 168, 128, 0.45) !important;
        background: linear-gradient(135deg, #b39268 0%, #9e7f56 100%) !important;
        text-decoration: none !important;
    }

    /* Hide original topbar on desktop, keep topbar settings */
    @media (min-width: 992px) {
        .bravo_wrap .bravo_topbar {
            display: none !important;
        }
    }
    /* Mobile Topbar and Header Adjustments */
    @media (max-width: 991px) {
        .bravo_wrap .bravo_header .header-btn-book {
            display: none !important; /* Ẩn nút Book Now trên mobile để tránh chật chội */
        }
        .bravo_wrap .bravo_header .header-topbar-items {
            display: none !important; /* Ẩn các selector ngôn ngữ/tiền tệ trên header chính mobile, dùng mobile menu */
        }
    }

    /* Dropdown Menu Styling (Language & User Account) */
    .bravo_wrap .bravo_header .content .header-right .header-topbar-items .dropdown-menu {
        background: #ffffff !important;
        border: 1px solid rgba(0, 0, 0, 0.06) !important;
        border-radius: 12px !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1) !important;
        padding: 8px 0 !important;
        margin-top: 10px !important;
        min-width: 210px !important;
        animation: headerDropdownFade 0.3s ease;
        left: auto !important;
        right: 0 !important;
    }
    @keyframes headerDropdownFade {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .bravo_wrap .bravo_header .content .header-right .header-topbar-items .dropdown-menu li {
        padding: 0 !important;
        margin: 0 !important;
        display: block !important;
    }
    .bravo_wrap .bravo_header .content .header-right .header-topbar-items .dropdown-menu li a {
        color: #2D3748 !important;
        font-size: 13.5px !important;
        font-weight: 500 !important;
        padding: 10px 20px !important;
        display: flex !important;
        align-items: center !important;
        gap: 10px !important;
        transition: all 0.2s ease !important;
        text-shadow: none !important;
        background: transparent !important;
    }
    .bravo_wrap .bravo_header .content .header-right .header-topbar-items .dropdown-menu li a i {
        color: #c5a880 !important; /* Icon màu vàng đồng sang trọng */
        font-size: 15px !important;
        width: 18px !important;
        text-align: center !important;
        margin: 0 !important;
    }
    .bravo_wrap .bravo_header .content .header-right .header-topbar-items .dropdown-menu li a:hover {
        background-color: #faf6f0 !important; /* Nền vàng kem rất nhẹ */
        color: #b39268 !important; /* Chữ đổi sang vàng đồng đậm */
        padding-left: 24px !important; /* Dịch nhẹ tạo cảm giác mượt mà */
        text-decoration: none !important;
    }
    
    /* Đường phân cách trong dropdown */
    .bravo_wrap .bravo_header .content .header-right .header-topbar-items .dropdown-menu li.menu-hr {
        border-top: 1px solid #f1f1f1 !important;
        margin-top: 5px !important;
    }
    .bravo_wrap .bravo_header .content .header-right .header-topbar-items .dropdown-menu li.credit_amount a {
        font-weight: 600 !important;
        color: #c5a880 !important;
    }

    /* FULL SCREEN HERO SLIDE (100VH) BY ANTIGRAVITY */
    .bravo_wrap .page-template-content .bravo-form-search-all {
        height: 100vh !important;
        min-height: 650px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        padding: 0 !important;
        position: relative !important;
        overflow: hidden !important;
        background-size: 100% 100% !important;
        background-position: center !important;
        background-repeat: no-repeat !important;
    }
    
    

    .bravo_wrap .page-template-content .bravo-form-search-all .container {
        position: relative !important;
        z-index: 2 !important;
    }

    /* Đảm bảo hiệu ứng slider tràn viền 100% chiều cao */
    .bravo_wrap .page-template-content .bravo-form-search-all .effect,
    .bravo_wrap .page-template-content .bravo-form-search-all .effect .owl-carousel,
    .bravo_wrap .page-template-content .bravo-form-search-all .effect .owl-stage-outer,
    .bravo_wrap .page-template-content .bravo-form-search-all .effect .owl-stage,
    .bravo_wrap .page-template-content .bravo-form-search-all .effect .owl-item,
    .bravo_wrap .page-template-content .bravo-form-search-all .effect .item {
        height: 100% !important;
    }
    
    .bravo_wrap .page-template-content .bravo-form-search-all .effect .item .item-bg {
        height: 100% !important;
        background-size: 100% 100% !important;
        background-position: center !important;
        background-repeat: no-repeat !important;
    }