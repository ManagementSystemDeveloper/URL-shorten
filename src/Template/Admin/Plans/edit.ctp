<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Plan $plan
 */
?>
<?php
$this->assign('title', __('Edit Plan'));
$this->assign('description', '');
$this->assign('content_title', __('Edit Plan'));
?>

<style>
    /* The switch - the box around the slider */
    .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
    }

    /* Hide default HTML checkbox */
    .switch input {
        display: none;
    }

    /* The slider */
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }

    input:checked + .slider {
        background-color: #2196F3;
    }

    input:focus + .slider {
        box-shadow: 0 0 1px #2196F3;
    }

    input:checked + .slider:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
    }

    /* Rounded sliders */
    .slider.round {
        border-radius: 34px;
    }

    .slider.round:before {
        border-radius: 50%;
    }
</style>

<div class="box box-primary">
    <div class="box-body">

        <?= $this->Form->create($plan); ?>

        <?= $this->Form->hidden('id'); ?>

        <?=
        $this->Form->control('enable', [
            'label' => __('Enable'),
        ]);
        ?>

        <?=
        $this->Form->control('hidden', [
            'label' => __('Hidden'),
        ]);
        ?>
        <span class="help-block">
            <?= __('Only admins can see hidden plans and assign it to users but users will not see it at the ' .
                'member area.') ?>
        </span>

        <?=
        $this->Form->control('title', [
            'label' => __('Title'),
            'class' => 'form-control',
            'type' => 'text',
        ]);
        ?>

        <div class="row">
            <div class="col-sm-6">
                <?=
                $this->Form->control('monthly_price', [
                    'label' => __('Monthly Price'),
                    'class' => 'form-control',
                    'type' => 'text',
                ]);
                ?>
            </div>
            <div class="col-sm-6">
                <?=
                $this->Form->control('yearly_price', [
                    'label' => __('Yearly Price'),
                    'class' => 'form-control',
                    'type' => 'text',
                ]);
                ?>
            </div>
        </div>

        <?=
        $this->Form->control('description', [
            'label' => __('Description'),
            'class' => 'form-control text-editor',
            'type' => 'textarea',
        ]);
        ?>

        <div class="row">
            <div class="col-sm-6">
                <?=
                $this->Form->control('url_daily_limit', [
                    'label' => __('Maximum number of shortened URLs per day'),
                    'class' => 'form-control',
                    'type' => 'number',
                    'step' => 1,
                    'min' => 0,
                ]);
                ?>
            </div>
            <div class="col-sm-6">
                <?=
                $this->Form->control('url_monthly_limit', [
                    'label' => __('Maximum number of shortened URLs per month'),
                    'class' => 'form-control',
                    'type' => 'number',
                    'step' => 1,
                    'min' => 0,
                ]);
                ?>
            </div>
        </div>

        <table class="table table-hover table-striped">
            <tr>
                <td>
                    <span style="font-weight: bold;"><?= __('Edit Link') ?></span>
                    <span class="help-block"><?= __("Enabling this feature will allow short link creator " .
                            "to edit his links but without editing the long URL.") ?></span>
                </td>
                <td>
                    <label class="switch"><?= $this->Form->checkbox('edit_link'); ?><span
                            class="slider round"></span></label>
                </td>
            </tr>
            <tr>
                <td>
                    <span style="font-weight: bold;"><?= __('Edit Long URL') ?></span>
                    <span class="help-block">
                        <?= __("Enabling this feature will allow short link creator to edit the long URL " .
                            "for his links. You must enable 'Edit Link' feature to use this feature.") ?>
                    </span>
                </td>
                <td><label class="switch"><?= $this->Form->checkbox('edit_long_url'); ?><span
                            class="slider round"></span></label></td>
            </tr>
            <tr>
                <td>
                    <span style="font-weight: bold;"><?= __('Custom Alias') ?></span>
                    <span class="help-block"><?= __("Enabling this feature will allow short link creator " .
                            "to add custom alias when shorten a URL.") ?></span>
                </td>
                <td><label class="switch"><?= $this->Form->checkbox('alias'); ?><span
                            class="slider round"></span></label></td>
            </tr>
            <tr>
                <td>
                    <span style="font-weight: bold;"><?= __('Protect Link With Password') ?></span>
                    <span class="help-block"><?= __("Enabling this feature will allow short link creator " .
                            "to protect short links with a password.") ?></span>
                </td>
                <td><label class="switch"><?= $this->Form->checkbox('password'); ?><span
                            class="slider round"></span></label></td>
            </tr>
            <tr>
                <td>
                    <span style="font-weight: bold;"><?= __('Delete Link') ?></span>
                    <span class="help-block"><?= __("Enabling this feature will allow short link creator " .
                            "to delete his links.") ?></span>
                </td>
                <td><label class="switch"><?= $this->Form->checkbox('delete_link'); ?><span
                            class="slider round"></span></label></td>
            </tr>
            <tr>
                <td>
                    <span style="font-weight: bold;"><?= __('Use Bundles') ?></span>
                    <span class="help-block"><?= __("Enabling this feature will allow short link creator " .
                            "to manage bundles and choose when shorten a link.") ?></span>
                </td>
                <td><label class="switch"><?= $this->Form->checkbox('bundle'); ?><span
                            class="slider round"></span></label></td>
            </tr>
            <tr>
                <td>
                    <span style="font-weight: bold;"><?= __('Remove Ads Area 1') ?></span>
                    <span class="help-block"><?= __("By enabling this feature, Ads on area 1 will be disabled for " .
                            " the users assigned to this plan.") ?></span>
                </td>
                <td><label class="switch"><?= $this->Form->checkbox('disable_ads_area1'); ?><span
                            class="slider round"></span></label></td>
            </tr>
            <tr>
                <td>
                    <span style="font-weight: bold;"><?= __('Remove Ads Area 2') ?></span>
                    <span class="help-block"><?= __("By enabling this feature, Ads on area 2 will be disabled for " .
                            " the users assigned to this plan.") ?></span>
                </td>
                <td><label class="switch"><?= $this->Form->checkbox('disable_ads_area2'); ?><span
                            class="slider round"></span></label></td>
            </tr>
            <tr>
                <td>
                    <span style="font-weight: bold;"><?= __('Timer') ?></span>
                    <span class="help-block"><?= __("Enabling this feature will display a count down timer " .
                            "into the short link page. 0, will disable the timer.") ?></span>
                </td>
                <td>
                    <?=
                    $this->Form->control('timer', [
                        'label' => false,
                        'placeholder' => __('Enter the number of seconds'),
                        'class' => 'form-control',
                        'type' => 'number',
                        'step' => 1,
                        'min' => 0,
                    ]);
                    ?>
                </td>
            </tr>
            <tr>
                <td>
                    <span style="font-weight: bold;"><?= __('Display Feed') ?></span>
                    <span class="help-block">
                        <?= __("Enabling this feature will allow short link creator to " .
                            "display articles from their website(using the RSS feed URL) into the short link page.") ?>
                    </span>
                </td>
                <td><label class="switch"><?= $this->Form->checkbox('feed'); ?><span
                            class="slider round"></span></label></td>
            </tr>
            <tr>
                <td>
                    <span style="font-weight: bold;"><?= __('Display Comments') ?></span>
                    <span class="help-block">
                        <?= __("Enabling this feature will allow short link creator to display a comment " .
                            "box so visitors can leave their comments into the short link page.") ?>
                    </span>
                </td>
                <td><label class="switch"><?= $this->Form->checkbox('comments'); ?><span
                            class="slider round"></span></label></td>
            </tr>
            <tr>
                <td style="font-weight: bold;"><?= __('Link Statistics') ?></td>
                <td>
                    <?=
                    $this->Form->control('stats', [
                        'label' => false,
                        'options' => [
                            1 => __('Disable'),
                            2 => __('Simple'),
                            3 => __('Advanced'),
                        ],
                        'class' => 'form-control',
                    ]);
                    ?>
                </td>
            </tr>
            <tr>
                <td style="font-weight: bold;"><?= __('Quick Link Tool') ?></td>
                <td><label class="switch"><?= $this->Form->checkbox('api_quick'); ?><span
                            class="slider round"></span></label></td>
            </tr>
            <tr>
                <td style="font-weight: bold;"><?= __('Mass Shrinker Tool') ?></td>
                <td><label class="switch"><?= $this->Form->checkbox('api_mass'); ?><span
                            class="slider round"></span></label></td>
            </tr>
            <tr>
                <td style="font-weight: bold;"><?= __('Full Page Script Tool') ?></td>
                <td><label class="switch"><?= $this->Form->checkbox('api_full'); ?><span
                            class="slider round"></span></label></td>
            </tr>
            <tr>
                <td style="font-weight: bold;"><?= __('Bookmarklet Tool') ?></td>
                <td><label class="switch"><?= $this->Form->checkbox('bookmarklet'); ?><span class="slider round"></span></label>
                </td>
            </tr>
            <tr>
                <td style="font-weight: bold;"><?= __('Developers API Tool') ?></td>
                <td><label class="switch"><?= $this->Form->checkbox('api_developer'); ?><span
                            class="slider round"></span></label></td>
            </tr>
        </table>

        <?= $this->Form->button(__('Submit'), ['class' => 'btn btn-primary']); ?>

        <?= $this->Form->end(); ?>
    </div>
</div>

<?php $this->start('scriptBottom'); ?>
<script src="//cdn.ckeditor.com/4.6.2/full/ckeditor.js"></script>
<script>
    $(document).ready(function () {
        CKEDITOR.replaceClass = 'text-editor';
        CKEDITOR.config.allowedContent = true;
        CKEDITOR.dtd.$removeEmpty['span'] = false;
        CKEDITOR.dtd.$removeEmpty['i'] = false;
    });
</script>
<?php $this->end(); ?>
