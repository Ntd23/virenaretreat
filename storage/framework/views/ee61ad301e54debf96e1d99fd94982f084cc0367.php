<?php if(!empty($style) and $style == "carousel" and !empty($list_slider)): ?>
    <div class="effect">
        <div class="owl-carousel">
            <?php $__currentLoopData = $list_slider; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php $img = get_file_url($item['bg_image'],'full') ?>
                <div class="item">
                    <div class="item-bg" style="background-image: linear-gradient(0deg,rgba(0, 0, 0, 0.0),rgba(0, 0, 0, 0.0)),url('<?php echo e($img); ?>') !important"></div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
<?php endif; ?>
<div class="container d-none d-lg-block mt-lg-6" style="position: relative; z-index: 1; margin-top: 100px;">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="text-heading"><?php echo e($title); ?></h1>
            <div class="sub-heading"><?php echo e($sub_title); ?></div>
            <?php if(empty($hide_form_search)): ?>
                <div class="g-form-control" style="">
                    <ul class="nav nav-tabs responsive-search-tabs" role="tablist">
                        <?php if(!empty($service_types)): ?>
                            <?php $number = 0; ?>
                            <?php $__currentLoopData = $service_types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service_type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $allServices = get_bookable_services();
                                    if(empty($allServices[$service_type])) continue;
                                    $module = new $allServices[$service_type];
                                ?>
                                <li role="bravo_<?php echo e($service_type); ?>">
                                    <a href="#bravo_<?php echo e($service_type); ?>" class="<?php if($number == 0): ?> active <?php endif; ?>" aria-controls="bravo_<?php echo e($service_type); ?>" role="tab" data-toggle="tab">
                                        <i class="<?php echo e($module->getServiceIconFeatured()); ?>"></i>
                                        <?php echo e(!empty($modelBlock["title_for_".$service_type]) ? $modelBlock["title_for_".$service_type] : $module->getModelName()); ?>

                                    </a>
                                </li>
                                <?php $number++; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    </ul>
                    <div class="tab-content">
                        <?php if(!empty($service_types)): ?>
                            <?php $number = 0; ?>
                            <?php $__currentLoopData = $service_types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service_type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $allServices = get_bookable_services();
                                    if(empty($allServices[$service_type])) continue;
                                    $module = new $allServices[$service_type];
                                ?>
                                <div role="tabpanel" class="tab-pane <?php if($number == 0): ?> active <?php endif; ?>" id="bravo_<?php echo e($service_type); ?>">
                                    <?php echo $__env->make(ucfirst($service_type).'::frontend.layouts.search.form-search', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>
                                <?php $number++; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php $__env->startPush('css'); ?>
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
<?php $__env->stopPush(); ?><?php /**PATH /Users/hoangquan/Desktop/virenaretreat/themes/Base/Template/Views/frontend/blocks/form-search-all-service/style-normal.blade.php ENDPATH**/ ?>