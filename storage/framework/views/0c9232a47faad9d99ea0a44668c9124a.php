<?php
/**
 * @var  Arcanedev\LogViewer\Entities\Log            $log
 * @var  Illuminate\Pagination\LengthAwarePaginator  $entries
 * @var  string|null                                 $query
 */
?>



<?php $__env->startSection('content'); ?>
    <div class="page-header mb-4">
        <h1><?php echo app('translator')->get('Log'); ?> [<?php echo e($log->date); ?>]</h1>
    </div>

    <div class="row">
        <div class="col-lg-2">
            
            <div class="card mb-4">
                <div class="card-header"><i class="fa fa-fw fa-flag"></i> <?php echo app('translator')->get('Levels'); ?></div>
                <div class="list-group list-group-flush log-menu">
                    <?php $__currentLoopData = $log->menu(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $levelKey => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($item['count'] === 0): ?>
                            <a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center disabled">
                                <span class="level-name"><?php echo $item['icon']; ?> <?php echo e($item['name']); ?></span>
                                <span class="badge empty"><?php echo e($item['count']); ?></span>
                            </a>
                        <?php else: ?>
                            <a href="<?php echo e($item['url']); ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center level-<?php echo e($levelKey); ?><?php echo e($level === $levelKey ? ' active' : ''); ?>">
                                <span class="level-name"><?php echo $item['icon']; ?> <?php echo e($item['name']); ?></span>
                                <span class="badge badge-level-<?php echo e($levelKey); ?>"><?php echo e($item['count']); ?></span>
                            </a>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
        <div class="col-lg-10">
            
            <div class="card mb-4">
                <div class="card-header">
                    <?php echo app('translator')->get('Log info'); ?> :
                    <div class="group-btns pull-right">
                        <a href="<?php echo e(route('log-viewer::logs.download', [$log->date])); ?>" class="btn btn-sm btn-success">
                            <i class="fa fa-download"></i> <?php echo app('translator')->get('Download'); ?>
                        </a>
                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#delete-log-modal">
                            <i class="fa fa-trash-o"></i> <?php echo app('translator')->get('Delete'); ?>
                        </button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-condensed mb-0">
                        <tbody>
                            <tr>
                                <td><?php echo app('translator')->get('File path'); ?> :</td>
                                <td colspan="7"><?php echo e($log->getPath()); ?></td>
                            </tr>
                            <tr>
                                <td><?php echo app('translator')->get('Log entries'); ?> :</td>
                                <td>
                                    <span class="badge text-bg-primary"><?php echo e($entries->total()); ?></span>
                                </td>
                                <td><?php echo app('translator')->get('Size'); ?> :</td>
                                <td>
                                    <span class="badge text-bg-primary"><?php echo e($log->size()); ?></span>
                                </td>
                                <td><?php echo app('translator')->get('Created at'); ?> :</td>
                                <td>
                                    <span class="badge text-bg-primary"><?php echo e($log->createdAt()); ?></span>
                                </td>
                                <td><?php echo app('translator')->get('Updated at'); ?> :</td>
                                <td>
                                    <span class="badge text-bg-primary"><?php echo e($log->updatedAt()); ?></span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    
                    <form action="<?php echo e(route('log-viewer::logs.search', [$log->date, $level])); ?>" method="GET">
                        <div class="form-group">
                            <div class="input-group">
                                <input type="search" id="query" name="query" class="form-control" value="<?php echo e($query); ?>" placeholder="<?php echo app('translator')->get('Type here to search'); ?>">
                                <?php if (! (is_null($query))): ?>
                                    <a href="<?php echo e(route('log-viewer::logs.show', [$log->date])); ?>" class="btn btn-light">
                                        (<?php echo app('translator')->get(':count results', ['count' => $entries->count()]); ?>) <i class="fa fa-fw fa-times"></i>
                                    </a>
                                <?php endif; ?>
                                <button id="search-btn" class="btn btn-primary">
                                    <span class="fa fa-fw fa-search"></span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            
            <div class="card mb-4">
                <?php if($entries->hasPages()): ?>
                    <div class="card-header">
                        <span class="badge text-bg-info float-right">
                            <?php echo e(__('Page :current of :last', ['current' => $entries->currentPage(), 'last' => $entries->lastPage()])); ?>

                        </span>
                    </div>
                <?php endif; ?>

                <div class="table-responsive">
                    <table id="entries" class="table mb-0">
                        <thead>
                            <tr>
                                <th><?php echo app('translator')->get('ENV'); ?></th>
                                <th style="width: 120px;"><?php echo app('translator')->get('Level'); ?></th>
                                <th style="width: 65px;"><?php echo app('translator')->get('Time'); ?></th>
                                <th><?php echo app('translator')->get('Header'); ?></th>
                                <th class="text-end"><?php echo app('translator')->get('Actions'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $entries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $entry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <?php /** @var  Arcanedev\LogViewer\Entities\LogEntry  $entry */ ?>
                                <tr>
                                    <td>
                                        <span class="badge badge-env"><?php echo e($entry->env); ?></span>
                                    </td>
                                    <td>
                                        <span class="badge badge-level-<?php echo e($entry->level); ?>">
                                            <?php echo $entry->level(); ?>

                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge text-bg-secondary">
                                            <?php echo e($entry->datetime->format('H:i:s')); ?>

                                        </span>
                                    </td>
                                    <td>
                                        <?php echo e($entry->header); ?>

                                    </td>
                                    <td class="text-end">
                                        <?php if($entry->hasStack()): ?>
                                        <a class="btn btn-sm btn-light" role="button" data-bs-toggle="collapse"
                                           href="#log-stack-<?php echo e($key); ?>" aria-expanded="false" aria-controls="log-stack-<?php echo e($key); ?>">
                                            <i class="fa fa-toggle-on"></i> <?php echo app('translator')->get('Stack'); ?>
                                        </a>
                                        <?php endif; ?>

                                        <?php if($entry->hasContext()): ?>
                                        <a class="btn btn-sm btn-light" role="button" data-bs-toggle="collapse"
                                           href="#log-context-<?php echo e($key); ?>" aria-expanded="false" aria-controls="log-context-<?php echo e($key); ?>">
                                            <i class="fa fa-toggle-on"></i> <?php echo app('translator')->get('Context'); ?>
                                        </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php if($entry->hasStack() || $entry->hasContext()): ?>
                                    <tr>
                                        <td colspan="5" class="stack py-0">
                                            <?php if($entry->hasStack()): ?>
                                            <div class="stack-content collapse" id="log-stack-<?php echo e($key); ?>">
                                                <?php echo $entry->stack(); ?>

                                            </div>
                                            <?php endif; ?>

                                            <?php if($entry->hasContext()): ?>
                                            <div class="stack-content collapse" id="log-context-<?php echo e($key); ?>">
                                                <pre><?php echo e($entry->context()); ?></pre>
                                            </div>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="5" class="text-center">
                                        <span class="badge text-bg-secondary"><?php echo app('translator')->get('The list of logs is empty!'); ?></span>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <?php echo $entries->appends(compact('query'))->render(); ?>

        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('modals'); ?>
    
    <div class="modal fade" id="delete-log-modal" tabindex="-1" aria-labelledby="delete-log-modal-label" aria-hidden="true">
        <div class="modal-dialog">
            <form id="delete-log-form" action="<?php echo e(route('log-viewer::logs.delete')); ?>" method="POST">
                <?php echo e(csrf_field()); ?>

                <?php echo e(method_field('DELETE')); ?>

                <input type="hidden" name="date" value="<?php echo e($log->date); ?>">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fs-5" id="delete-log-modal-label"><?php echo app('translator')->get('Delete log file'); ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p><?php echo app('translator')->get('Are you sure you want to delete this log file: :date ?', ['date' => $log->date]); ?></p>
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
            let deleteLogForm = document.querySelector('form#delete-log-form')
            let submitBtn = new bootstrap.Button(deleteLogForm.querySelector('button[type=submit]'))

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
                            deleteLogModal.hide();
                            location.replace("<?php echo e(route('log-viewer::logs.list')); ?>");
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

            <?php if (! (empty(log_styler()->toHighlight()))): ?>
                <?php
                    $htmlHighlight = version_compare(PHP_VERSION, '7.4.0') >= 0
                        ? join('|', log_styler()->toHighlight())
                        : join(log_styler()->toHighlight(), '|');
                ?>

                document.querySelectorAll('.stack-content').forEach((elt) => {
                    elt.innerHTML = elt.innerHTML.trim()
                        .replace(/(<?php echo $htmlHighlight; ?>)/gm, '<strong>$1</strong>')
                })
            <?php endif; ?>
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('log-viewer::bootstrap-5._master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\afandina\vendor\arcanedev\log-viewer\views\bootstrap-5\show.blade.php ENDPATH**/ ?>