<?php /** @var  Illuminate\Pagination\LengthAwarePaginator  $rows */ ?>

<?php $__env->startSection('content'); ?>
    <div class="page-header mb-4">
        <h1><?php echo app('translator')->get('Logs'); ?></h1>
    </div>

    <div class="table-responsive">
        <table class="table table-sm table-hover">
            <thead>
                <tr>
                    <?php $__currentLoopData = $headers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $header): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <th scope="col" class="<?php echo e($key == 'date' ? 'text-left' : 'text-center'); ?>">
                        <?php if($key == 'date'): ?>
                            <span class="badge text-bg-info"><?php echo e($header); ?></span>
                        <?php else: ?>
                            <span class="badge badge-level-<?php echo e($key); ?>">
                                <?php echo e(log_styler()->icon($key)); ?> <?php echo e($header); ?>

                            </span>
                        <?php endif; ?>
                    </th>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <th scope="col" class="text-end"><?php echo app('translator')->get('Actions'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $date => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <?php $__currentLoopData = $row; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <td class="<?php echo e($key == 'date' ? 'text-left' : 'text-center'); ?>">
                                <?php if($key == 'date'): ?>
                                    <span class="badge text-bg-primary"><?php echo e($value); ?></span>
                                <?php elseif($value == 0): ?>
                                    <span class="badge empty"><?php echo e($value); ?></span>
                                <?php else: ?>
                                    <a href="<?php echo e(route('log-viewer::logs.filter', [$date, $key])); ?>">
                                        <span class="badge badge-level-<?php echo e($key); ?>"><?php echo e($value); ?></span>
                                    </a>
                                <?php endif; ?>
                            </td>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <td class="text-end">
                            <a href="<?php echo e(route('log-viewer::logs.show', [$date])); ?>" class="btn btn-sm btn-info">
                                <i class="fa fa-fw fa-search"></i>
                            </a>
                            <a href="<?php echo e(route('log-viewer::logs.download', [$date])); ?>" class="btn btn-sm btn-success">
                                <i class="fa fa-fw fa-download"></i>
                            </a>
                            <a href="#delete-log-modal" class="btn btn-sm btn-danger" data-log-date="<?php echo e($date); ?>">
                                <i class="fa fa-fw fa-trash-o"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="11" class="text-center">
                            <span class="badge text-bg-secondary"><?php echo app('translator')->get('The list of logs is empty!'); ?></span>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php echo e($rows->render()); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('modals'); ?>
    
    <div class="modal fade" id="delete-log-modal" tabindex="-1" aria-labelledby="delete-log-modal-label" aria-hidden="true">
        <div class="modal-dialog">
            <form id="delete-log-form" action="<?php echo e(route('log-viewer::logs.delete')); ?>" method="POST">
                <?php echo e(csrf_field()); ?>

                <?php echo e(method_field('DELETE')); ?>

                <input type="hidden" name="date" value="">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="delete-log-modal-label"><?php echo app('translator')->get('Delete log file'); ?></h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p></p>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal"><?php echo app('translator')->get('Cancel'); ?></button>
                        <button type="submit" class="btn btn-sm btn-danger" data-loading-text="<?php echo app('translator')->get('Loading'); ?>&hellip;"><?php echo app('translator')->get('Delete'); ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script>
        ready(() => {
            let deleteLogModal = new bootstrap.Modal('div#delete-log-modal')
            let deleteLogModalElt = deleteLogModal._element
            let deleteLogForm = document.querySelector('form#delete-log-form')
            let submitBtn = new bootstrap.Button(deleteLogForm.querySelector('button[type=submit]'))

            document.querySelectorAll("a[href='#delete-log-modal']").forEach((elt) => {
                elt.addEventListener('click', (event) => {
                    event.preventDefault()

                    let date = event.currentTarget.getAttribute('data-log-date')
                    let message = "<?php echo e(__('Are you sure you want to delete this log file: :date ?')); ?>"

                    deleteLogForm.querySelector('input[name=date]').value = date
                    deleteLogModalElt.querySelector('.modal-body p').innerHTML = message.replace(':date', date)

                    deleteLogModal.show()
                })
            })

            deleteLogForm.addEventListener('submit', (event) => {
                event.preventDefault()
                submitBtn.toggle('loading')

                fetch(event.currentTarget.getAttribute('action'), {
                    method: 'DELETE',
                    headers: {
                        "X-Requested-With": "XMLHttpRequest",
                        'Content-type': 'application/json'
                    },
                    body: JSON.stringify({
                        date: event.currentTarget.querySelector("input[name='date']").value,
                    })
                })
                    .then((resp) => resp.json())
                    .then((resp) => {
                        if (resp.result === 'success') {
                            deleteLogModal.hide()
                            location.reload()
                        }
                        else {
                            alert('AJAX ERROR ! Check the console !')
                            console.error(resp)
                        }
                    })
                    .catch((err) => {
                        alert('AJAX ERROR ! Check the console !')
                        console.error(err)
                    })

                return false
            })

            deleteLogModalElt.addEventListener('hidden.bs.modal', () => {
                deleteLogForm.querySelector('input[name=date]').value = ''
                deleteLogModalElt.querySelector('.modal-body p').innerHTML = ''
            })
        })
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('log-viewer::bootstrap-5._master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\afandina\resources\views\vendor\log-viewer\bootstrap-5\logs.blade.php ENDPATH**/ ?>