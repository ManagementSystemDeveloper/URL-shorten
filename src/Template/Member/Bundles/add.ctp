<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Bundle $bundle
 */
$this->assign('title', __('Add Bundle'));
$this->assign('description', '');
$this->assign('content_title', __('Add Bundle'));
?>

<div class="box box-primary">
    <div class="box-body">

        <?= $this->Form->create($bundle); ?>

        <?=
        $this->Form->control('title', [
            'label' => __('Title'),
            'class' => 'form-control',
            'type' => 'text'
        ]);
        ?>

        <?=
        $this->Form->control('slug', [
            'label' => __('Slug'),
            'class' => 'form-control',
            'type' => 'text',
        ]);
        ?>

        <?=
        $this->Form->control('private', [
            'label' => __('Status'),
            'options' => [
                '1' => __('Private'),
                '0' => __('Public')
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
