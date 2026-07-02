@extends('admin.layouts.app')

@push('css')
    <style>
        .advertisement-pricing-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 20px;
        }

        .advertisement-pricing-table .form-control {
            min-width: 120px;
        }

        .advertisement-pricing-table .position-name {
            font-weight: 600;
        }

        .advertisement-pricing-table .position-code {
            color: #6c757d;
            font-size: 12px;
            margin-top: 4px;
        }

        .advertisement-pricing-note {
            color: #6c757d;
            margin: 0;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="advertisement-pricing-header">
            <h1 class="title-bar mb-0">{{__("Giá tiền quảng cáo")}}</h1>
            <a href="{{route('admin.advertisements.index')}}" class="btn btn-secondary">
                <i class="fa fa-arrow-left"></i> {{__("Quay lại danh sách")}}
            </a>
        </div>

        @include('admin.message')

        <form method="post" action="{{route('admin.advertisements.pricing.update')}}">
            @csrf
            <div class="panel">
                <div class="panel-title">
                    <strong>{{__("Vị trí quảng cáo và giá tiền")}}</strong>
                </div>
                <div class="panel-body">
                    @if($positions->count())
                        <div class="table-responsive">
                            <table class="table table-hover advertisement-pricing-table">
                                <thead>
                                <tr>
                                    <th>{{__("Vị trí quảng cáo")}}</th>
                                    <th>{{__("Trang / khu vực")}}</th>
                                    <th>{{__("Kích thước")}}</th>
                                    <th>{{__("Giá tiền / ngày")}}</th>
                                    <th>{{__("Số lượng cho thuê")}}</th>
                                    <th>{{__("Trạng thái")}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($positions as $position)
                                    <tr>
                                        <td>
                                            <div class="position-name">{{$position->name}}</div>
                                            <div class="position-code">{{$position->code}}</div>
                                        </td>
                                        <td>
                                            <div>{{$position->page ?: '-'}}</div>
                                            <small class="text-muted">{{$position->placement ?: '-'}}</small>
                                        </td>
                                        <td>
                                            @if($position->width || $position->height)
                                                {{(int) $position->width}} x {{(int) $position->height}}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            <input type="number"
                                                   name="positions[{{$position->id}}][base_price]"
                                                   class="form-control"
                                                   min="0"
                                                   step="1000"
                                                   value="{{old('positions.'.$position->id.'.base_price', (float) $position->base_price)}}">
                                        </td>
                                        <td>
                                            <strong>{{$position->code === 'large_banner' ? 3 : 1}}</strong>
                                        </td>
                                        <td>
                                            <input type="hidden" name="positions[{{$position->id}}][is_active]" value="0">
                                            <label class="mb-0">
                                                <input type="checkbox"
                                                       name="positions[{{$position->id}}][is_active]"
                                                       value="1"
                                                       @if(old('positions.'.$position->id.'.is_active', $position->is_active)) checked @endif>
                                                {{__("Đang bật")}}
                                            </label>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <p class="advertisement-pricing-note">
                            {{__("Giá tiền là đơn giá theo ngày. Số lượng cho thuê là số quảng cáo tối đa có thể chạy cùng lúc ở vị trí đó.")}}
                        </p>
                    @else
                        <div class="alert alert-warning mb-0">
                            {{__("Chưa có vị trí quảng cáo nào. Vui lòng chạy migration seed vị trí quảng cáo mặc định.")}}
                        </div>
                    @endif
                </div>
                @if($positions->count())
                    <div class="panel-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i> {{__("Lưu giá tiền")}}
                        </button>
                    </div>
                @endif
            </div>
        </form>
    </div>
@endsection
