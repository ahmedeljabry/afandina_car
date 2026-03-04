<div class="card card-primary card-outline shadow-sm mb-4" id="<?php echo e($section['anchor']); ?>">
    <div class="card-header bg-white">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between">
            <div>
                <h5 class="mb-1"><?php echo e($section['title']); ?></h5>
                <p class="text-muted mb-0"><?php echo e($section['description']); ?></p>
            </div>
            <span class="badge badge-light border text-dark"><?php echo e(count($homeLocales)); ?> Languages</span>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <?php $__currentLoopData = $homeLocales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $locale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $localeCode = $locale['code'];
                    $translation = $translationsByLocale[$localeCode] ?? null;
                ?>

                <div class="col-lg-6 d-flex">
                    <div class="border rounded p-3 mb-3 flex-fill">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h6 class="mb-0"><?php echo e($locale['name']); ?></h6>
                            <span class="badge badge-secondary"><?php echo e(strtoupper($localeCode)); ?></span>
                        </div>

                        <div class="row">
                            <?php $__currentLoopData = $section['fields']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $fieldName = $field['name'];
                                    $fieldType = $field['type'] ?? 'text';
                                    $fieldRows = $field['rows'] ?? 3;
                                    $fieldWidth = $field['width'] ?? 'col-12';
                                    $fieldValue = old($fieldName . '.' . $localeCode, data_get($translation, $fieldName));

                                    if (!filled($fieldValue)) {
                                        $fieldValue = $prefillValues[$fieldName][$localeCode] ?? '';
                                    }
                                ?>

                                <div class="<?php echo e($fieldWidth); ?>">
                                    <div class="form-group">
                                        <label for="<?php echo e($fieldName); ?>_<?php echo e($localeCode); ?>" class="font-weight-bold">
                                            <?php echo e($field['label']); ?>

                                        </label>

                                        <?php if($fieldType === 'textarea'): ?>
                                            <textarea
                                                name="<?php echo e($fieldName); ?>[<?php echo e($localeCode); ?>]"
                                                id="<?php echo e($fieldName); ?>_<?php echo e($localeCode); ?>"
                                                class="form-control <?php $__errorArgs = [$fieldName . '.' . $localeCode];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                rows="<?php echo e($fieldRows); ?>"
                                            ><?php echo e($fieldValue); ?></textarea>
                                        <?php else: ?>
                                            <input
                                                type="text"
                                                name="<?php echo e($fieldName); ?>[<?php echo e($localeCode); ?>]"
                                                id="<?php echo e($fieldName); ?>_<?php echo e($localeCode); ?>"
                                                class="form-control <?php $__errorArgs = [$fieldName . '.' . $localeCode];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                value="<?php echo e($fieldValue); ?>"
                                            >
                                        <?php endif; ?>

                                        <?php $__errorArgs = [$fieldName . '.' . $localeCode];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <span class="invalid-feedback d-block"><?php echo e($message); ?></span>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</div>
<?php /**PATH D:\afandina\resources\views\pages\admin\homes\partials\section-panel.blade.php ENDPATH**/ ?>