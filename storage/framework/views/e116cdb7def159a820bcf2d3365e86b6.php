<?php $__env->startSection('title', 'Contacts Settings'); ?>

<?php $__env->startSection('page-title'); ?>
    Contact Settings
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
        <!-- Page Header --><!-- Error Messages -->


                <!-- Contact Update Form -->
                <form action="<?php echo e(route('admin.contacts.update')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>

                    <!-- Basic Information Section -->
                    <div class="card card-info shadow-sm mb-4">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-info-circle mr-2"></i> Basic Information</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <?php $__currentLoopData = [
                                    ['label' => 'Name', 'name' => 'name', 'type' => 'text', 'icon' => 'fas fa-user', 'value' => $contact->name],
                                    ['label' => 'Email', 'name' => 'email', 'type' => 'email', 'icon' => 'fas fa-envelope', 'value' => $contact->email],
                                    ['label' => 'Phone', 'name' => 'phone', 'type' => 'text', 'icon' => 'fas fa-phone', 'value' => $contact->phone],
                                    ['label' => 'Alternative Phone', 'name' => 'alternative_phone', 'type' => 'text', 'icon' => 'fas fa-phone-alt', 'value' => $contact->alternative_phone]
                                ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="<?php echo e($field['name']); ?>"><i class="<?php echo e($field['icon']); ?> mr-1"></i> <?php echo e($field['label']); ?></label>
                                            <input type="<?php echo e($field['type']); ?>" name="<?php echo e($field['name']); ?>" class="form-control" value="<?php echo e($field['value']); ?>" required>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>

                    <!-- Address Information Section -->
                    <div class="card card-primary shadow-sm mb-4">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-map-marker-alt mr-2"></i> Address Information</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <?php $__currentLoopData = [
                                    ['label' => 'Address Line 1', 'name' => 'address_line1', 'type' => 'text', 'icon' => 'fas fa-home', 'value' => $contact->address_line1],
                                    ['label' => 'Address Line 2', 'name' => 'address_line2', 'type' => 'text', 'icon' => 'fas fa-map-pin', 'value' => $contact->address_line2],
                                    ['label' => 'City', 'name' => 'city', 'type' => 'text', 'icon' => 'fas fa-city', 'value' => $contact->city],
                                    ['label' => 'Country', 'name' => 'country', 'type' => 'text', 'icon' => 'fas fa-globe', 'value' => $contact->country],
                                    ['label' => 'State', 'name' => 'state', 'type' => 'text', 'icon' => 'fas fa-flag', 'value' => $contact->state],
                                    ['label' => 'Postal Code', 'name' => 'postal_code', 'type' => 'text', 'icon' => 'fas fa-mail-bulk', 'value' => $contact->postal_code]
                                ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="<?php echo e($field['name']); ?>"><i class="<?php echo e($field['icon']); ?> mr-1"></i> <?php echo e($field['label']); ?></label>
                                            <input type="<?php echo e($field['type']); ?>" name="<?php echo e($field['name']); ?>" class="form-control" value="<?php echo e($field['value']); ?>" required>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>

                    <!-- Social Media Links Section -->
                    <div class="card card-success shadow-sm mb-4">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-share-alt mr-2"></i> Social Media Links</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <?php $__currentLoopData = ['facebook' => 'fab fa-facebook', 'twitter' => 'fab fa-twitter', 'instagram' => 'fab fa-instagram', 'linkedin' => 'fab fa-linkedin', 'youtube' => 'fab fa-youtube', 'whatsapp' => 'fab fa-whatsapp', 'tiktok' => 'fab fa-tiktok', 'snapchat' => 'fab fa-snapchat']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $social => $icon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="<?php echo e($social); ?>"><i class="<?php echo e($icon); ?> mr-1"></i> <?php echo e(ucfirst($social)); ?></label>
                                            <input type="text" name="<?php echo e($social); ?>" class="form-control" value="<?php echo e($contact->$social); ?>">
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>

                    <!-- Other Information Section -->
                    <div class="card card-secondary shadow-sm mb-4">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-info mr-2"></i> Other Information</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <?php $__currentLoopData = [
                                    ['label' => 'Website', 'name' => 'website', 'type' => 'url', 'icon' => 'fas fa-globe', 'value' => $contact->website],
                                    ['label' => 'Google Map URL', 'name' => 'google_map_url', 'type' => 'text', 'icon' => 'fas fa-map-marked-alt', 'value' => $contact->google_map_url],
                                    ['label' => 'Contact Person', 'name' => 'contact_person', 'type' => 'text', 'icon' => 'fas fa-user-tie', 'value' => $contact->contact_person],
                                    ['label' => 'Additional Information', 'name' => 'additional_info', 'type' => 'textarea', 'icon' => 'fas fa-info-circle', 'value' => $contact->additional_info]
                                ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="<?php echo e($field['name']); ?>"><i class="<?php echo e($field['icon']); ?> mr-1"></i> <?php echo e($field['label']); ?></label>
                                            <?php if($field['type'] === 'textarea'): ?>
                                                <textarea name="<?php echo e($field['name']); ?>" class="form-control"><?php echo e($field['value']); ?></textarea>
                                            <?php else: ?>
                                                <input type="<?php echo e($field['type']); ?>" name="<?php echo e($field['name']); ?>" class="form-control" value="<?php echo e($field['value']); ?>">
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>

















                    <!-- Submit Button -->
                    <div class="row">
                        <button type="submit" class="btn btn-primary btn-lg btn-block mb-3"><i class="fas fa-save mr-2"></i> Update Contact Information</button>
                    </div>
                </form><?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin_layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\afandina\resources\views\pages\admin\contacts\edit.blade.php ENDPATH**/ ?>