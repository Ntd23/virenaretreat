<div class="container">
    <div class="bravo-list-hotel layout_{{$style_list}}">
        @if($title)
        <div class="title">
            {{$title}}
        </div>
        @endif
        @if($desc)
            <div class="sub-title">
                {{$desc}}
            </div>
        @endif
        <div class="list-item">
            @if($style_list === "normal")
                <div class="row">
                    @foreach($rows as $row)
                        <div class="col-lg-{{$col ?? 3}} col-md-6">
                            @include('Hotel::frontend.layouts.search.loop-grid')
                        </div>
                    @endforeach
                </div>
            @endif
            @if($style_list === "carousel")
                <div class="owl-carousel">
                    @foreach($rows as $row)
                        @include('Hotel::frontend.layouts.search.loop-grid')
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

@push('css')
    <style>
        /* Modernized Booking.com Style for Hotel List Block (Compact & Balanced Version) */
        .bravo-list-hotel .item-loop {
            background: #ffffff;
            border: 1px solid #f1f1f1 !important;
            border-radius: 12px !important;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
            transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.25s ease;
            margin-bottom: 30px;
            display: flex;
            flex-direction: column;
            height: calc(100% - 30px);
            position: relative;
        }

        .bravo-list-hotel .item-loop:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(15, 41, 77, 0.06);
        }

        /* Featured Badge ("NỔI BẬT" - Coral Orange) */
        .bravo-list-hotel .item-loop .featured {
            background: #ff523d !important;
            color: #ffffff !important;
            font-size: 10px !important;
            font-weight: 800 !important;
            padding: 3px 8px !important;
            border-radius: 4px !important;
            position: absolute !important;
            top: 10px !important;
            left: 10px !important;
            z-index: 2 !important;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 6px rgba(255, 82, 61, 0.2);
        }

        /* Thumbnail section (dẹt hơn với aspect-ratio 1.5) */
        .bravo-list-hotel .item-loop .thumb-image {
            position: relative;
            width: 100%;
            aspect-ratio: 1.5 !important; /* Dẹt hơn, giúp card ngắn lại */
            overflow: hidden;
            border-bottom: 1px solid #f1f1f1;
        }

        .bravo-list-hotel .item-loop .thumb-image a {
            display: block;
            width: 100%;
            height: 100%;
        }

        .bravo-list-hotel .item-loop .thumb-image img {
            width: 100%;
            height: 100%;
            object-fit: cover !important;
            transition: transform 0.35s ease;
        }

        .bravo-list-hotel .item-loop:hover .thumb-image img {
            transform: scale(1.03);
        }

        /* Wishlist Heart Button - White Circle floating */
        .bravo-list-hotel .item-loop .service-wishlist {
            position: absolute !important;
            top: 10px !important;
            right: 10px !important;
            z-index: 3 !important;
            width: 32px !important;
            height: 32px !important;
            background: #ffffff !important;
            border-radius: 50% !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06) !important;
            border: 1px solid rgba(0, 0, 0, 0.02) !important;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
            bottom: auto !important;
            left: auto !important;
        }

        .bravo-list-hotel .item-loop .service-wishlist:hover {
            transform: scale(1.06);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1) !important;
        }

        .bravo-list-hotel .item-loop .service-wishlist i {
            color: #0f294d !important; /* Deep Navy heart icon */
            font-size: 14px !important;
            transition: color 0.2s ease;
        }

        /* Wishlist Active (Liked) */
        .bravo-list-hotel .item-loop .service-wishlist.active i {
            color: #ff3366 !important;
        }

        /* Content Details Section */
        .bravo-list-hotel .item-loop .item-content {
            padding: 12px 14px 14px 14px !important; /* Gọn gàng hơn */
            display: flex !important;
            flex-direction: column !important;
            flex: 1 !important;
        }

        /* Meta Header: Property type, stars, genius, thumbs-up */
        .bravo-list-hotel .item-loop .meta-header {
            display: flex !important;
            align-items: center !important;
            flex-wrap: wrap !important;
            gap: 5px !important;
            margin-bottom: 6px !important;
        }

        .bravo-list-hotel .item-loop .meta-header .service-type {
            font-size: 12px !important;
            font-weight: 600 !important;
            color: #7f8c8d !important;
        }

        .bravo-list-hotel .item-loop .meta-header .star-rate-inline {
            display: flex !important;
            gap: 1.5px !important;
        }

        .bravo-list-hotel .item-loop .meta-header .star-rate-inline i {
            color: #ff9500 !important; /* Booking Yellow-Orange Star */
            font-size: 10px !important;
        }

        .bravo-list-hotel .item-loop .meta-header .thumb-recommend {
            background: #ff9500 !important;
            color: #ffffff !important;
            width: 15px !important;
            height: 15px !important;
            border-radius: 3px !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            font-size: 8px !important;
            margin-left: 2px !important;
        }

        .bravo-list-hotel .item-loop .meta-header .badge-instant {
            background: #003b95 !important; /* Deep Navy Blue */
            color: #ffffff !important;
            font-size: 9px !important;
            font-weight: 700 !important;
            padding: 2px 6px !important;
            border-radius: 4px !important;
            margin-left: 2px !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 2px !important;
        }

        .bravo-list-hotel .item-loop .meta-header .badge-instant i {
            font-size: 8px !important;
        }

        .bravo-list-hotel .item-loop .meta-header .badge-verified {
            background: #00a680 !important; /* Mint Green / Teal */
            color: #ffffff !important;
            font-size: 9px !important;
            font-weight: 700 !important;
            padding: 2px 6px !important;
            border-radius: 4px !important;
            margin-left: 2px !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 2px !important;
        }

        .bravo-list-hotel .item-loop .meta-header .badge-verified i {
            font-size: 8px !important;
        }

        /* Hotel Title (Spacious & Deep Navy Blue) */
        .bravo-list-hotel .item-loop .item-title {
            margin-bottom: 4px !important;
        }

        .bravo-list-hotel .item-loop .item-title a {
            font-size: 16px !important; /* Cân đối hơn */
            font-weight: 700 !important;
            color: #0f294d !important; /* Premium dark navy */
            line-height: 1.35 !important;
            text-decoration: none !important;
            transition: color 0.2s ease !important;
        }

        .bravo-list-hotel .item-loop .item-title a:hover {
            color: #003b95 !important;
        }

        /* Location */
        .bravo-list-hotel .item-loop .location {
            font-size: 12px !important;
            color: #7f8c8d !important;
            margin-bottom: 8px !important;
        }

        /* Review section - Booking.com style */
        .bravo-list-hotel .item-loop .service-review-custom {
            display: flex !important;
            align-items: center !important;
            gap: 8px !important;
            margin-bottom: auto !important; /* Pushes price section to bottom */
            padding-bottom: 8px !important;
        }

        .bravo-list-hotel .item-loop .service-review-custom .score-badge {
            background: #0f294d !important;
            color: #ffffff !important;
            font-size: 14px !important;
            font-weight: 700 !important;
            width: 32px !important;
            height: 32px !important;
            border-radius: 4px 4px 4px 0 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            flex-shrink: 0 !important;
        }

        .bravo-list-hotel .item-loop .service-review-custom .review-meta {
            display: flex !important;
            flex-direction: column !important;
            line-height: 1.2 !important;
        }

        .bravo-list-hotel .item-loop .service-review-custom .review-meta .review-text {
            font-size: 13px !important;
            font-weight: 700 !important;
            color: #0f294d !important;
        }

        .bravo-list-hotel .item-loop .service-review-custom .review-meta .review-count {
            font-size: 11px !important;
            color: #7f8c8d !important;
        }

        /* Price Section (Aligns perfectly to the bottom-right) */
        .bravo-list-hotel .item-loop .price-section-custom {
            display: flex !important;
            flex-direction: column !important;
            align-items: flex-end !important;
            margin-top: auto !important; /* Đẩy giá sát đáy khi không có reviews */
            padding-top: 8px !important;
        }

        .bravo-list-hotel .item-loop .price-section-custom .price-prefix {
            font-size: 11px !important;
            color: #7f8c8d !important;
            margin-bottom: 2px !important;
            text-transform: lowercase;
        }

        .bravo-list-hotel .item-loop .price-section-custom .price-group {
            display: flex !important;
            align-items: center !important;
            flex-wrap: wrap !important;
            justify-content: flex-end !important;
            gap: 5px !important;
        }

        .bravo-list-hotel .item-loop .price-section-custom .old-price {
            font-size: 13px !important;
            text-decoration: line-through !important;
            color: #d90429 !important; /* Red strike-through */
            font-weight: 600 !important;
        }

        .bravo-list-hotel .item-loop .price-section-custom .main-price {
            font-size: 19px !important; /* Giảm nhẹ để vừa vặn */
            font-weight: 800 !important;
            color: #0f294d !important;
        }
    </style>
@endpush