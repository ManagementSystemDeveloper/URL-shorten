<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $logged_user
 * @var \App\Model\Entity\Plan $logged_user_plan
 */
$users = \Cake\ORM\TableRegistry::getTableLocator()->get('Users');

$bundles = $users->Bundles
    ->find('list', [
        'keyField' => 'id',
        'valueField' => 'title',
    ])
    ->where([
        'Bundles.user_id' => $logged_user->id,
    ])
    ->orderDesc('Bundles.id');
?>

<?=
$this->Form->create(null, [
    'url' => ['controller' => 'Links', 'action' => 'shorten', 'prefix' => false],
    'id' => 'shorten',
]);
?>

<?php
$this->Form->unlockField('smart');
?>

<?=
$this->Form->control('url', [
    'label' => false,
    'class' => 'form-control input-lg',
    'type' => 'text',
    'required' => 'required',
    'placeholder' => __('Your URL Here'),
]);
?>

<div class="form-inline">
    <?php if (count(get_multi_domains_list())) : ?>
        <?php
        $multi_domains_list = get_multi_domains_list();
        echo $this->Form->control('domain', [
            'label' => false,
            'options' => $multi_domains_list,
            'default' => '',
            'empty' => get_default_short_domain(),
            'class' => 'form-control input-lg',
            'placeholder' => __('Domain'),
        ]);
        ?>
        <?= '/' ?>
    <?php else: ?>
        <div class="form-control-static input-lg"><?= get_short_url(); ?></div>
    <?php endif; ?>

    <?php if ($logged_user_plan->alias) : ?>
        <?=
        $this->Form->control('alias', [
            'label' => false,
            'type' => 'text',
            'class' => 'form-control input-lg',
            'required' => false,
            'placeholder' => __('Alias'),
        ]);
        ?>
    <?php endif; ?>
</div>

<div class="advanced-div" style="display: none; overflow: hidden;">

    <div class="row">
        <?php if ($logged_user_plan->password) : ?>
            <div class="col-sm-4">
                <?=
                $this->Form->control('password', [
                    'label' => false,
                    'type' => 'text',
                    'class' => 'form-control input-sm',
                    'placeholder' => __('Password Protect'),
                    "autocomplete" => "false",
                ]);
                ?>
            </div>
        <?php endif; ?>
        <div class="col-sm-4">
            <?php
            $redirects = get_allowed_redirects();

            if (count($redirects) > 1) {
                echo $this->Form->control('type', [
                    'label' => false,
                    'options' => $redirects,
                    'default' => $logged_user->redirect_type,
                    'empty' => __('Select Redirect Type'),
                    'class' => 'form-control input-sm',
                ]);
            } else {
                echo $this->Form->hidden('type', ['value' => $logged_user->redirect_type]);
            }
            ?>
        </div>
        <?php if ($logged_user_plan->bundle) : ?>
            <div class="col-sm-4">
                <?=
                $this->Form->control('bundles._ids', [
                    'label' => false,
                    'options' => $bundles,
                    'data-placeholder' => __('Select Bundles'),
                    'class' => 'form-control input-sm select2',
                ]);
                ?>
            </div>
        <?php endif; ?>
    </div>

    <legend><?= __('Smart Targeting') ?></legend>

    <div class="genius_box">
        <div class="genius_wrap">
            <?= $this->General->buildMoreFields(); ?>
        </div>
        <a class="add_field_button btn btn-primary btn-lg pull-right" href="#">
            <i class="fa fa-plus-square" aria-hidden="true"></i> <?= __('Add More') ?>
        </a>
    </div>

</div>

<?= $this->Form->button(__('Shorten'), ['class' => 'btn btn-submit btn-primary btn-sm']); ?>

<button type="button" class="btn btn-default btn-sm advanced"><?= __('Advanced Options') ?></button>

<?= $this->Form->end(); ?>

<div class="shorten add-link-result"></div>
