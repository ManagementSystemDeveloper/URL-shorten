<?php
/**
 * @var \App\View\AppView $this
 */
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset(); ?>
    <title><?= h($this->fetch('title')); ?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?= h($this->fetch('description')); ?>">

    <?= $this->Assets->favicon() ?>

    <?php
    echo $this->Assets->css('https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css');
    //echo $this->Assets->css( 'https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap-theme.min.css' );
    if (get_option('language_direction') == 'rtl') {
        echo $this->Assets->css('https://cdn.jsdelivr.net/gh/morteza/bootstrap-rtl@3.4.0/dist/css/bootstrap-rtl.min.css');
        //echo $this->Assets->css( 'https://cdn.jsdelivr.net/gh/morteza/bootstrap-rtl@3.4.0/dist/css/bootstrap-flipped.min.css' );
    }
    echo $this->Assets->css('https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css');
    echo $this->Assets->css('https://cdn.jsdelivr.net/npm/select2@4.0.11/dist/css/select2.min.css');
    echo $this->Assets->css('https://cdn.jsdelivr.net/npm/admin-lte@2.3.11/dist/css/AdminLTE.min.css');
    echo $this->Assets->css('https://cdn.jsdelivr.net/npm/admin-lte@2.3.11/dist/css/skins/skin-blue.min.css');
    echo $this->Assets->css('app.css?ver=' . APP_VERSION);
    if (get_option('language_direction', 'ltr') == 'rtl') {
        echo $this->Assets->css('app-rtl');
    }
    echo $this->fetch('meta');
    echo $this->fetch('css');
    echo $this->fetch('script');
    ?>

    <?= $this->fetch('scriptTop') ?>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="//oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="installation  login-page">

<div class="login-box">
    <div class="login-logo">
        <?= h($this->fetch('title')); ?>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <?= $this->Flash->render() ?>

        <?= $this->fetch('content') ?>

        <hr>

        <div class="text-center">
            <?= __('Copyright &copy;') ?> <?php echo date('Y'); ?> | <?= __('Developed by') ?> <a
                href="http://www.mightyscripts.com" target="_blank">MightyScripts</a>
        </div>
    </div>
    <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<?= $this->element('js_vars'); ?>

<?= $this->Assets->script('https://cdn.jsdelivr.net/npm/jquery@3.4.1/dist/jquery.min.js'); ?>
<?= $this->Assets->script('https://cdn.jsdelivr.net/npm/jquery-ui-dist@1.12.1/jquery-ui.min.js'); ?>
<?= $this->Assets->script('https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/js/bootstrap.min.js'); ?>
<?= $this->Assets->script('https://cdn.jsdelivr.net/npm/clipboard@2.0.4/dist/clipboard.min.js'); ?>

<?= $this->Assets->script('https://cdn.jsdelivr.net/npm/select2@4.0.11/dist/js/select2.min.js'); ?>
<?= $this->Assets->script('app.js?ver=' . APP_VERSION); ?>
<?= $this->Assets->script('https://cdn.jsdelivr.net/npm/admin-lte@2.3.11/dist/js/app.min.js'); ?>
<?= $this->fetch('scriptBottom') ?>
</body>
</html>
