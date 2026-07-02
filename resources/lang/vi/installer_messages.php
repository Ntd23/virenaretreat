<?php
return [
    /**
     *
     * Shared translations.
     *
     */
    'title'        => __('Trình cài đặt Laravel'),
    'next'         => __('Bước tiếp theo'),
    'back'         => __('Quay lại'),
    'finish'       => __('Cài đặt'),
    'forms'        => [
        'errorTitle' => __('Đã xảy ra các lỗi sau:'),
    ],
    /**
     *
     * Home page translations.
     *
     */
    'welcome'      => [
        'templateTitle' => __('Chào mừng'),
        'title'         => __('Trình cài đặt Laravel'),
        'message'       => __('Trình hướng dẫn thiết lập và cài đặt dễ dàng.'),
        'next'          => __('Kiểm tra yêu cầu hệ thống'),
    ],
    /**
     *
     * Requirements page translations.
     *
     */
    'requirements' => [
        'templateTitle' => __('Bước 1 | Yêu cầu máy chủ'),
        'title'         => __('Yêu cầu máy chủ'),
        'next'          => __('Kiểm tra quyền ghi'),
    ],
    /**
     *
     * Permissions page translations.
     *
     */
    'permissions'  => [
        'templateTitle' => __('Bước 2 | Quyền ghi mục'),
        'title'         => __('Quyền ghi thư mục'),
        'next'          => __('Cấu hình môi trường'),
    ],
    /**
     *
     * Environment page translations.
     *
     */
    'environment'  => [
        'menu'    => [
            'templateTitle'  => __('Bước 3 | Cài đặt môi trường'),
            'title'          => __('Cài đặt môi trường'),
            'desc'           => __('Vui lòng chọn cách bạn muốn định cấu hình tệp <code>.env</code> của ứng dụng.'),
            'wizard-button'  => __('Thiết lập bằng giao diện'),
            'classic-button' => __('Trình biên tập văn bản cổ điển'),
        ],
        'wizard'  => [
            'templateTitle' => __('Bước 3 | Cài đặt môi trường | Giao diện hướng dẫn'),
            'title'         => __('Giao diện hướng dẫn thiết lập <code>.env</code>'),
            'tabs'          => [
                'environment' => __('Môi trường'),
                'database'    => __('Cơ sở dữ liệu'),
                'application' => __('Ứng dụng')
            ],
            'form'          => [
                'name_required'                      => __('Tên môi trường là bắt buộc.'),
                'app_name_label'                     => __('Tên ứng dụng'),
                'app_name_placeholder'               => __('Tên ứng dụng'),
                'app_environment_label'              => __('Môi trường ứng dụng'),
                'app_environment_label_local'        => __('Local (Nội bộ)'),
                'app_environment_label_developement' => __('Development (Phát triển)'),
                'app_environment_label_qa'           => __('QA (Kiểm thử)'),
                'app_environment_label_production'   => __('Production (Vận hành)'),
                'app_environment_label_other'        => __('Khác'),
                'app_environment_placeholder_other'  => __('Nhập môi trường của bạn...'),
                'app_debug_label'                    => __('Bật Debug ứng dụng'),
                'app_debug_label_true'               => __('Đúng (True)'),
                'app_debug_label_false'              => __('Sai (False)'),
                'app_log_level_label'                => __('Cấp độ ghi Log'),
                'app_log_level_label_debug'          => __('debug'),
                'app_log_level_label_info'           => __('info'),
                'app_log_level_label_notice'         => __('notice'),
                'app_log_level_label_warning'        => __('warning'),
                'app_log_level_label_error'          => __('error'),
                'app_log_level_label_critical'       => __('critical'),
                'app_log_level_label_alert'          => __('alert'),
                'app_log_level_label_emergency'      => __('emergency'),
                'app_url_label'                      => __('URL ứng dụng'),
                'app_url_placeholder'                => __('URL ứng dụng'),
                'db_connection_label'                => __('Kết nối Cơ sở dữ liệu'),
                'db_connection_label_mysql'          => __('mysql'),
                'db_connection_label_sqlite'         => __('sqlite'),
                'db_connection_label_pgsql'          => __('pgsql'),
                'db_connection_label_sqlsrv'         => __('sqlsrv'),
                'db_host_label'                      => __('Database Host (Địa chỉ máy chủ)'),
                'db_host_placeholder'                => __('Database Host (Địa chỉ máy chủ)'),
                'db_port_label'                      => __('Cổng kết nối CSDL (Port)'),
                'db_port_placeholder'                => __('Cổng kết nối CSDL (Port)'),
                'db_name_label'                      => __('Tên cơ sở dữ liệu'),
                'db_name_placeholder'                => __('Tên cơ sở dữ liệu'),
                'db_username_label'                  => __('Tên đăng nhập CSDL'),
                'db_username_placeholder'            => __('Tên đăng nhập CSDL'),
                'db_password_label'                  => __('Mật khẩu kết nối CSDL'),
                'db_password_placeholder'            => __('Mật khẩu kết nối CSDL'),
                'app_tabs' => [
                    'more_info'                => __('Thông tin thêm'),
                    'broadcasting_title'       => __('Phát sóng, Bộ nhớ đệm, Phiên làm việc & Hàng đợi'),
                    'broadcasting_label'       => __('Trình điều khiển phát sóng'),
                    'broadcasting_placeholder' => __('Trình điều khiển phát sóng'),
                    'cache_label'              => __('Trình điều khiển bộ nhớ đệm (Cache)'),
                    'cache_placeholder'        => __('Trình điều khiển bộ nhớ đệm (Cache)'),
                    'session_label'            => __('Trình điều khiển phiên (Session)'),
                    'session_placeholder'      => __('Trình điều khiển phiên (Session)'),
                    'queue_label'              => __('Trình điều khiển hàng đợi (Queue)'),
                    'queue_placeholder'        => __('Trình điều khiển hàng đợi (Queue)'),
                    'redis_label'              => __('Trình điều khiển Redis'),
                    'redis_host'               => __('Redis Host'),
                    'redis_password'           => __('Mật khẩu Redis'),
                    'redis_port'               => __('Cổng Redis'),
                    'mail_label'                  => __('Cấu hình gửi Mail'),
                    'mail_driver_label'           => __('Trình gửi thư (Mail Driver)'),
                    'mail_driver_placeholder'     => __('Trình gửi thư (Mail Driver)'),
                    'mail_host_label'             => __('Mail Host (Địa chỉ máy chủ thư)'),
                    'mail_host_placeholder'       => __('Mail Host (Địa chỉ máy chủ thư)'),
                    'mail_port_label'             => __('Cổng gửi thư (Mail Port)'),
                    'mail_port_placeholder'       => __('Cổng gửi thư (Mail Port)'),
                    'mail_username_label'         => __('Tên đăng nhập Mail'),
                    'mail_username_placeholder'   => __('Tên đăng nhập Mail'),
                    'mail_password_label'         => __('Mật khẩu Mail'),
                    'mail_password_placeholder'   => __('Mật khẩu Mail'),
                    'mail_encryption_label'       => __('Mã hóa Mail (Encryption)'),
                    'mail_encryption_placeholder' => __('Mã hóa Mail (Encryption)'),
                    'pusher_label'                  => __('Cấu hình Pusher'),
                    'pusher_app_id_label'           => __('Pusher App Id'),
                    'pusher_app_id_palceholder'     => __('Pusher App Id'),
                    'pusher_app_key_label'          => __('Pusher App Key'),
                    'pusher_app_key_palceholder'    => __('Pusher App Key'),
                    'pusher_app_secret_label'       => __('Pusher App Secret'),
                    'pusher_app_secret_palceholder' => __('Pusher App Secret'),
                ],
                'buttons'  => [
                    'setup_database'    => __('Thiết lập Cơ sở dữ liệu'),
                    'setup_application' => __('Thiết lập ứng dụng'),
                    'install'           => __('Cài đặt'),
                ],
            ],
        ],
        'classic' => [
            'templateTitle' => __('Bước 3 | Cài đặt môi trường | Trình soạn thảo cổ điển'),
            'title'         => __('Trình soạn thảo môi trường cổ điển'),
            'save'          => __('Lưu tệp .env'),
            'back'          => __('Sử dụng giao diện hướng dẫn'),
            'install'       => __('Lưu và cài đặt'),
        ],
        'success' => __('Cài đặt tệp .env của bạn đã được lưu.'),
        'errors'  => __('Không thể lưu tệp .env, vui lòng tạo thủ công.'),
    ],
    'install'   => __('Cài đặt'),
    /**
     *
     * Installed Log translations.
     *
     */
    'installed' => [
        'success_log_message' => __('Trình cài đặt Laravel đã cài đặt thành công vào '),
    ],
    /**
     *
     * Final page translations.
     *
     */
    'final'     => [
        'title'         => __('Cài đặt hoàn tất'),
        'templateTitle' => __('Cài đặt hoàn tất'),
        'finished'      => __('Ứng dụng đã được cài đặt thành công.'),
        'migration'     => __('Kết quả chạy dòng lệnh Migration & Seed:'),
        'console'       => __('Kết quả dòng lệnh ứng dụng (Console Output):'),
        'log'           => __('Nhật ký cài đặt (Log Entry):'),
        'env'           => __('Tệp .env hoàn tất:'),
        'exit'          => __('Nhấp vào đây để thoát'),
    ],
    /**
     *
     * Update specific translations
     *
     */
    'updater'   => [
        /**
         *
         * Shared translations.
         *
         */
        'title'    => __('Trình cập nhật Laravel'),
        /**
         *
         * Welcome page translations for update feature.
         *
         */
        'welcome'  => [
            'title'   => __('Chào mừng đến với trình cập nhật'),
            'message' => __('Chào mừng bạn đến với giao diện cập nhật.'),
        ],
        /**
         *
         * Welcome page translations for update feature.
         *
         */
        'overview' => [
            'title'           => __('Tổng quan'),
            'message'         => __('Có 1 bản cập nhật.|Có :number bản cập nhật.'),
            'install_updates' => "Cài đặt các bản cập nhật"
        ],
        /**
         *
         * Final page translations.
         *
         */
        'final'    => [
            'title'    => __('Hoàn thành'),
            'finished' => __('Cơ sở dữ liệu của ứng dụng đã được cập nhật thành công.'),
            'exit'     => __('Nhấp vào đây để thoát'),
        ],
        'log' => [
            'success_message' => __('Trình cài đặt Laravel đã cập nhật thành công vào '),
        ],
    ],
];
