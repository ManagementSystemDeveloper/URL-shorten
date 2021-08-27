<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Bundle $bundle
 */
$this->assign('title', __('Edit Bundle'));
$this->assign('description', '');
$this->assign('content_title', __('Edit Bundle'));
?>

<div class="box box-primary">
    <div class="box-body">

        <?= $this->Form->create($bundle); ?>

        <?= $this->Form->hidden('id'); ?>

        <?=
        $this->Form->control('title', [
            'label' => __('Title'),
            'class' => 'form-control',
            'type' => 'text'
        ]);
        ?>

        <?=
        $this->Form->control('private', [
            'label' => __('Private'),
            'options' => [
                '1' => __('Yes'),
                '0' => __('No')
            ],
            'class' => 'form-control'
        ]);
        ?>

        <?=
        $this->Form->control('description', [
            'label' => __('Description'),
            'class' => 'form-control text-editor',
            'type' => 'textarea'
        ]);
        ?>

        <?= $this->Form->button(__('Submit'), ['class' => 'btn btn-primary']); ?>

        <?= $this->Form->end(); ?>
    </div>
</div>
