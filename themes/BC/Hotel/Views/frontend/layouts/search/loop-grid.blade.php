@php
    $translation = $row->translate();
@endphp
<div class="item-loop {{$wrap_class ?? ''}}">
    @if($row->is_featured == "1")
        <div class="featured">
            {{__("Featured")}}
        </div>
    @endif
    <div class="thumb-image ">
        <a @if(!empty($blank)) target="_blank" @endif href="{{$row->getDetailUrl()}}">
            @if($row->image_url)
                @if(!empty($disable_lazyload))
                    <img src="{{$row->image_url}}" class="img-responsive" alt="">
                @else
                    {!! get_image_tag($row->image_id,'medium',['class'=>'img-responsive','alt'=>$translation->title]) !!}
                @endif
            @endif
        </a>
        <div class="service-wishlist {{$row->isWishList()}}" data-id="{{$row->id}}" data-type="{{$row->type}}">
            <i class="fa fa-heart"></i>
        </div>
    </div>
    
    <div class="item-content">
        <div class="meta-header">
            <span class="service-type">{{__("Hotel")}}</span>
            @if($row->star_rate)
                <div class="star-rate-inline">
                    @for ($star = 1 ;$star <= $row->star_rate ; $star++)
                        <i class="fa fa-star"></i>
                    @endfor
                </div>
            @endif
            <span class="thumb-recommend"><i class="fa fa-thumbs-up"></i></span>
            @if($row->is_instant)
                <span class="badge-instant"><i class="fa fa-bolt"></i> {{__("Đặt ngay")}}</span>
            @else
                <span class="badge-verified"><i class="fa fa-check"></i> {{__("Đã xác minh")}}</span>
            @endif
        </div>

        <div class="item-title">
            <a @if(!empty($blank)) target="_blank" @endif href="{{$row->getDetailUrl()}}">
                @if($row->is_instant)
                    <i class="fa fa-bolt d-none"></i>
                @endif
                {{$translation->title}}
            </a>
        </div>

        <div class="location">
            @if(!empty($row->location->name))
                @php $location =  $row->location->translate() @endphp
                {{$location->name ?? ''}}
            @endif
        </div>

        @if(setting_item('hotel_enable_review'))
            @php
                $reviewData = $row->getScoreReview();
                $score_total = $reviewData['score_total'];
            @endphp
            @if($reviewData['total_review'] > 0)
                <div class="service-review-custom">
                    <div class="score-badge">
                        {{ str_replace('.', ',', number_format($score_total * 2, 1)) }}
                    </div>
                    <div class="review-meta">
                        <span class="review-text">{{$reviewData['review_text']}}</span>
                        <span class="review-count">
                            @if($reviewData['total_review'] > 1)
                                {{ __(":number reviews",["number"=>$reviewData['total_review'] ]) }}
                            @else
                                {{ __(":number review",["number"=>$reviewData['total_review'] ]) }}
                            @endif
                        </span>
                    </div>
                </div>
            @endif
        @endif

        <div class="price-section-custom">
            <span class="price-prefix">{{__("from")}}</span>
            <div class="price-group">
                @if($row->discount_percent && $row->price > 0)
                    <span class="old-price">{{ format_money($row->price) }}</span>
                @endif
                <span class="main-price">{{ $row->display_price }}</span>
            </div>
        </div>
    </div>
</div>

