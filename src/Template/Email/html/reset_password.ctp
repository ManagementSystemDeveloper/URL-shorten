<?php
/**
 * @var \App\View\AppView $this
 * @var string $activation_key
 * @var string $username
 */
?>
<p><?= __('Hello') ?> <b><?= $username; ?></b>,</p>

<p><?= __('Someone requested that the password be reset for the following account:') ?></p>

<p><?= $this->Url->build('/', true); ?></p>

<p><?= __('If this was a mistake, just ignore this email and nothing will happen.') ?></p>

<p><?= __('To reset your password click on the following link or copy-paste it in your browser:') ?></p>

<?php
$url = $this->Url->build('/', true) . 'auth/users/forgot-password/' . $username . '/' . $activation_key;
?>

<p>
    <a href="<?= $url ?>"><?= $url ?></a>
</p>

<p>
    <?= __('Thanks,') ?><br>
    <?= h(get_option('site_name')) ?>
</p>
