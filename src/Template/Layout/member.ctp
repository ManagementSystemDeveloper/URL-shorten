<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $logged_user
 * @var \App\Model\Entity\Plan $logged_user_plan
 */
?>
<!DOCTYPE html>
<html lang="<?= locale_get_primary_language(null) ?>">
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
    if (get_option('language_direction') == 'rtl') {
        echo $this->Assets->css('app-rtl');
    }
    echo $this->fetch('meta');
    echo $this->fetch('css');
    echo $this->fetch('script');
    ?>

    <?= get_option('member_head_code'); ?>

    <?= $this->fetch('scriptTop') ?>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="//oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="<?= (get_option('language_direction') == 'rtl' ? "rtl" : "") ?> hold-transition skin-blue sidebar-mini">
<div class="wrapper">

    <!-- Main Header -->
    <header class="main-header">

        <!-- Logo -->
        <a href="<?= $this->Url->build('/'); ?>" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><?= preg_replace('/(\B.|\s+)/', '', get_option('site_name')) ?></span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><?= get_option('site_name') ?></span>
        </a>

        <!-- Header Navbar -->
        <nav class="navbar navbar-static-top" role="navigation">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only"><?= __('Toggle navigation') ?></span>
            </a>
            <!-- Navbar Right Menu -->
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">

                    <?php if (in_array($logged_user->role, [1, 3])) : ?>
                        <li class="dropdown messages-menu">
                            <!-- Menu toggle button -->
                            <a href="<?= $this->Url->build([
                                'controller' => 'Users',
                                'action' => 'dashboard',
                                'prefix' => 'admin',
                            ]); ?>">
                                <i class="fa fa-dashboard"></i> <?= __('Administration Area') ?>
                            </a>
                        </li>
                    <?php endif; ?>

                    <li class="dropdown language-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-language"></i> <?= __('Language') ?>
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <?php foreach (get_site_languages(true) as $lang) : ?>
                                <li>
                                    <?= $this->Html->link(
                                        locale_get_display_name($lang, $lang),
                                        $this->request->getPath() . '?lang=' . $lang
                                    ); ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </li>

                    <!-- User Account Menu -->
                    <li class="dropdown user user-menu">
                        <?php
                        $gravatar_md5 = md5(strtolower($logged_user->email));
                        ?>
                        <!-- Menu Toggle Button -->
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <!-- The user image in the navbar-->
                            <img src="<?= "//www.gravatar.com/avatar/" . $gravatar_md5 . "?s=160" ?>"
                                 class="user-image">
                            <!-- hidden-xs hides the username on small devices so only the image appears. -->
                            <span class="hidden-xs"><?= h($logged_user->first_name); ?></span>
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- The user image in the menu -->
                            <li class="user-header">
                                <img src="<?= "//www.gravatar.com/avatar/" . $gravatar_md5 . "?s=160" ?>"
                                     class="img-circle">

                                <p>
                                    <small><?= __('Member since') ?> <?= $logged_user->created ?></small>
                                </p>
                            </li>
                            <!-- Menu Footer-->
                            <li class="user-footer">
                                <div class="pull-left">
                                    <a href="<?= $this->Url->build([
                                        'controller' => 'Users',
                                        'action' => 'profile',
                                        'prefix' => 'member',
                                    ]); ?>" class="btn btn-default btn-flat"><?= __('Profile') ?></a>
                                </div>
                                <div class="pull-right">
                                    <a href="<?= $this->Url->build([
                                        'controller' => 'Users',
                                        'action' => 'logout',
                                        'prefix' => 'auth',
                                    ]); ?>" class="btn btn-default btn-flat"><?= __('Log out') ?></a>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </header>


    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">

        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">

            <br>

            <button type="button" class="btn btn-block btn-social btn-github btn-lg shorten-button" data-toggle="modal"
                    data-target="#myModal"><i class="fa fa-paper-plane"></i> <span><?= __("New Shorten Link") ?></span>
            </button>

            <br>

            <!-- Sidebar Menu -->
            <ul class="sidebar-menu">

                <li><a href="<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'dashboard']); ?>"><i
                            class="fa fa-dashboard"></i> <span><?= __('Dashboard') ?></span></a></li>

                <li class="treeview">
                    <a href="#"><i class="fa fa-link"></i> <span><?= __('Manage Links') ?></span> <i
                            class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        <li><a href="<?php echo $this->Url->build([
                                'controller' => 'Links',
                                'action' => 'index',
                            ]); ?>"><?= __('All Links') ?></a></li>
                        <li><a href="<?php echo $this->Url->build([
                                'controller' => 'Links',
                                'action' => 'hidden',
                            ]); ?>"><?= __('Hidden Links') ?></a></li>
                    </ul>
                </li>

                <?php if ($logged_user_plan->bundle) : ?>
                    <li class="treeview">
                        <a href="#"><i class="fa fa-tags"></i> <span><?= __('Manage Bundles') ?></span> <i
                                class="fa fa-angle-left pull-right"></i></a>
                        <ul class="treeview-menu">
                            <li><a href="<?php echo $this->Url->build([
                                    'controller' => 'Bundles',
                                    'action' => 'index',
                                ]); ?>"><?= __('All Bundles') ?></a></li>
                            <li><a href="<?php echo $this->Url->build([
                                    'controller' => 'Bundles',
                                    'action' => 'add',
                                ]); ?>"><?= __('Add Bundle') ?></a></li>
                        </ul>
                    </li>
                <?php endif; ?>

                <li class="treeview">
                    <a href="#"><i class="fa fa-bar-chart"></i> <span><?= __('Statistics') ?></span> <i
                            class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        <li><a href="<?php echo $this->Url->build([
                                'controller' => 'Statistics',
                                'action' => 'location',
                            ]); ?>"><?= __('Location') ?></a></li>
                        <li><a href="<?php echo $this->Url->build([
                                'controller' => 'Statistics',
                                'action' => 'referrer',
                            ]); ?>"><?= __('Referrer') ?></a></li>
                        <li><a href="<?php echo $this->Url->build([
                                'controller' => 'Statistics',
                                'action' => 'browser',
                            ]); ?>"><?= __('Browser') ?></a></li>
                        <li><a href="<?php echo $this->Url->build([
                                'controller' => 'Statistics',
                                'action' => 'platform',
                            ]); ?>"><?= __('Platform') ?></a></li>
                        <li><a href="<?php echo $this->Url->build([
                                'controller' => 'Statistics',
                                'action' => 'language',
                            ]); ?>"><?= __('Language') ?></a></li>
                        <li><a href="<?php echo $this->Url->build([
                                'controller' => 'Statistics',
                                'action' => 'device',
                            ]); ?>"><?= __('Device') ?></a></li>
                        <li><a href="<?php echo $this->Url->build([
                                'controller' => 'Statistics',
                                'action' => 'mobile',
                            ]); ?>"><?= __('Mobile') ?></a></li>
                    </ul>
                </li>

                <?php if (
                    $logged_user_plan->api_quick ||
                    $logged_user_plan->api_mass ||
                    $logged_user_plan->api_full ||
                    $logged_user_plan->api_developer ||
                    $logged_user_plan->bookmarklet
                ) : ?>
                    <li class="treeview">
                        <a href="#"><i class="fa fa-wrench"></i> <span><?= __('Tools') ?></span> <i
                                class="fa fa-angle-left pull-right"></i></a>
                        <ul class="treeview-menu">
                            <?php if ($logged_user_plan->api_quick) : ?>
                                <li><a href="<?php echo $this->Url->build([
                                        'controller' => 'Tools',
                                        'action' => 'quick',
                                    ]); ?>"><?= __('Quick Link') ?></a></li>
                            <?php endif; ?>
                            <?php if ($logged_user_plan->api_mass) : ?>
                                <li><a href="<?php echo $this->Url->build([
                                        'controller' => 'Tools',
                                        'action' => 'massShrinker',
                                    ]); ?>"><?= __('Mass Shrinker') ?></a></li>
                            <?php endif; ?>
                            <?php if ($logged_user_plan->api_full) : ?>
                                <li><a href="<?php echo $this->Url->build([
                                        'controller' => 'Tools',
                                        'action' => 'full',
                                    ]); ?>"><?= __('Full Page Script') ?></a></li>
                            <?php endif; ?>
                            <?php if ($logged_user_plan->api_developer) : ?>
                                <li><a href="<?php echo $this->Url->build([
                                        'controller' => 'Tools',
                                        'action' => 'api',
                                    ]); ?>"><?= __('Developers API') ?></a></li>
                            <?php endif; ?>
                            <?php if ($logged_user_plan->bookmarklet) : ?>
                                <li><a href="<?php echo $this->Url->build([
                                        'controller' => 'Tools',
                                        'action' => 'bookmarklet',
                                    ]); ?>"><?= __('Bookmarklet') ?></a></li>
                            <?php endif; ?>
                        </ul>
                    </li>
                <?php endif; ?>

                <?php if ((bool)get_option('enable_premium_membership')) : ?>
                    <li><a href="<?php echo $this->Url->build(['controller' => 'Invoices', 'action' => 'index']); ?>"><i
                                class="fa fa-credit-card"></i> <span><?= __('Invoices') ?></span></a></li>
                <?php endif; ?>

                <li class="treeview">
                    <a href="#"><i class="fa fa-gears"></i> <span><?= __('Settings') ?></span> <i
                            class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        <li><a href="<?php echo $this->Url->build([
                                'controller' => 'Users',
                                'action' => 'profile',
                            ]); ?>"><?= __('Profile') ?></a></li>
                        <li><a href="<?php echo $this->Url->build([
                                'controller' => 'Users',
                                'action' => 'changePassword',
                            ]); ?>"><?= __('Change Password') ?></a></li>
                        <li><a href="<?php echo $this->Url->build([
                                'controller' => 'Users',
                                'action' => 'changeEmail',
                            ]); ?>"><?= __('Change Email') ?></a></li>
                    </ul>
                </li>

                <li><a href="<?php echo $this->Url->build(['controller' => 'Forms', 'action' => 'support']); ?>"><i
                            class="fa fa-life-ring"></i> <span><?= __('Support') ?></span></a></li>

                <?php if ((bool)get_option('enable_premium_membership')) : ?>
                    <li><a href="<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'plans']); ?>"><i
                                class="fa fa-refresh"></i> <span><?= __('Change Your Plan') ?></span></a></li>
                <?php endif; ?>

            </ul>
            <!-- /.sidebar-menu -->

            <?php if ((bool)get_option('enable_premium_membership')) : ?>

                <?php
                if ($logged_user->id === 1) {
                    $exp_date = __("Never");
                } else {
                    $exp_date = __("Never");
                    if (isset($logged_user->expiration)) {
                        $exp_date = $this->Time->nice($logged_user->expiration);
                    }
                }
                ?>

                <UL class="sidebar-menu">
                    <li>
                        <a><i class="fa fa-user-circle text-aqua"></i>
                            <span><b><?= __("Current Plan") ?></b><br>
                                <?= h($logged_user_plan->title) ?>
                            </span>
                        </a>
                    </li>
                    <li>
                        <a><i class="fa fa-clock-o text-aqua"></i>
                            <span><b><?= __("Expiration Date") ?></b><br>
                                <?= $exp_date ?>
                            </span>
                        </a>
                    </li>
                </UL>
                <?php if (isset($logged_user->expiration) &&
                    ($this->Time->isThisWeek($logged_user->expiration) || $this->Time->isPast($logged_user->expiration))
                ) : ?>
                    <?= $this->Html->link(
                        __("Renew"),
                        ['controller' => 'Users', 'action' => 'plans'],
                        ['class' => 'btn btn-danger btn-sm']
                    ); ?>
                <?php endif; ?>
            <?php endif; ?>
        </section>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1><?= h($this->fetch('content_title')); ?></h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> <?= __('Dashboard') ?></a></li>
                <li class="active"><?= h($this->fetch('content_title')); ?></li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">

            <div class="box-short" style="margin-bottom: 10px; display: none;">
                <div class="box box-success box-solid shorten-member">
                    <div class="box-body" style="overflow: hidden;">
                        <?= $this->element('shorten_member'); ?>
                    </div>
                </div>
            </div>

            <?php if (!empty(get_option('ad_member'))) : ?>
                <div class="banner banner-member">
                    <div class="banner-inner">
                        <?= get_option('ad_member'); ?>
                    </div>
                </div>
            <?php endif; ?>

            <?= $this->Flash->render() ?>
            <?= $this->fetch('content') ?>

        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Main Footer -->
    <footer class="main-footer">
        <!-- To the right -->
        <div class="pull-right hidden-xs">

        </div>
        <!-- Default to the left -->
        <?= __('Copyright &copy;') ?> <?= h(get_option('site_name')) ?> <?= date("Y") ?>
    </footer>

    <!-- Add the sidebar's background. This div must be placed
         immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>


</div>

<?= $this->element('js_vars'); ?>

<?= $this->Assets->script('https://cdn.jsdelivr.net/npm/jquery@3.4.1/dist/jquery.min.js'); ?>
<?= $this->Assets->script('https://cdn.jsdelivr.net/npm/jquery-ui-dist@1.12.1/jquery-ui.min.js'); ?>
<?= $this->Assets->script('https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/js/bootstrap.min.js'); ?>
<?= $this->Assets->script('https://cdn.jsdelivr.net/npm/clipboard@2.0.4/dist/clipboard.min.js'); ?>

<?= $this->Assets->script('https://cdn.jsdelivr.net/npm/select2@4.0.11/dist/js/select2.min.js'); ?>
<?= $this->Assets->script('/vendor/conditionize.jquery.js?ver=' . APP_VERSION); ?>
<?= $this->Assets->script('app.js?ver=' . APP_VERSION); ?>
<?= $this->Assets->script('https://cdn.jsdelivr.net/npm/admin-lte@2.3.11/dist/js/app.min.js'); ?>

<?= $this->fetch('scriptBottom') ?>
</body>
</html>
