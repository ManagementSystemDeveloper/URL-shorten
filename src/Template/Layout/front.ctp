<?php
/**
 * @var \App\View\AppView $this
 */
?>
<?php $user = $this->request->getSession()->read('Auth.User'); ?>
<!DOCTYPE html>
<html lang="<?= locale_get_primary_language(null) ?>">

<head>
    <?= $this->Html->charset(); ?>
    <title><?= h($this->fetch('title')); ?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?= h($this->fetch('description')); ?>">
    <meta name="og:title" content="<?= h($this->fetch('og_title')); ?>">
    <meta name="og:description" content="<?= h($this->fetch('og_description')); ?>">
    <meta property="og:image" content="<?= h($this->fetch('og_image')); ?>"/>

    <?= $this->Assets->favicon() ?>

    <?= $this->Assets->css('/vendor/bootstrap/css/bootstrap.min.css?ver=' . APP_VERSION); ?>
    <?= $this->Assets->css('/vendor/font-awesome/css/font-awesome.min.css?ver=' . APP_VERSION); ?>
    <?= $this->Assets->css('/vendor/animate.min.css?ver=' . APP_VERSION); ?>
    <?= $this->Assets->css('/vendor/owl/owl.carousel.min.css?ver=' . APP_VERSION); ?>
    <?= $this->Assets->css('/vendor/owl/owl.theme.default.css?ver=' . APP_VERSION); ?>
    <?= $this->Assets->css('https://cdn.jsdelivr.net/npm/select2@4.0.11/dist/css/select2.min.css'); ?>

    <?= $this->Assets->css('front.css?ver=' . APP_VERSION); ?>

    <?php
    echo $this->fetch('meta');
    echo $this->fetch('css');
    echo $this->fetch('script');
    ?>

    <link href='//fonts.googleapis.com/css?family=Lato:300,400,700,900' rel='stylesheet' type='text/css'>

    <?= get_option('head_code'); ?>

    <?= $this->fetch('scriptTop') ?>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="//oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body id="page-top" class="index <?= ($this->request->getParam('_name') === 'home') ? 'home' : 'inner-page' ?>">
<?= get_option('after_body_tag_code'); ?>
<!-- Navigation -->
<nav id="mainNav" class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header page-scroll">
            <button type="button" class="navbar-toggle" data-toggle="collapse"
                    data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only"><?= __('Toggle navigation') ?></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <?php
            $logo = get_logo();
            $class = '';
            if ($logo['type'] == 'image') {
                $class = 'logo-image';
            }
            ?>
            <a class="navbar-brand <?= $class ?>" href="<?= build_main_domain_url('/'); ?>"><?= $logo['content'] ?></a>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right">
                <li>
                    <a href="<?= build_main_domain_url('/'); ?>"><?= __('Home') ?></a>
                </li>
                <?php if ((bool)get_option('blog_enable', false)) : ?>
                    <li>
                        <a href="<?= build_main_domain_url('/blog'); ?>"><?= __('Blog') ?></a>
                    </li>
                <?php endif; ?>
                <?php
                if (null !== $this->request->getSession()->read('Auth.User.id')) {
                    ?>
                    <li>
                        <a href="<?= build_main_domain_url('/member/dashboard'); ?>"><?= __('Dashboard') ?></a>
                    </li>
                    <?php
                } else {
                    ?>
                    <li>
                        <a href="<?= build_main_domain_url('/auth/signin'); ?>"><?= __('Login') ?></a>
                    </li>
                    <li>
                        <a href="<?= build_main_domain_url('/auth/signup'); ?>"><?= __('Sign Up') ?></a>
                    </li>
                    <?php
                }
                ?>
                <?php if (count(get_site_languages(true)) > 1) : ?>
                    <li class="dropdown language-selector">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                           aria-expanded="false"><i class="fa fa-language"></i> <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <?php foreach (get_site_languages(true) as $lang) : ?>
                                <li><?= $this->Html->link(locale_get_display_name($lang, $lang),
                                        $this->request->getPath() . '?lang=' . $lang); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container-fluid -->
</nav>

<?= $this->Flash->render() ?>
<?= $this->fetch('content') ?>

<footer>
    <div class="container">
        <div class="row">
            <div class="col-sm-6">
                <div class="social-links">
                    <?php if (get_option('facebook_url')) : ?>
                        <a href="<?= h(get_option('facebook_url')) ?>" target="_blank">
                            <i class="fa fa-facebook"></i>
                        </a>
                    <?php endif; ?>
                    <?php if (get_option('twitter_url')) : ?>
                        <a href="<?= h(get_option('twitter_url')) ?>" target="_blank">
                            <i class="fa fa-twitter"></i>
                        </a>
                    <?php endif; ?>
                    <?php if (get_option('googleplus_url')) : ?>
                        <a href="<?= h(get_option('googleplus_url')) ?>" target="_blank">
                            <i class="fa fa-google-plus"></i></a>
                    <?php endif; ?>
                    <?php if (get_option('vkontakte_url')) : ?>
                        <a href="<?= h(get_option('vkontakte_url')) ?>" target="_blank">
                            <i class="fa fa-vk"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="bottom-menu">
                    <ul class="list-inline">
                        <li><a href="<?= build_main_domain_url('/'); ?>"><?= __('Home') ?></a></li>
                        <li><a href="<?= build_main_domain_url('/pages/terms'); ?>"><?= __('Terms of Use') ?></a></li>
                        <li><a href="<?= build_main_domain_url('/pages/privacy'); ?>"><?= __('Privacy Policy') ?></a>
                        </li>
                        <li><a href="<?= build_main_domain_url('/pages/dmca'); ?>"><?= __('DMCA') ?></a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="copyright text-center">
            <?= __('Copyright &copy;') ?> <?= h(get_option('site_name')) ?> <?= date("Y") ?>
        </div>
    </div>
</footer>

<?= $this->Assets->script('/vendor/jquery.min.js?ver=' . APP_VERSION); ?>
<?= $this->Assets->script('//code.jquery.com/ui/1.12.1/jquery-ui.min.js?ver=' . APP_VERSION); ?>
<?= $this->Assets->script('/vendor/bootstrap/js/bootstrap.min.js?ver=' . APP_VERSION); ?>
<?= $this->Assets->script('/vendor/owl/owl.carousel.min.js?ver=' . APP_VERSION); ?>
<?= $this->Assets->script('/vendor/wow.min.js?ver=' . APP_VERSION); ?>
<?= $this->Assets->script('https://cdn.jsdelivr.net/npm/clipboard@2.0.4/dist/clipboard.min.js'); ?>
<?= $this->Assets->script('https://cdn.jsdelivr.net/gh/jnicol/particleground@eac0d29a85e12523de625845e2cd30be3fa266b6/jquery.particleground.min.js'); ?>
<?= $this->Assets->script('https://cdn.jsdelivr.net/npm/select2@4.0.11/dist/js/select2.min.js'); ?>

<?= $this->element('js_vars'); ?>

<!-- Custom Theme JavaScript -->
<?= $this->Assets->script('front.js?ver=' . APP_VERSION); ?>
<?= $this->Assets->script('app.js?ver=' . APP_VERSION); ?>

<?php if (in_array(get_option('captcha_type', 'recaptcha'), ['recaptcha', 'invisible-recaptcha'])) : ?>
    <script src="https://www.recaptcha.net/recaptcha/api.js?onload=onloadRecaptchaCallback&render=explicit"
            async defer></script>
<?php endif; ?>

<?php if (get_option('captcha_type') == 'solvemedia') : ?>
    <script type="text/javascript">
        var script = document.createElement('script');
        script.type = 'text/javascript';

        if (location.protocol === 'https:') {
            script.src = 'https://api-secure.solvemedia.com/papi/challenge.ajax';
        } else {
            script.src = 'http://api.solvemedia.com/papi/challenge.ajax';
        }

        document.body.appendChild(script);
    </script>
<?php endif; ?>

<?= $this->fetch('scriptBottom') ?>
<?= get_option('footer_code'); ?>

</body>
</html>
