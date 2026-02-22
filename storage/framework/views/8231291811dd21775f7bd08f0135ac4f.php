<?php $__env->startSection('title', 'Contact Messages'); ?>

<?php $__env->startSection('page-title'); ?>
    Contact Messages
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">
                <i class="fas fa-envelope-open-text mr-2"></i>Contact Messages
            </h3>
            <span class="badge badge-primary"><?php echo e($messages->total()); ?></span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Message</th>
                            <th>Sent At</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($message->id); ?></td>
                                <td><?php echo e($message->full_name); ?></td>
                                <td><?php echo e($message->email ?: '-'); ?></td>
                                <td><?php echo e($message->phone ?: '-'); ?></td>
                                <td style="min-width: 260px;"><?php echo e(\Illuminate\Support\Str::limit($message->message, 160)); ?></td>
                                <td><?php echo e($message->created_at?->format('Y-m-d H:i')); ?></td>
                                <td class="text-right">
                                    <form action="<?php echo e(route('admin.contact-messages.destroy', $message->id)); ?>"
                                        method="POST"
                                        onsubmit="return confirm('Delete this message?');"
                                        class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">No contact messages found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php if($messages->hasPages()): ?>
            <div class="card-footer">
                <?php echo e($messages->links('pagination::bootstrap-4')); ?>

            </div>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin_layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\afandina\resources\views\pages\admin\contact_messages\index.blade.php ENDPATH**/ ?>