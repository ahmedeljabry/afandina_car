<?php $__currentLoopData = $activeLanguages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="tab-pane fade <?php echo e($loop->first ? 'show active' : ''); ?>" 
         id="<?php echo e($language->code); ?>" 
         role="tabpanel" 
         aria-labelledby="<?php echo e($language->code); ?>-tab">
        
        <div class="form-group">
            <label for="title_<?php echo e($language->code); ?>">Title (<?php echo e(strtoupper($language->code)); ?>)</label>
            <input type="text" 
                   class="form-control <?php $__errorArgs = ['title.' . $language->code];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                   id="title_<?php echo e($language->code); ?>" 
                   name="title[<?php echo e($language->code); ?>]" 
                   value="<?php echo e(old('title.' . $language->code, $model->translate($language->code)->title ?? '')); ?>">
            <?php $__errorArgs = ['title.' . $language->code];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="form-group">
            <label for="article_<?php echo e($language->code); ?>">Article (<?php echo e(strtoupper($language->code)); ?>)</label>
            <textarea class="form-control editor <?php $__errorArgs = ['article.' . $language->code];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                      id="article_<?php echo e($language->code); ?>" 
                      name="article[<?php echo e($language->code); ?>]" 
                      rows="5"><?php echo e(old('article.' . $language->code, $model->translate($language->code)->article ?? '')); ?></textarea>
            <?php $__errorArgs = ['article.' . $language->code];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <!-- SEO Fields -->
        <div class="form-group">
            <label for="meta_title_<?php echo e($language->code); ?>">Meta Title (<?php echo e(strtoupper($language->code)); ?>)</label>
            <input type="text" 
                   class="form-control <?php $__errorArgs = ['meta_title.' . $language->code];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                   id="meta_title_<?php echo e($language->code); ?>" 
                   name="meta_title[<?php echo e($language->code); ?>]" 
                   value="<?php echo e(old('meta_title.' . $language->code, $model->translate($language->code)->meta_title ?? '')); ?>">
            <?php $__errorArgs = ['meta_title.' . $language->code];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="form-group">
            <label for="meta_description_<?php echo e($language->code); ?>">Meta Description (<?php echo e(strtoupper($language->code)); ?>)</label>
            <textarea class="form-control <?php $__errorArgs = ['meta_description.' . $language->code];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                      id="meta_description_<?php echo e($language->code); ?>" 
                      name="meta_description[<?php echo e($language->code); ?>]" 
                      rows="3"><?php echo e(old('meta_description.' . $language->code, $model->translate($language->code)->meta_description ?? '')); ?></textarea>
            <?php $__errorArgs = ['meta_description.' . $language->code];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="form-group">
            <label for="meta_keywords_<?php echo e($language->code); ?>">Meta Keywords (<?php echo e(strtoupper($language->code)); ?>)</label>
            <input type="text" 
                   class="form-control <?php $__errorArgs = ['meta_keywords.' . $language->code];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                   id="meta_keywords_<?php echo e($language->code); ?>" 
                   name="meta_keywords[<?php echo e($language->code); ?>]" 
                   value="<?php echo e(old('meta_keywords.' . $language->code, $model->translate($language->code)->meta_keywords ?? '')); ?>">
            <?php $__errorArgs = ['meta_keywords.' . $language->code];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php /**PATH D:\afandina\resources\views\includes\translation-fields.blade.php ENDPATH**/ ?>