<div class="bravo-form-search-all <?php echo e($style); ?> <?php if(!empty($style) and $style == "carousel"): ?> bravo-form-search-slider <?php endif; ?>" <?php if(empty($style)): ?> style="background-image: linear-gradient(0deg,rgba(0, 0, 0, 0.0),rgba(0, 0, 0, 0.0)),url('<?php echo e($bg_image_url); ?>') !important;width: 100%;

    background-size: contain !important;
    background-repeat: no-repeat !important;
    background-position: top !important;
      " <?php endif; ?>>
    <?php if(in_array($style,['carousel',''])): ?>
        <?php echo $__env->make("Template::frontend.blocks.form-search-all-service.style-normal", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>
    <?php if($style == "carousel_v2"): ?>
        <?php echo $__env->make("Template::frontend.blocks.form-search-all-service.style-slider-ver2", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>
</div>
<?php /**PATH /Users/hoangquan/Desktop/virenaretreat/themes/Base/Template/Views/frontend/blocks/form-search-all-service/index.blade.php ENDPATH**/ ?>