<?php
/**
 * @var \App\View\AppView $this
 * @var array $_SESSION
 */
$app_vars = [
    'base_url' => $this->Url->build('/', true),
    'language' => h(locale_get_default()),
    'copy' => h(__("Copy")),
    'copied' => h(__("Copied!")),
    'user_id' => h($this->request->getSession()->read('Auth.User.id')),
    'home_shortening_register' => (get_option('home_shortening_register') == 'yes') ? 'yes' : 'no',
    'enable_captcha' => get_option('enable_captcha', 'no'),
    'captcha_type' => h(get_option('captcha_type', "recaptcha")),
    'reCAPTCHA_site_key' => h(get_option('reCAPTCHA_site_key')),
    'invisible_reCAPTCHA_site_key' => h(get_option('invisible_reCAPTCHA_site_key')),
    'solvemedia_challenge_key' => h(get_option('solvemedia_challenge_key')),
    'captcha_short_anonymous' => h(get_option('enable_captcha_shortlink_anonymous', 0)),
    'captcha_signin' => h(get_option('enable_captcha_signin', 'no')),
    'captcha_signup' => h(get_option('enable_captcha_signup', 'no')),
    'captcha_forgot_password' => h(get_option('enable_captcha_forgot_password', 'no')),
    'captcha_contact' => h(get_option('enable_captcha_contact', 'no')),
    'cookie_notification_bar' => (bool)get_option('cookie_notification_bar', 1),
    'cookie_message' => __('This website uses cookies to ensure you get the best experience on our website. {0}',
        "<a href='" . $this->Url->build('/pages/privacy', true) . "' target='_blank'><b>" .
        __('Learn more') . "</b></a>"),
    'cookie_button' => h(__('Got it!')),
];
?>

<script type='text/javascript'>
    /* <![CDATA[ */
    var app_vars = <?= json_encode($app_vars) ?>;
    /* ]]> */
</script>

<?php
$appAuth = (isset($_SESSION['Auth']['AppAuth'])) ? $_SESSION['Auth']['AppAuth'] : null;
?>
<?php if (isset($appAuth['Domains'])) : ?>
    <?php
    $cookie = '';
    if (!empty($appAuth['Cookie'])) {
        $cookie = '&cookie=' . $appAuth['Cookie'];
    }
    ?>
    <?php foreach ($appAuth['Domains'] as $domain) : ?>
        <?php
        $url = '//' . mb_strtolower($domain) . $this->request->getAttribute('base');
        $url .= '/auth/users/multidomains-auth?auth=' . $appAuth['DomainsData'] . $cookie;
        ?>
        <div style="position: fixed;width:1px;height:1px;background:url('<?= $url ?>') no-repeat -9999px -9999px"></div>
    <?php endforeach; ?>

    <script>
        window.addEventListener('load', function () {
            var img = document.createElement('img');
            img.setAttribute('src', "<?= $this->Assets->url('/auth/users/auth-done') ?>");
            img.setAttribute('style', 'position: fixed;');
            document.getElementsByTagName('body')[0].appendChild(img);
            //console.log(img);
        });
    </script>
<?php endif; ?>
