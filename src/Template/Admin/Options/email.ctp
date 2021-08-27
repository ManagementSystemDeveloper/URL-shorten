<?php
/**
 * @var \App\View\AppView $this
 * @var mixed $options
 * @var array $settings
 */
$this->assign('title', __('Email Settings'));
$this->assign('description', '');
$this->assign('content_title', __('Email Settings'));
?>

<div class="box box-primary">
    <div class="box-body">
        <?= $this->Form->create($options, [
            'id' => 'form-settings',
            'onSubmit' => "save_settings.disabled=true; save_settings.innerHTML='" . __('Saving ...') . "'; return true;"
        ]); ?>

        <div class="row">
            <div class="col-sm-2"><?= __('Admin Email') ?></div>
            <div class="col-sm-10">
                <?=
                $this->Form->control('Options.' . $settings['admin_email']['id'] . '.value', [
                    'label' => false,
                    'class' => 'form-control',
                    'type' => 'email',
                    'value' => $settings['admin_email']['value'],
                ]);
                ?>
                <span
                    class="help-block"><?= __('The recipient email for the contact form and support requests.') ?></span>
            </div>
        </div>

        <legend><?= __("Sending Email Settings") ?></legend>

        <div class="row">
            <div class="col-sm-2"><?= __('From Email') ?></div>
            <div class="col-sm-10">
                <?=
                $this->Form->control('Options.' . $settings['email_from']['id'] . '.value', [
                    'label' => false,
                    'class' => 'form-control',
                    'type' => 'email',
                    'value' => $settings['email_from']['value'],
                ]);

                ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-2"><?= __('Email Method') ?></div>
            <div class="col-sm-10">
                <?=
                $this->Form->control('Options.' . $settings['email_method']['id'] . '.value', [
                    'label' => false,
                    'options' => [
                        'default' => __('PHP Mail Function'),
                        'smtp' => __('SMTP'),
                        'sendmail' => __('Sendmail'),
                        'mail2' => __('PHP Mail Function 2'),
                    ],
                    'value' => $settings['email_method']['value'],
                    'class' => 'form-control',
                ]);

                ?>
            </div>
        </div>

        <div class="row conditional" data-cond-option="Options[<?= $settings['email_method']['id'] ?>][value]"
             data-cond-value="smtp">
            <div class="col-sm-2"><?= __('SMTP Connection Security') ?></div>
            <div class="col-sm-10">
                <?=
                $this->Form->control('Options.' . $settings['email_smtp_security']['id'] . '.value', [
                    'label' => false,
                    'options' => [
                        'none' => __('None'),
                        'ssl' => __('SSL'),
                        'tls' => __('TLS'),
                    ],
                    'value' => $settings['email_smtp_security']['value'],
                    'class' => 'form-control',
                ]);
                ?>
            </div>
        </div>

        <div class="row conditional" data-cond-option="Options[<?= $settings['email_method']['id'] ?>][value]"
             data-cond-value="smtp">
            <div class="col-sm-2"><?= __('SMTP Outgoing Host') ?></div>
            <div class="col-sm-10">
                <?=
                $this->Form->control('Options.' . $settings['email_smtp_host']['id'] . '.value', [
                    'label' => false,
                    'class' => 'form-control',
                    'type' => 'text',
                    'value' => $settings['email_smtp_host']['value'],
                ]);
                ?>
            </div>
        </div>

        <div class="row conditional" data-cond-option="Options[<?= $settings['email_method']['id'] ?>][value]"
             data-cond-value="smtp">
            <div class="col-sm-2"><?= __('SMTP Outgoing Port') ?></div>
            <div class="col-sm-10">
                <?=
                $this->Form->control('Options.' . $settings['email_smtp_port']['id'] . '.value', [
                    'label' => false,
                    'class' => 'form-control',
                    'type' => 'number',
                    'value' => $settings['email_smtp_port']['value'],
                ]);
                ?>
                <span class="help-block">
                    <?= __('Port value depends on the Connection Security type you set above ' .
                        'None - port 25, SSL - port 465, TLS - port 587. these values maybe different between email providers. ') ?>
                </span>
            </div>
        </div>

        <div class="row conditional" data-cond-option="Options[<?= $settings['email_method']['id'] ?>][value]"
             data-cond-value="smtp">
            <div class="col-sm-2"><?= __('SMTP Username') ?></div>
            <div class="col-sm-10">
                <?=
                $this->Form->control('Options.' . $settings['email_smtp_username']['id'] . '.value', [
                    'label' => false,
                    'class' => 'form-control',
                    'type' => 'text',
                    'value' => $settings['email_smtp_username']['value'],
                    'autocomplete' => 'off',
                ]);
                ?>
            </div>
        </div>

        <div class="row conditional" data-cond-option="Options[<?= $settings['email_method']['id'] ?>][value]"
             data-cond-value="smtp">
            <div class="col-sm-2"><?= __('SMTP Password') ?></div>
            <div class="col-sm-10">
                <?=
                $this->Form->control('Options.' . $settings['email_smtp_password']['id'] . '.value', [
                    'label' => false,
                    'class' => 'form-control',
                    'type' => 'password',
                    'value' => $settings['email_smtp_password']['value'],
                    'autocomplete' => 'off',
                ]);
                ?>
            </div>
        </div>

        <?= $this->Form->button(__('Save'), ['name' => 'save_settings', 'class' => 'btn btn-primary']); ?>
        <?= $this->Form->end(); ?>
    </div>
</div>

<?php $this->start('scriptBottom'); ?>
<script>
    $('.conditional').conditionize();
</script>
<?php $this->end(); ?>
