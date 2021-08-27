<?php
/**
 * @var \App\View\AppView $this
 */
?>
<?=
$this->Form->create(null, [
    'url' => ['controller' => 'Forms', 'action' => 'contact', 'prefix' => false],
    'id' => 'contact-form'
]);
?>

<?php
$this->Form->setTemplates([
    'inputContainer' => '{{content}}',
    'error' => '{{content}}',
    'inputContainerError' => '{{content}}'
]);

?>
<div class="row">
    <div class="col-sm-6">
        <div class="form-group wow fadeInUp">
            <?=
            $this->Form->control('name', [
                'label' => false,
                'type' => 'text',
                'placeholder' => __('Your Name *'),
                'required' => 'required',
                'class' => 'form-control'
            ]);

            ?>
            <p class="help-block text-danger"></p>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group wow fadeInUp">
            <?=
            $this->Form->control('email', [
                'label' => false,
                'type' => 'text',
                'placeholder' => __('Your Email *'),
                'required' => 'required',
                'class' => 'form-control'
            ]);

            ?>
            <p class="help-block text-danger"></p>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group wow fadeInUp">
            <?=
            $this->Form->control('subject', [
                'label' => false,
                'type' => 'text',
                'placeholder' => __('Your Subject *'),
                'required' => 'required',
                'class' => 'form-control'
            ]);

            ?>
            <p class="help-block text-danger"></p>
        </div>
    </div>
    <div class="col-sm-12 wow fadeInUp">
        <div class="form-group">
            <?=
            $this->Form->control('message', [
                'label' => false,
                'type' => 'textarea',
                'placeholder' => __('Your Message *'),
                'required' => 'required',
                'class' => 'form-control'
            ]);

            ?>
            <p class="help-block text-danger"></p>
        </div>
        <div class="form-group">
            <?= $this->Form->control('accept', [
                'type' => 'checkbox',
                'label' => "<b>" . __(
                        "I consent to having this website store my submitted information so they can respond to my inquiry"
                    ) . "</b>",
                'escape' => false,
                'required' => true
            ]) ?>
        </div>

    </div>

</div>

<div class="wow fadeInUp">
    <?php if ((get_option('enable_captcha_contact') == 'yes') && isset_captcha()) : ?>
        <div class="form-group captcha">
            <div id="captchaContact" style="display: inline-block;"></div>
        </div>
        <?php
        $this->Form->unlockField('g-recaptcha-response');
        $this->Form->unlockField('adcopy_challenge');
        $this->Form->unlockField('adcopy_response');
        ?>
    <?php endif; ?>
</div>

<div class="text-center wow fadeInUp">
    <div id="success"></div>
    <?= $this->Form->button('<i class="fa fa-paper-plane"></i> '.__('Send Message'), [
        'class' => 'btn btn-primary btn-captcha btn-lg pull-right',
        'id' => 'invisibleCaptchaContact'
    ]); ?>
</div>

<?= $this->Form->end(); ?>

<div class="contact-result"></div>
