<?php
/**
 * @var \App\View\AppView $this
 * @var string $totalClicks
 * @var string $totalLinks
 * @var string $totalUsers
 */
$this->assign('title', (get_option('site_meta_title')) ?: get_option('site_name'));
$this->assign('description', get_option('site_description'));
$this->assign('content_title', get_option('site_name'));
?>

<!-- Header -->
<header class="shorten">
    <div class="container">
        <div class="intro-text">
            <div class="intro-heading wow zoomIn" data-wow-delay=".3s"><?= h(get_option('site_name')) ?></div>
            <div class="row">
                <div class="col-sm-8 col-sm-offset-2">
                    <?php if (get_option('home_shortening') == 'yes') : ?>
                        <?= $this->element('shorten'); ?>
                    <?php endif; ?>
                </div>
            </div>
            <div class="slogan">
                <?= __(
                    '{0} is a service that takes long URLs and squeezes them into fewer ' .
                    'characters to make a link that is easier to share tweet email to friends.',
                    h(get_option('site_name'))
                ); ?>
            </div>
        </div>
    </div>
</header>

<section id="features" class="home">
    <div class="container">
        <div class="section-heading wow bounceIn">
            <h1><?= __('Features') ?></h1>
            <div class="divider"></div>
            <p><?= __('All Amazing Features') ?></p>
        </div>
        <div class="row">
            <div class="col-sm-3 col-xs-12">
                <div class="about-item wow fadeInLeft" data-wow-delay="0s">
                    <i class="fa fa-bar-chart-o fa-2x"></i>
                    <h3><?= __('Advanced Analytics') ?></h3>
                    <p><?= __('Advanced Reporting & Analytics by <u>continents</u>, <u>countries</u>, ' .
                            '<u>states</u>, <u>cities</u>, <u>device type</u>, <u>device brand</u> & <u>device ' .
                            'name</u>') ?></p>
                </div>
            </div>
            <div class="col-sm-3 col-xs-12">
                <div class="about-item wow fadeInLeft" data-wow-delay="0.3s">
                    <i class="fa fa-tachometer fa-2x"></i>
                    <h3><?= __('Featured Administration Panel') ?></h3>
                    <p><?= __('Control all of the features from the administration panel with a click of a ' .
                            'button.') ?></p>
                </div>
            </div>
            <div class="col-sm-3 col-xs-12">
                <div class="about-item wow fadeInLeft" data-wow-delay="0.6s">
                    <i class="fa fa-users fa-2x"></i>
                    <h3><?= __('Unlimited Members Plans') ?></h3>
                    <p><?= __('You can control features for each plan like stats type, ads placement, ' .
                            'comments, sharing and timer.') ?></p>
                </div>
            </div>
            <div class="col-sm-3 col-xs-12">
                <div class="about-item wow fadeInLeft" data-wow-delay="0.9s">
                    <i class="fa fa-file-o fa-2x"></i>
                    <h3><?= __('Custom Redirect Page') ?></h3>
                    <p><?= __('You can custom you redirect page to feel like your website by adding ' .
                            'your logo and colors.') ?></p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-3 col-xs-12">
                <div class="about-item wow fadeInLeft" data-wow-delay="0s">
                    <i class="fa fa-lock fa-2x"></i>
                    <h3><?= __('Password Protect') ?></h3>
                    <p><?= __('Set a password to protect your links from unauthorized access.') ?></p>
                </div>
            </div>
            <div class="col-sm-3 col-xs-12">
                <div class="about-item wow fadeInLeft" data-wow-delay="0.3s">
                    <i class="fa fa-share-square-o fa-2x"></i>
                    <h3><?= __('Social Media Counts') ?></h3>
                    <p><?= __('Display social media counts for most popular networks like Facebook, Google+, ' .
                            'Pinterest, LinkedIn, StumbleUpon & Reddit') ?></p>
                </div>
            </div>
            <div class="col-sm-3 col-xs-12">
                <div class="about-item wow fadeInLeft" data-wow-delay="0.6s">
                    <i class="fa fa-tags fa-2x"></i>
                    <h3><?= __('Bundles') ?></h3>
                    <p><?= __('Bundle your links for easy access and share them with the public.') ?></p>
                </div>
            </div>
            <div class="col-sm-3 col-xs-12">
                <div class="about-item wow fadeInLeft" data-wow-delay="0.9s">
                    <i class="fa fa-list fa-2x"></i>
                    <h3><?= __('Display Website Articles') ?></h3>
                    <p><?= __('Connect your website with the custom redirect page by displaying your articles.') ?></p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-3 col-xs-12">
                <div class="about-item wow fadeInLeft" data-wow-delay="0s">
                    <i class="fa fa-comments fa-2x"></i>
                    <h3><?= __('Comments System') ?></h3>
                    <p><?= __('The Comments box lets people comment on your links.') ?></p>
                </div>
            </div>
            <div class="col-sm-3 col-xs-12">
                <div class="about-item wow fadeInLeft" data-wow-delay="0.3s">
                    <i class="fa fa-pencil-square-o fa-2x"></i>
                    <h3><?= __('Edit Created Links') ?></h3>
                    <p><?= __(
                            '%s allows you to modify the long URL behind your customized shortlinks.',
                            h(get_option('site_name'))
                        ) ?></p>
                </div>
            </div>
            <div class="col-sm-3 col-xs-12">
                <div class="about-item wow fadeInLeft" data-wow-delay="0.6s">
                    <i class="fa fa-file-text-o fa-2x"></i>
                    <h3><?= __('Unlimited Pages') ?></h3>
                    <p><?= __('You can easily add Unlimited pages easily from the admin area.') ?></p>
                </div>
            </div>
            <div class="col-sm-3 col-xs-12">
                <div class="about-item wow fadeInLeft" data-wow-delay="0.9s">
                    <i class="fa fa-code fa-2x"></i>
                    <h3><?= __('Advanced API System') ?></h3>
                    <p><?= __('API allows you to develop applications that interface with this service.') ?></p>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="stats" class="home">
    <div class="container">
        <div class="section-heading wow bounceIn">
            <h1><?= __('Stats') ?></h1>
            <div class="divider"></div>
            <p><?= __('Check Our Statistics') ?></p>
        </div>
        <div class="row">
            <div class="col-sm-4 text-center wow flipInY">
                <i class="fa fa-link fa-2x"></i>
                <span class="display-counter"><?= $totalClicks; ?></span>
                <span><?= __('Total Clicks') ?></span>
            </div>
            <div class="col-sm-4 text-center wow flipInY">
                <i class="fa fa-bar-chart-o fa-2x"></i>
                <span class="display-counter"><?= $totalLinks; ?></span>
                <span><?= __('Total URLs') ?></span>
            </div>
            <div class="col-sm-4 text-center wow flipInY">
                <i class="fa fa-users fa-2x"></i>
                <span class="display-counter"><?= $totalUsers; ?></span>
                <span><?= __('Registered Users') ?></span>
            </div>
        </div>
    </div>
</section>

<?=
$this->cell('Testimonial', [], [
    'cache' => [
        'config' => '1day',
        'key' => 'home_testimonials_' . locale_get_default(),
    ],
])
?>

<section id="contact" class="home">
    <div class="container">
        <div class="section-heading wow bounceIn">
            <h1><?php echo __('Contact Us') ?></h1>
            <div class="divider"></div>
            <p><?php echo __('Get in touch') ?></p>
        </div>
        <?= $this->element('contact'); ?>
    </div>
</section>
