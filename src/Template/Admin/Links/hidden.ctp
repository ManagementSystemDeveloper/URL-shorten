<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Link[]|\Cake\Collection\CollectionInterface $links
 */
$this->assign('title', __('Hidden Links'));
$this->assign('description', '');
$this->assign('content_title', __('Hidden Links'));
?>

<div class="box box-solid">
    <div class="box-body">
        <?php
        // The base url is the url where we'll pass the filter parameters
        $base_url = ['controller' => 'Links', 'action' => 'hidden'];

        echo $this->Form->create(null, [
            'url' => $base_url,
            'class' => 'form-inline'
        ]);
        ?>

        <?=
        $this->Form->control('Filter.user_id', [
            'label' => false,
            'class' => 'form-control',
            'type' => 'text',
            'size' => 0,
            'placeholder' => __('User Id')
        ]);
        ?>

        <?=
        $this->Form->control('Filter.alias', [
            'label' => false,
            'class' => 'form-control',
            'type' => 'text',
            'size' => 10,
            'placeholder' => __('Alias')
        ]);
        ?>

        <?=
        $this->Form->control('Filter.type', [
            'label' => false,
            'options' => get_allowed_redirects(),
            'empty' => __('Redirect Type'),
            'class' => 'form-control'
        ]);
        ?>

        <?=
        $this->Form->control('Filter.title_desc', [
            'label' => false,
            'class' => 'form-control',
            'type' => 'text',
            'placeholder' => __('Title, Desc. or URL')
        ]);
        ?>

        <?= $this->Form->button(__('Filter'), ['class' => 'btn btn-default btn-sm']); ?>

        <?= $this->Html->link(__('Reset'), $base_url, ['class' => 'btn btn-link btn-sm']); ?>

        <?= $this->Form->end(); ?>

    </div>
</div>

<?php foreach ($links as $link) : ?>

    <?php
    $title = $link->alias;
    if (!empty($link->title)) {
        $title = $link->title;
    }
    ?>

    <div class="box box-solid">
        <div class="box-body">
            <h4><a href="<?= $link->permalink() ?>" target="_blank"><span
                            class="glyphicon glyphicon-link"></span> <?= h($title) ?></a></h4>
            <p class="text-muted">
                <small><i class="fa fa-bar-chart"></i> <a href="<?= $link->permalink() ?>/info"
                                                          target="_blank"><?= __('Stats') ?></a> - <i
                            class="fa fa-user"></i> <?= $this->Html->link(
                                                              $link->user->username,
                                                              ['controller' => 'Users', 'action' => 'view', $link->user_id]
                                                          ); ?> - <i
                            class="fa fa-calendar"></i> <?= display_date_timezone($link->created); ?> - <a
                            target="_blank" href="<?= $link->url ?>"><?= strtoupper(parse_url(
                                $link->url,
                                PHP_URL_HOST
                            )); ?></a></small>
            </p>
            <div class="row">
                <div class="col-sm-6">
                    <div class="input-group"><input type="text" class="form-control input-sm" value="<?= $link->permalink() ?>"
                                                    readonly="" onfocus="javascript:this.select()">
                        <div class="input-group-addon copy-it" data-clipboard-text="<?= $link->permalink() ?>"
                             data-toggle="tooltip" data-placement="bottom" title="<?= __('Copy') ?>"><i
                                    class="fa fa-clone"></i></div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="text-right">
                        <?= $this->Html->link(
                                 __('Edit'),
                                 ['action' => 'edit', $link->alias],
                                 ['class' => 'btn btn-primary btn-sm']
                             ); ?>
                        <?= $this->Form->postLink(
                            __('Unhide'),
                            ['action' => 'unhide', $link->alias],
                            ['confirm' => __('Are you sure?'), 'class' => 'btn btn-warning btn-sm']
                        ); ?>
                        <?= $this->Form->postLink(
                            __('Deactivate'),
                            ['action' => 'deactivate', $link->alias],
                            ['confirm' => __('Are you sure?'), 'class' => 'btn btn-danger btn-sm']
                        ); ?>
                        <?= $this->Form->postLink(
                            __('Delete'),
                            ['action' => 'delete', $link->alias],
                            ['confirm' => __('Are you sure?'), 'class' => 'btn btn-danger btn-sm']
                        ); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php endforeach; ?>
<?php unset($link); ?>

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
