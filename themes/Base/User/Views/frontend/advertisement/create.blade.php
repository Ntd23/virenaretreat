@extends('layouts.user')

@php
    $currentUser = auth()->user();
@endphp

@push('css')
    <style>
        .advertisement-create-page .title-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 22px;
        }

        .advertisement-create-page .title-bar .btn {
            border-radius: 6px;
            padding: 10px 18px;
            font-weight: 700;
        }

        .advertisement-form-card {
            overflow: hidden;
            border: 1px solid #e6edf5;
            border-radius: 8px;
            background: #fff;
            box-shadow: 0 10px 30px rgba(26, 43, 72, 0.07);
        }

        .advertisement-form-header {
            padding: 24px 28px;
            border-bottom: 1px solid #edf1f6;
            background: #f8fafd;
        }

        .advertisement-form-header h3 {
            margin: 0;
            color: #1a2b48;
            font-size: 22px;
            font-weight: 700;
        }

        .advertisement-form-body {
            padding: 28px;
        }

        .advertisement-form-section {
            margin-bottom: 26px;
            padding-bottom: 24px;
            border-bottom: 1px solid #edf1f6;
        }

        .advertisement-form-section:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: 0;
        }

        .advertisement-section-title {
            margin: 0 0 18px;
            color: #1a2b48;
            font-size: 17px;
            font-weight: 700;
        }

        .advertisement-create-page .form-group {
            margin-bottom: 18px;
        }

        .advertisement-create-page label {
            margin-bottom: 8px;
            color: #253858;
            font-weight: 700;
        }

        .advertisement-create-page .form-control {
            min-height: 46px;
            border-color: #d8e1ec;
            border-radius: 6px;
            color: #253858;
            box-shadow: none;
            transition: border-color .2s ease, box-shadow .2s ease;
        }

        .advertisement-create-page textarea.form-control {
            min-height: 148px;
            resize: vertical;
        }

        .advertisement-create-page .form-control:focus {
            border-color: #2684ff;
            box-shadow: 0 0 0 3px rgba(38, 132, 255, 0.12);
        }

        .advertisement-create-page .input-has-icon .form-group {
            position: relative;
        }

        .advertisement-create-page .input-has-icon .form-group .form-control {
            padding-left: 12px !important;
            padding-right: 12px !important;
        }

        .advertisement-create-page .input-icon {
            display: none !important;
        }

        .advertisement-create-page input[type="file"].form-control {
            height: auto;
            padding: 11px 12px;
            background: #fbfcfe;
        }

        .advertisement-form-actions {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            padding-top: 4px;
        }

        .advertisement-form-actions .btn {
            min-height: 44px;
            border-radius: 6px;
            padding: 10px 20px;
            font-weight: 700;
        }

        @media (max-width: 767px) {
            .advertisement-create-page .title-bar {
                align-items: flex-start;
                flex-direction: column;
            }

            .advertisement-form-header,
            .advertisement-form-body {
                padding: 20px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="advertisement-create-page">
        <h2 class="title-bar">
            <span>{{__("Tạo yêu cầu quảng cáo")}}</span>
            <a href="{{route('user.advertisement.index')}}" class="btn btn-default">
                <i class="fa fa-arrow-left"></i> {{__("Quay lại")}}
            </a>
        </h2>
        @include('admin.message')

        <form action="{{route('user.advertisement.store')}}" method="post" class="input-has-icon advertisement-form-card" enctype="multipart/form-data">
            @csrf
            <div class="advertisement-form-header">
                <h3>{{__("Thông tin quảng cáo")}}</h3>
            </div>
            <div class="advertisement-form-body">
                <div class="advertisement-form-section">
                    <h4 class="advertisement-section-title">{{__("Nội dung chính")}}</h4>
                    <div class="row">
                        <div class="col-md-12">
                <div class="form-group">
                    <label>{{__("Tiêu đề")}} <span class="text-danger">*</span></label>
                    <input type="text" name="title" value="{{old('title')}}" class="form-control" required>
                    <i class="fa fa-bullhorn input-icon"></i>
                </div>
                <div class="form-group">
                    <label>{{__("Link đích")}}</label>
                    <input type="url" name="target_url" value="{{old('target_url')}}" class="form-control" placeholder="https://">
                    <i class="fa fa-link input-icon"></i>
                </div>
                        </div>
                    </div>
                </div>

                <div class="advertisement-form-section">
                    <h4 class="advertisement-section-title">{{__("Thông tin khách hàng")}}</h4>
                    <div class="row">
                        <div class="col-md-6">
                <div class="form-group">
                    <label>{{__("Tên khách hàng")}}</label>
                    <input type="text" name="customer_name" value="{{old('customer_name', $currentUser ? $currentUser->getDisplayName() : '')}}" class="form-control">
                </div>
                        </div>
                        <div class="col-md-6">
                <div class="form-group">
                    <label>{{__("Email")}}</label>
                    <input type="email" name="customer_email" value="{{old('customer_email', $currentUser->email ?? '')}}" class="form-control">
                </div>
                        </div>
                    </div>
                    <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{__("Số điện thoại")}}</label>
                            <input type="text" name="customer_phone" value="{{old('customer_phone', $currentUser->phone ?? '')}}" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{__("Địa chỉ")}}</label>
                            <input type="text" name="customer_address" value="{{old('customer_address', $currentUser->address ?? '')}}" class="form-control">
                        </div>
                    </div>
                    </div>
                </div>

                <div class="advertisement-form-section">
                    <h4 class="advertisement-section-title">{{__("Mô tả và hình ảnh")}}</h4>
                <div class="form-group">
                    <label>{{__("Mô tả nội dung quảng cáo")}}</label>
                    <textarea name="description" rows="6" class="form-control">{{old('description')}}</textarea>
                </div>
                <div class="form-group">
                    <label>{{__("Hình ảnh quảng cáo")}}</label>
                    <input type="file" name="media_files[]" class="form-control" accept="image/*" multiple>
                    <small class="form-text text-muted">{{__("Có thể chọn nhiều ảnh. Tối đa 10 ảnh, mỗi ảnh tối đa 50MB.")}}</small>
                </div>
                </div>

                <div class="advertisement-form-actions">
                <button class="btn btn-primary" type="submit">
                    <i class="fa fa-paper-plane"></i> {{__("Gửi yêu cầu")}}
                </button>
                <a href="{{route('user.advertisement.index')}}" class="btn btn-default">{{__("Hủy")}}</a>
                </div>
            </div>
        </form>
    </div>
@endsection
