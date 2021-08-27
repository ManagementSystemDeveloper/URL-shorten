<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Bundle[]|\Cake\Collection\CollectionInterface $bundles
 * @var mixed $post
 */
$this->assign('title', __('Manage Bundles'));
$this->assign('description', '');
$this->assign('content_title', __('Manage Bundles'));
?>

<div class="box box-primary">
    <div class="box-body no-padding">

        <table class="table table-hover table-striped">
            <tr>
                <th><?= __('Title') ?></th>
                <th><?= __('Slug') ?></th>
                <th><?= __('Status'); ?></th>
                <th><?= __('Views') ?></th>
                <th><?= __('Modified') ?></th>
                <th><?= __('Created') ?></th>
                <th><?= __('Actions') ?></th>
            </tr>

            <!-- Here is where we loop through our $posts array, printing out post info -->

            <?php foreach ($bundles as $bundle) : ?>
                <tr>
                    <td>
                        <?= $this->Html->link($bundle->title, [
                            'action' => 'edit',
                            $bundle->id,
                        ]);
                        ?>
                    </td>
                    <td><?= h($bundle->slug) ?></td>
                    <td><?= ($bundle->private) ? __('Private') : __('Public') ?></td>
                    <td><?= $bundle->views ?></td>
                    <td><?= display_date_timezone($bundle->modified) ?></td>
                    <td><?= display_date_timezone($bundle->created) ?></td>
                    <td>
                        <?= $this->Html->link(
                            __('View'),
                            $bundle->permalink(),
                            ['class' => 'btn btn-primary btn-xs', 'target' => '_blank']
                        );
                        ?>

                        <?= $this->Html->link(
                            __('Edit'),
                            ['action' => 'edit', $bundle->id],
                            ['class' => 'btn btn-info btn-xs']
                        );
                        ?>

                        <?= $this->Form->postLink(
                            __('Delete'),
                            ['action' => 'delete', $bundle->id],
                            ['confirm' => __('Are you sure?'), 'class' => 'btn btn-danger btn-xs']
                        );
                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php unset($post); ?>
        </table>

    </div><!-- /.box-body -->
</div>

<ul class="pagination">
    <?php
    $this->Paginator->setTemplates([
        'ellipsis' => '<li><a href="javascript: void(0)">...</a></li>',
    ]);

    if ($this->Paginator->hasPrev()) {
        echo $this->Paginator->prev('«');
    }

    echo $this->Paginator->numbers([
        'modulus' => 4,
        'first' => 2,
        'last' => 2,
    ]);

    if ($this->Paginator->hasNext()) {
        echo $this->Paginator->next('»');
    }
    ?>
</ul>
