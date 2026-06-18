<?php
    $banner_left_url = !empty($banner_left_img) ? get_file_url($banner_left_img, 'full') : '';
    $banner_right_url = !empty($banner_right_img) ? get_file_url($banner_right_img, 'full') : '';
?>
<div class="container-fluid px-0">
    <div class="row align-items-stretch list-locations-banner-wrapper no-gutters">
        <!-- Left Banner -->
        <div class="col-xl-2 col-lg-3 d-none d-lg-block">
            <a href="<?php echo e($banner_left_link ?: '#'); ?>" class="location-side-banner d-block h-100">
                <div class="banner-bg" style="background-image: url('<?php echo e($banner_left_url ?: 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=600&q=80'); ?>');"></div>
                <div class="banner-overlay"></div>

            </a>
        </div>

        <!-- Central Grid -->
        <div class="col-xl-8 col-lg-6 col-md-12 px-4 px-md-5">
            <div class="bravo-list-locations <?php if(!empty($layout)): ?> <?php echo e($layout); ?> <?php endif; ?>">
                <div class="title">
                    <?php echo e($title); ?>

                </div>
                <?php if(!empty($desc)): ?>
                    <div class="sub-title">
                        <?php echo e($desc); ?>

                    </div>
                <?php endif; ?>
                <?php if(!empty($rows)): ?>
                    <div class="list-item">
                        <div class="row">
                            <?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                $size_col = 4;
                                if( !empty($layout) and (  $layout == "style_2" or $layout == "style_3" or $layout == "style_4" )){
                                    $size_col = 4;
                                }else{
                                    if($key == 0){
                                        $size_col = 8;
                                    }
                                }
                                ?>
                                <div class="col-lg-<?php echo e($size_col); ?> col-md-6">
                                    <?php echo $__env->make('Location::frontend.blocks.list-locations.loop', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Right Banner -->
        <div class="col-xl-2 col-lg-3 d-none d-lg-block">
            <a href="<?php echo e($banner_right_link ?: '#'); ?>" class="location-side-banner d-block h-100">
                <div class="banner-bg" style="background-image: url('<?php echo e($banner_right_url ?: 'https://images.unsplash.com/photo-1476514525535-07fb3b4ae5f1?auto=format&fit=crop&w=600&q=80'); ?>');"></div>
                <div class="banner-overlay"></div>

            </a>
        </div>
    </div>
</div>

<?php $__env->startPush('css'); ?>
    <style>
        .list-locations-banner-wrapper {
            margin-left: 0 !important;
            margin-right: 0 !important;
            width: 100% !important;
        }
        .list-locations-banner-wrapper > .col-xl-2,
        .list-locations-banner-wrapper > .col-lg-3 {
            padding-left: 10px !important;
            padding-right: 10px !important;
        }
        .location-side-banner {
            position: relative;
            overflow: hidden;
            height: 100%;
            min-height: 450px;
            display: flex;
            align-items: flex-end;
            padding: 30px 24px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.05);
            transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
            text-decoration: none !important;
            transform: translateY(-8px);
        }
        .list-locations-banner-wrapper > div:first-child .location-side-banner {
            border-radius: 6px;
        }
        .list-locations-banner-wrapper > div:last-child .location-side-banner {
            border-radius: 6px;
        }
        .location-side-banner .banner-bg {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-size: cover;
            background-position: center;
            transition: transform 0.6s cubic-bezier(0.25, 0.8, 0.25, 1);
            z-index: 0;
        }
        .location-side-banner .banner-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            transition: background 0.4s ease;
            z-index: 1;
        }
        .location-side-banner:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }
        .location-side-banner:hover .banner-bg {
            transform: scale(1.1);
        }
        
        .location-side-banner .banner-content {
            position: relative;
            z-index: 2;
            color: #fff;
            width: 100%;
        }
        .location-side-banner .banner-badge {
            background: rgba(255,255,255,0.25);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            color: #fff;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: inline-block;
            margin-bottom: 12px;
        }
        .location-side-banner .banner-title {
            font-size: 24px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 20px;
            line-height: 1.3;
        }
        .location-side-banner .banner-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: #fff;
            color: #1A2B48;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        .location-side-banner:hover .banner-btn {
            background: #5191FA;
            color: #fff;
            transform: translateX(4px);
        }
        .list-locations-banner-wrapper .bravo-list-locations {
            margin: 0 !important;
        }
    </style>
<?php $__env->stopPush(); ?><?php /**PATH /home/virenaretreat/main/themes/BC/Location/Views/frontend/blocks/list-locations/index.blade.php ENDPATH**/ ?>