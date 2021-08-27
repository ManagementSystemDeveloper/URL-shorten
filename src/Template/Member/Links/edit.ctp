<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Link $link
 * @var object $bundles
 * @var mixed $edit_long_url
 * @var mixed $link_password
 */
$this->assign('title', __('Edit Link: {0}', $link->alias));
$this->assign('description', '');
$this->assign('content_title', __('Edit Link: {0}', $link->alias));
?>

<div class="box box-primary">
    <div class="box-body">

        <?= $this->Form->create($link); ?>

        <?php
        $this->Form->unlockField('smart');
        ?>

        <?= $this->Form->hidden('id'); ?>

        <?=
        $this->Form->control('url', [
            'label' => __('Long URL'),
            'class' => 'form-control',
            'type' => 'url',
            'disabled' => ($edit_long_url) ? false : true,
        ]);
        ?>

        <?php if ($link_password) : ?>
            <?=
            $this->Form->control('password', [
                'label' => __('Password'),
                'type' => 'text',
                'placeholder' => __('Password'),
                'class' => 'form-control',
                "autocomplete" => "false",
            ]);
            ?>
        <?php endif; ?>

        <?=
        $this->Form->control('title', [
            'label' => __('Title'),
            'class' => 'form-control',
            'type' => 'text',
        ]);
        ?>

        <?=
        $this->Form->control('description', [
            'label' => __('Description'),
            'class' => 'form-control',
            'type' => 'textarea',
        ]);
        ?>

        <?php
        $redirects = get_allowed_redirects();

        if (count($redirects) > 1) {
            echo $this->Form->control('type', [
                'label' => __('Redirect Type'),
                'options' => $redirects,
                'default' => get_option('member_default_redirect', 1),
                //'empty'   => __( 'Choose' ),
                'class' => 'form-control input-sm',
            ]);
        } else {
            echo $this->Form->hidden('type', ['value' => get_option('member_default_redirect', 1)]);
        }
        ?>

        <?php
        if ($bundles->count() > 0) {
            echo $this->Form->control('bundles._ids', [
                'label' => __('Bundles'),
                'options' => $bundles,
                'data-placeholder' => __('Select Bundles'),
                'class' => 'form-control input-sm select2',
            ]);
        }
        ?>

        <legend><?= __('Smart Targeting') ?></legend>

        <div class="genius_box">
            <div class="genius_wrap">
                <?php
                if (is_serialized($link->smart)) {
                    $smart = unserialize($link->smart);
                    if (!empty($smart)) {
                        foreach ($smart as $key => $item) {
                            echo $this->General->buildMoreFields($key, $item);
                        }
                    }
                }
                ?>
            </div>
            <a class="add_field_button" href="#">
                <h4><i class="fa fa-plus-square" aria-hidden="true"></i> <?= __('Add More') ?></h4>
            </a>
        </div>

        <?= $this->Form->button(__('Submit'), ['class' => 'btn btn-primary']); ?>

        <?= $this->Form->end(); ?>
    </div>
</div>
