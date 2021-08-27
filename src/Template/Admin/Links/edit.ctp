<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Link $link
 * @var object $bundles
 */
$this->assign('title', __('Edit Link: {0}', $link->alias));
$this->assign('description', '');
$this->assign('content_title', __('Edit Link: {0}', $link->alias));
?>

<div class="box box-primary">
    <div class="box-body">

        <?= $this->Form->create($link); ?>

        <?= $this->Form->hidden('id'); ?>

        <?=
        $this->Form->control('status', [
            'label' => __('Status'),
            'options' => [
                1 => __('Active'),
                2 => __('Hidden'),
                3 => __('Inactive')
            ],
            'class' => 'form-control'
        ]);
        ?>

        <?=
        $this->Form->control('url', [
            'label' => __('Long URL'),
            'class' => 'form-control',
            'type' => 'url'
        ]);
        ?>

        <?=
        $this->Form->control('password', [
            'label' => __('Password'),
            'class' => 'form-control',
            'type' => 'text',
            "autocomplete" => "false"
        ]);
        ?>

        <?=
        $this->Form->control('title', [
            'label' => __('Title'),
            'class' => 'form-control',
            'type' => 'text'
        ]);
        ?>

        <?=
        $this->Form->control('description', [
            'label' => __('Description'),
            'class' => 'form-control',
            'type' => 'textarea'
        ]);
        ?>

        <?php
        $redirects = get_allowed_redirects();

        if (count($redirects) > 1) {
            echo $this->Form->control('type', [
                'label' => __('Redirect Type'),
                'options' => $redirects,
                //'empty'   => __( 'Choose' ),
                'class' => 'form-control input-sm'
            ]);
        } else {
            echo $this->Form->hidden('type', ['value' => get_option('member_default_redirect', 1)]);
        }
        ?>

        <?php
        if($bundles->count() > 0) {
            echo $this->Form->control('bundles._ids', [
                'label' => __('Bundles'),
                'options' => $bundles,
                'data-placeholder' => __('Select Bundles'),
                'class' => 'form-control input-sm select2',
            ]);
        }
        ?>

        <?= $this->Form->button(__('Submit'), ['class' => 'btn btn-primary']); ?>

        <?= $this->Form->end(); ?>
    </div>
</div>
