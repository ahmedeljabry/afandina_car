<?php $__env->startSection('title', 'Edit ' . $modelName); ?>

<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Edit :entity', ['entity' => $modelName])); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-actions'); ?>
    <a href="<?php echo e(route('admin.' . $modelName . '.index')); ?>" class="btn btn-outline-secondary d-inline-flex align-items-center mb-2">
        <i class="fas fa-arrow-left me-1"></i><?php echo e(__('Back to List')); ?>

    </a>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('includes.admin.blog_editor_theme', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->startSection('content'); ?>
    <?php
        $placeholderImage = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='800' height='560' viewBox='0 0 800 560'%3E%3Cdefs%3E%3ClinearGradient id='g' x1='0' y1='0' x2='1' y2='1'%3E%3Cstop stop-color='%23dbeafe'/%3E%3Cstop offset='1' stop-color='%23e2e8f0'/%3E%3C/linearGradient%3E%3C/defs%3E%3Crect width='100%25' height='100%25' fill='url(%23g)'/%3E%3Cpath d='M0 430L170 280l120 90 165-180 120 115 225-175v430H0z' fill='%23cbd5e1'/%3E%3Ctext x='50%25' y='50%25' fill='%23475569' font-size='28' text-anchor='middle' dy='.3em'%3EFeature Image Preview%3C/text%3E%3C/svg%3E";
        $rawImagePath = ltrim((string) ($item->image_path ?? ''), '/');
        $imagePreview = $rawImagePath !== ''
            ? (str_starts_with($rawImagePath, 'storage/') ? asset($rawImagePath) : asset('storage/' . $rawImagePath))
            : $placeholderImage;
        $selectedCarIds = collect(old('cars', $item->cars->pluck('id')->all()))->map(fn ($value) => (string) $value)->all();
        $translationsByLocale = $item->translations->keyBy('locale');
        $seoQuestionsByLocale = $item->seoQuestions->groupBy('locale');
    ?>

    <div class="blog-editor-layout">
        <div>
            <div class="blog-editor-hero mb-4">
                <h2><?php echo e(__('Shape the Story')); ?></h2>
                <p><?php echo e(__('Refine the article, improve language coverage, and tighten SEO from one cleaner editing workspace.')); ?></p>
                <div class="blog-editor-hero-metrics">
                    <span class="blog-editor-chip"><i class="fas fa-language"></i><?php echo e(__(':count Locale(s)', ['count' => $activeLanguages->count()])); ?></span>
                    <span class="blog-editor-chip"><i class="fas fa-image"></i><?php echo e(__('Cover managed')); ?></span>
                    <span class="blog-editor-chip"><i class="fas fa-search"></i><?php echo e(__('SEO maintained')); ?></span>
                    <span class="blog-editor-chip"><i class="fas fa-pen"></i><?php echo e(__('Live revisions')); ?></span>
                </div>
            </div>

            <?php if($errors->any()): ?>
                <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4">
                    <strong><?php echo e(__('Please review the highlighted fields.')); ?></strong>
                    <div class="small mt-1"><?php echo e($errors->first()); ?></div>
                </div>
            <?php endif; ?>

            <form action="<?php echo e(route('admin.' . $modelName . '.update', $item->id)); ?>" method="POST" enctype="multipart/form-data" class="blog-editor">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <div class="card blog-editor-main-card">
                    <div class="blog-editor-tabbar nav nav-pills" role="tablist">
                        <button class="nav-link active" type="button" data-bs-toggle="pill" data-bs-target="#blog-edit-general" role="tab">
                            <i class="fas fa-pen-nib"></i><?php echo e(__('General')); ?>

                        </button>
                        <button class="nav-link" type="button" data-bs-toggle="pill" data-bs-target="#blog-edit-translated" role="tab">
                            <i class="fas fa-language"></i><?php echo e(__('Translated Content')); ?>

                        </button>
                        <button class="nav-link" type="button" data-bs-toggle="pill" data-bs-target="#blog-edit-seo" role="tab">
                            <i class="fas fa-chart-line"></i><?php echo e(__('SEO & Discovery')); ?>

                        </button>
                    </div>

                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane fade show active blog-editor-pane" id="blog-edit-general" role="tabpanel">
                                <div class="row g-4">
                                    <div class="col-lg-5">
                                        <div class="blog-editor-panel h-100">
                                            <span class="blog-editor-kicker"><?php echo e(__('Cover Story')); ?></span>
                                            <h5 class="blog-editor-panel-title"><?php echo e(__('Feature Image')); ?></h5>
                                            <div class="blog-editor-preview-box mb-3">
                                                <img src="<?php echo e($imagePreview); ?>" alt="<?php echo e(__('Blog preview')); ?>" id="blogImagePreview">
                                            </div>
                                            <label for="image_path" class="blog-editor-label"><?php echo e(__('Replace Image')); ?></label>
                                            <input type="file" name="image_path" id="image_path" class="form-control <?php $__errorArgs = ['image_path'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" accept=".jpg,.jpeg,.png,.svg,.webp">
                                            <?php $__errorArgs = ['image_path'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                            <p class="blog-editor-hint mt-3 mb-0"><?php echo e(__('Swap the cover only when it improves readability in cards, banners, and social previews.')); ?></p>
                                        </div>
                                    </div>

                                    <div class="col-lg-7">
                                        <div class="blog-editor-panel">
                                            <span class="blog-editor-kicker"><?php echo e(__('Connections')); ?></span>
                                            <h5 class="blog-editor-panel-title"><?php echo e(__('Post Settings')); ?></h5>

                                            <div class="mb-4">
                                                <label for="cars" class="blog-editor-label"><?php echo e(__('Cars Related to This Post')); ?></label>
                                                <select class="form-control car-select" name="cars[]" id="cars" multiple="multiple">
                                                    <?php $__currentLoopData = $cars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $car): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <?php
                                                            $carTranslation = $car->translations->first();
                                                            $carImage = $car->default_image_path
                                                                ? asset('storage/' . ltrim($car->default_image_path, '/'))
                                                                : asset('admin/assets/img/car/car-01.jpg');
                                                        ?>
                                                        <option value="<?php echo e($car->id); ?>" data-image="<?php echo e($carImage); ?>" <?php if(in_array((string) $car->id, $selectedCarIds, true)): echo 'selected'; endif; ?>>
                                                            <?php echo e($carTranslation->name ?? __('Car #:id', ['id' => $car->id])); ?>

                                                        </option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                                <p class="blog-editor-hint mt-2 mb-0"><?php echo e(__('Keep related vehicles aligned with the story so editorial content still drives discovery.')); ?></p>
                                            </div>

                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <div class="blog-editor-switch">
                                                        <div>
                                                            <strong><?php echo e(__('Publish State')); ?></strong>
                                                            <span><?php echo e(__('Control whether this article is active in listings and the storefront.')); ?></span>
                                                        </div>
                                                        <div class="form-check form-switch m-0">
                                                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" <?php if((bool) old('is_active', $item->is_active)): echo 'checked'; endif; ?>>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="blog-editor-switch">
                                                        <div>
                                                            <strong><?php echo e(__('Homepage Spotlight')); ?></strong>
                                                            <span><?php echo e(__('Decide whether this article should stay featured on the homepage.')); ?></span>
                                                        </div>
                                                        <div class="form-check form-switch m-0">
                                                            <input class="form-check-input" type="checkbox" name="show_in_home" id="show_in_home" value="1" <?php if((bool) old('show_in_home', $item->show_in_home)): echo 'checked'; endif; ?>>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade blog-editor-pane" id="blog-edit-translated" role="tabpanel">
                                <div class="nav nav-pills blog-editor-langbar" role="tablist">
                                    <?php $__currentLoopData = $activeLanguages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <button class="nav-link <?php if($loop->first): ?> active <?php endif; ?>" type="button" data-bs-toggle="pill" data-bs-target="#blog-edit-lang-<?php echo e($lang->code); ?>" role="tab">
                                            <?php echo e($lang->name); ?>

                                        </button>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>

                                <div class="tab-content">
                                    <?php $__currentLoopData = $activeLanguages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $translation = $translationsByLocale->get($lang->code);
                                        ?>
                                        <div class="tab-pane fade <?php if($loop->first): ?> show active <?php endif; ?>" id="blog-edit-lang-<?php echo e($lang->code); ?>" role="tabpanel">
                                            <div class="blog-editor-langpane">
                                                <div class="row g-3">
                                                    <div class="col-12">
                                                        <label for="title_<?php echo e($lang->code); ?>" class="blog-editor-label"><?php echo e(__('Title')); ?> (<?php echo e($lang->name); ?>)</label>
                                                        <input type="text" name="title[<?php echo e($lang->code); ?>]" id="title_<?php echo e($lang->code); ?>" class="form-control <?php $__errorArgs = ['title.' . $lang->code];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('title.' . $lang->code, $translation->title ?? '')); ?>">
                                                        <?php $__errorArgs = ['title.' . $lang->code];
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
                                                    <div class="col-12">
                                                        <label for="description_<?php echo e($lang->code); ?>" class="blog-editor-label"><?php echo e(__('Description')); ?> (<?php echo e($lang->name); ?>)</label>
                                                        <textarea name="description[<?php echo e($lang->code); ?>]" id="description_<?php echo e($lang->code); ?>" class="form-control <?php $__errorArgs = ['description.' . $lang->code];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" rows="4"><?php echo e(old('description.' . $lang->code, $translation->description ?? '')); ?></textarea>
                                                        <?php $__errorArgs = ['description.' . $lang->code];
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
                                                    <div class="col-12">
                                                        <label for="content_<?php echo e($lang->code); ?>" class="blog-editor-label"><?php echo e(__('Content')); ?> (<?php echo e($lang->name); ?>)</label>
                                                        <textarea name="content[<?php echo e($lang->code); ?>]" id="content_<?php echo e($lang->code); ?>" class="form-control tinymce <?php $__errorArgs = ['content.' . $lang->code];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" rows="10"><?php echo e(old('content.' . $lang->code, $translation->content ?? '')); ?></textarea>
                                                        <?php $__errorArgs = ['content.' . $lang->code];
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
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>

                            <div class="tab-pane fade blog-editor-pane" id="blog-edit-seo" role="tabpanel">
                                <div class="nav nav-pills blog-editor-langbar" role="tablist">
                                    <?php $__currentLoopData = $activeLanguages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <button class="nav-link <?php if($loop->first): ?> active <?php endif; ?>" type="button" data-bs-toggle="pill" data-bs-target="#blog-edit-seo-lang-<?php echo e($lang->code); ?>" role="tab">
                                            <?php echo e($lang->name); ?>

                                        </button>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>

                                <div class="tab-content">
                                    <?php $__currentLoopData = $activeLanguages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $translation = $translationsByLocale->get($lang->code);
                                            $metaKeywords = json_decode($translation->meta_keywords ?? '[]', true);
                                            $metaKeywords = is_array($metaKeywords) ? $metaKeywords : [];
                                            $keywordString = collect($metaKeywords)
                                                ->map(function ($keyword) {
                                                    if (is_array($keyword)) {
                                                        return $keyword['value'] ?? null;
                                                    }

                                                    return is_string($keyword) ? $keyword : null;
                                                })
                                                ->filter()
                                                ->implode(',');
                                            $seoQuestionRows = old('seo_questions.' . $lang->code);
                                            if (!is_array($seoQuestionRows)) {
                                                $seoQuestionRows = $seoQuestionsByLocale->get($lang->code, collect())
                                                    ->map(fn ($question) => ['question' => $question->question_text, 'answer' => $question->answer_text])
                                                    ->values()
                                                    ->all();
                                            }
                                            if ($seoQuestionRows === []) {
                                                $seoQuestionRows = [['question' => '', 'answer' => '']];
                                            }
                                        ?>
                                        <div class="tab-pane fade <?php if($loop->first): ?> show active <?php endif; ?>" id="blog-edit-seo-lang-<?php echo e($lang->code); ?>" role="tabpanel">
                                            <div class="blog-editor-langpane">
                                                <div class="row g-3">
                                                    <div class="col-12">
                                                        <label for="meta_title_<?php echo e($lang->code); ?>" class="blog-editor-label"><?php echo e(__('Meta Title')); ?> (<?php echo e($lang->name); ?>)</label>
                                                        <input type="text" name="meta_title[<?php echo e($lang->code); ?>]" id="meta_title_<?php echo e($lang->code); ?>" class="form-control" value="<?php echo e(old('meta_title.' . $lang->code, $translation->meta_title ?? '')); ?>">
                                                    </div>
                                                    <div class="col-12">
                                                        <label for="meta_description_<?php echo e($lang->code); ?>" class="blog-editor-label"><?php echo e(__('Meta Description')); ?> (<?php echo e($lang->name); ?>)</label>
                                                        <textarea name="meta_description[<?php echo e($lang->code); ?>]" id="meta_description_<?php echo e($lang->code); ?>" class="form-control" rows="4"><?php echo e(old('meta_description.' . $lang->code, $translation->meta_description ?? '')); ?></textarea>
                                                    </div>
                                                    <div class="col-12">
                                                        <label for="meta_keywords_<?php echo e($lang->code); ?>" class="blog-editor-label"><?php echo e(__('Meta Keywords')); ?> (<?php echo e($lang->name); ?>)</label>
                                                        <input type="text" name="meta_keywords[<?php echo e($lang->code); ?>]" id="meta_keywords_<?php echo e($lang->code); ?>" class="form-control blog-editor-meta-keywords" value="<?php echo e(old('meta_keywords.' . $lang->code, $keywordString)); ?>" placeholder="<?php echo e(__('Enter meta keywords')); ?>">
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="blog-editor-seo-box">
                                                            <h6><?php echo e(__('Search Indexing')); ?></h6>
                                                            <p class="blog-editor-hint mb-3"><?php echo e(__('Allow search engines to index this locale.')); ?></p>
                                                            <div class="form-check form-switch m-0">
                                                                <input class="form-check-input" type="checkbox" name="robots_index[<?php echo e($lang->code); ?>]" id="robots_index_<?php echo e($lang->code); ?>" value="index" <?php if(old('robots_index.' . $lang->code, $translation->robots_index ?? '') === 'index'): echo 'checked'; endif; ?>>
                                                                <label class="form-check-label ms-2" for="robots_index_<?php echo e($lang->code); ?>"><?php echo e(__('Index')); ?></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="blog-editor-seo-box">
                                                            <h6><?php echo e(__('Link Following')); ?></h6>
                                                            <p class="blog-editor-hint mb-3"><?php echo e(__('Allow crawlers to follow outgoing links.')); ?></p>
                                                            <div class="form-check form-switch m-0">
                                                                <input class="form-check-input" type="checkbox" name="robots_follow[<?php echo e($lang->code); ?>]" id="robots_follow_<?php echo e($lang->code); ?>" value="follow" <?php if(old('robots_follow.' . $lang->code, $translation->robots_follow ?? '') === 'follow'): echo 'checked'; endif; ?>>
                                                                <label class="form-check-label ms-2" for="robots_follow_<?php echo e($lang->code); ?>"><?php echo e(__('Follow')); ?></label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <label class="blog-editor-label d-block"><?php echo e(__('SEO Questions & Answers')); ?> (<?php echo e($lang->name); ?>)</label>
                                                        <div class="blog-editor-question-list" id="seo-questions-<?php echo e($lang->code); ?>" data-next-index="<?php echo e(count($seoQuestionRows)); ?>">
                                                            <?php $__currentLoopData = $seoQuestionRows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $seoQuestion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <div class="blog-editor-question seo-question-group">
                                                                    <div class="row g-3">
                                                                        <div class="col-12">
                                                                            <input type="text" name="seo_questions[<?php echo e($lang->code); ?>][<?php echo e($index); ?>][question]" class="form-control" value="<?php echo e($seoQuestion['question'] ?? ''); ?>" placeholder="<?php echo e(__('Enter question')); ?>">
                                                                        </div>
                                                                        <div class="col-12">
                                                                            <textarea name="seo_questions[<?php echo e($lang->code); ?>][<?php echo e($index); ?>][answer]" class="form-control" rows="3" placeholder="<?php echo e(__('Enter answer')); ?>"><?php echo e($seoQuestion['answer'] ?? ''); ?></textarea>
                                                                        </div>
                                                                    </div>
                                                                    <div class="blog-editor-question-actions">
                                                                        <button type="button" class="btn btn-outline-danger btn-sm remove-question"><i class="fas fa-trash"></i><?php echo e(__('Remove')); ?></button>
                                                                    </div>
                                                                </div>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </div>
                                                        <button type="button" class="btn btn-outline-primary blog-editor-ghost-btn mt-3 add-question" data-lang="<?php echo e($lang->code); ?>">
                                                            <i class="fas fa-plus"></i><?php echo e(__('Add Question')); ?>

                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        </div>

                        <div class="blog-editor-submitbar">
                            <p><?php echo e(__('Review the updated content across all tabs before saving the changes.')); ?></p>
                            <div class="d-flex flex-wrap gap-2">
                                <a href="<?php echo e(route('admin.' . $modelName . '.index')); ?>" class="btn btn-outline-secondary blog-editor-ghost-btn">
                                    <i class="fas fa-arrow-left"></i><?php echo e(__('Back to List')); ?>

                                </a>
                                <button type="submit" class="btn btn-primary blog-editor-ghost-btn">
                                    <i class="fas fa-save"></i><?php echo e(__('Update')); ?>

                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="card blog-editor-sidecard">
            <div class="card-body">
                <span class="blog-editor-kicker"><?php echo e(__('Editing Focus')); ?></span>
                <h5 class="blog-editor-panel-title"><?php echo e(__('Publishing Checklist')); ?></h5>
                <div class="blog-editor-preview-box mb-3">
                    <img src="<?php echo e($imagePreview); ?>" alt="<?php echo e(__('Sidebar preview')); ?>" id="blogImagePreviewMirror">
                </div>
                <div class="blog-editor-checklist">
                    <div class="blog-editor-checklist-item">
                        <i class="fas fa-image"></i>
                        <div>
                            <strong><?php echo e(__('Cover Control')); ?></strong>
                            <span><?php echo e(__('Refresh the image only when the article needs a stronger visual direction.')); ?></span>
                        </div>
                    </div>
                    <div class="blog-editor-checklist-item">
                        <i class="fas fa-language"></i>
                        <div>
                            <strong><?php echo e(__('Locale Review')); ?></strong>
                            <span><?php echo e(__('Keep title, description, and content aligned across every active language.')); ?></span>
                        </div>
                    </div>
                    <div class="blog-editor-checklist-item">
                        <i class="fas fa-search"></i>
                        <div>
                            <strong><?php echo e(__('SEO Review')); ?></strong>
                            <span><?php echo e(__('Maintain keywords, robots rules, and Q&A content so search coverage stays stable.')); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const imageInput = document.getElementById('image_path');
            const previews = [document.getElementById('blogImagePreview'), document.getElementById('blogImagePreviewMirror')];

            if (imageInput) {
                imageInput.addEventListener('change', function (event) {
                    const file = event.target.files && event.target.files[0];
                    if (!file) {
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = function (loadEvent) {
                        previews.forEach(function (preview) {
                            if (preview) {
                                preview.src = loadEvent.target.result;
                            }
                        });
                    };
                    reader.readAsDataURL(file);
                });
            }

            document.querySelectorAll('.add-question').forEach(function (button) {
                button.addEventListener('click', function () {
                    const lang = this.dataset.lang;
                    const container = document.getElementById('seo-questions-' + lang);
                    if (!container) {
                        return;
                    }

                    const count = Number(container.dataset.nextIndex || container.querySelectorAll('.seo-question-group').length);
                    container.dataset.nextIndex = String(count + 1);
                    const group = document.createElement('div');
                    group.className = 'blog-editor-question seo-question-group';
                    group.innerHTML = '<div class="row g-3"><div class="col-12"><input type="text" name="seo_questions[' + lang + '][' + count + '][question]" class="form-control" placeholder="<?php echo e(__('Enter question')); ?>"></div><div class="col-12"><textarea name="seo_questions[' + lang + '][' + count + '][answer]" class="form-control" rows="3" placeholder="<?php echo e(__('Enter answer')); ?>"></textarea></div></div><div class="blog-editor-question-actions"><button type="button" class="btn btn-outline-danger btn-sm remove-question"><i class="fas fa-trash"></i><?php echo e(__('Remove')); ?></button></div>';
                    container.appendChild(group);
                });
            });

            document.addEventListener('click', function (event) {
                const removeButton = event.target.closest('.remove-question');
                if (!removeButton) {
                    return;
                }

                const group = removeButton.closest('.seo-question-group');
                if (group) {
                    group.remove();
                }
            });

            if (window.jQuery && typeof window.jQuery.fn.select2 !== 'undefined') {
                const $ = window.jQuery;

                function formatCar(option) {
                    if (!option.id) {
                        return option.text;
                    }

                    const image = $(option.element).data('image');
                    return $('<span class="d-inline-flex align-items-center"><img src="' + image + '" alt="" style="width:44px;height:32px;object-fit:cover;border-radius:8px;margin-right:8px;"><span>' + $(option.element).text() + '</span></span>');
                }

                $('.car-select').select2({
                    theme: 'bootstrap4',
                    width: '100%',
                    placeholder: <?php echo json_encode(__('Select cars'), 15, 512) ?>,
                    allowClear: true,
                    templateResult: formatCar,
                    templateSelection: formatCar
                });
            }

            if (typeof Tagify !== 'undefined') {
                document.querySelectorAll('.blog-editor-meta-keywords').forEach(function (input) {
                    new Tagify(input, {
                        placeholder: <?php echo json_encode(__('Enter meta keywords'), 15, 512) ?>
                    });
                });
            }
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin_layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\afandina\resources\views\pages\admin\blogs\edit.blade.php ENDPATH**/ ?>