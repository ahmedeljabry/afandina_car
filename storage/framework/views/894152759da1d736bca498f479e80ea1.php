<?php $__env->startSection('title', 'View ' . $modelName); ?>

<?php $__env->startSection('page-title'); ?>
    View <?php echo e($modelName); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?><!-- Back, Edit, and Delete Buttons -->
                <div class="mb-3 d-flex justify-content-between">
                    <a href="<?php echo e(route('admin.' . $modelName . '.index')); ?>" class="btn btn-outline-secondary" data-toggle="tooltip" data-placement="top" title="Back to list">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                    <div>
                        <a href="<?php echo e(route('admin.' . $modelName . '.edit', $item->id)); ?>" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Edit item">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="<?php echo e(route('admin.' . $modelName . '.destroy', $item->id)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this item?');">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Delete item">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Main Card -->
                <div class="card card-primary card-outline shadow-lg">
                    <div class="card-header">
                        <h3 class="card-title">Details for <?php echo e($modelName); ?></h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <img src="<?php echo e(asset('storage/' . $item->logo_path)); ?>" alt="Logo" class="img-fluid rounded shadow-sm" />
                            </div>
                            <div class="col-md-8">
                                <h4><?php echo e($item->translations->first()->name ?? 'N/A'); ?></h4>
                                <span class="badge badge-info"><?php echo e($item->translations->first()->slug ?? 'N/A'); ?></span>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Tabs for Different Languages -->
                        <ul class="nav nav-tabs" id="language-tabs" role="tablist">
                            <?php $__currentLoopData = $activeLanguages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="nav-item">
                                    <a class="nav-link <?php if($loop->first): ?> active <?php endif; ?>" id="tab-<?php echo e($lang->code); ?>" data-toggle="tab" href="#content-<?php echo e($lang->code); ?>" role="tab" aria-controls="content-<?php echo e($lang->code); ?>" aria-selected="true"><?php echo e($lang->name); ?></a>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>

                        <div class="tab-content mt-3" id="language-tabs-content">
                            <?php $__currentLoopData = $activeLanguages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="tab-pane fade <?php if($loop->first): ?> show active <?php endif; ?>" id="content-<?php echo e($lang->code); ?>" role="tabpanel" aria-labelledby="tab-<?php echo e($lang->code); ?>">
                                    <div class="mb-3">
                                        <h5>Meta Data</h5>
                                        <table class="table table-bordered table-striped">
                                            <tbody>
                                            <tr>
                                                <th>Meta Title</th>
                                                <td><?php echo e($item->translations->where('locale', $lang->code)->first()->meta_title ?? 'N/A'); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Meta Description</th>
                                                <td><?php echo e($item->translations->where('locale', $lang->code)->first()->meta_description ?? 'N/A'); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Meta Keywords</th>
                                                <td>
                                                    <?php
                                                        $keywords = json_decode($item->translations->where('locale', $lang->code)->first()->meta_keywords, true);
                                                    ?>
                                                    <?php $__currentLoopData = $keywords; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $keyword): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <span class="badge badge-pill badge-primary"><?php echo e($keyword['value']); ?></span>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div>
                                        <h5>SEO Questions/Answers</h5>
                                        <?php $__empty_1 = true; $__currentLoopData = $item->seoQuestions->where('locale', $lang->code); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $seoQuestion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <?php if($seoQuestion->where('locale', $lang->code)->first()): ?>
                                                <div class="card mb-3 shadow-sm">
                                                    <div class="card-header d-flex justify-content-between align-items-center">
                                                        <h6 class="m-0 font-weight-bold">Question <?php echo e($seoQuestion->id); ?></h6>
                                                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse-<?php echo e($seoQuestion->id); ?>" aria-expanded="false" aria-controls="collapse-<?php echo e($seoQuestion->id); ?>">
                                                            <i class="fas fa-chevron-down"></i>
                                                        </button>
                                                    </div>
                                                    <div id="collapse-<?php echo e($seoQuestion->id); ?>" class="collapse">
                                                        <div class="card-body">
                                                            <p><strong>Question:</strong> <?php echo e($seoQuestion->question_text ?? 'N/A'); ?></p>
                                                            <p><strong>Answer:</strong> <?php echo e($seoQuestion->answer_text ?? 'N/A'); ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                            <div class="alert alert-default-light">
                                                No SEO questions available for this language.
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <?php $__env->startPush('scripts'); ?>
        <!-- Initialize Tooltips -->
        <script>
            $(function () {
                $('[data-toggle="tooltip"]').tooltip()
            })
        </script>
    <?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin_layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\afandina\resources\views\pages\admin\abouts\show.blade.php ENDPATH**/ ?>