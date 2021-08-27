<?php
/**
 * @var \App\View\AppView $this
 * @var mixed $options
 * @var array $settings
 * @var array $plans
 */
?>
<?php
$this->assign('title', __('Settings'));
$this->assign('description', '');
$this->assign('content_title', __('Settings'));
?>

<?= $this->Form->create($options, [
    'id' => 'form-settings',
    'onSubmit' => "save_settings.disabled=true; save_settings.innerHTML='" . __('Saving ...') . "'; return true;",
]); ?>

<div class="nav-tabs-custom">
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation"><a href="#general" aria-controls="general" role="tab"
                                   data-toggle="tab"><?= __('General') ?></a></li>
        <li role="presentation"><a href="#language" aria-controls="language" role="tab"
                                   data-toggle="tab"><?= __('Language') ?></a></li>
        <li role="presentation"><a href="#design" aria-controls="design" role="tab"
                                   data-toggle="tab"><?= __('Design') ?></a></li>
        <li role="presentation"><a href="#links" aria-controls="links" role="tab"
                                   data-toggle="tab"><?= __('Links') ?></a></li>
        <li role="presentation"><a href="#users" aria-controls="users" role="tab"
                                   data-toggle="tab"><?= __('Users') ?></a></li>
        <li role="presentation"><a href="#integration" aria-controls="integration" role="tab"
                                   data-toggle="tab"><?= __('Integration') ?></a></li>
        <li role="presentation"><a href="#admin-ads" aria-controls="admin-ads" role="tab"
                                   data-toggle="tab"><?= __('Admin Ads') ?></a></li>
        <li role="presentation"><a href="#captcha" aria-controls="captcha" role="tab"
                                   data-toggle="tab"><?= __('Captcha') ?></a></li>
        <li role="presentation"><a href="#security" aria-controls="security" role="tab"
                                   data-toggle="tab"><?= __('Security') ?></a></li>
        <li role="presentation"><a href="#blog" aria-controls="blog" role="tab"
                                   data-toggle="tab"><?= __('Blog') ?></a></li>
        <li role="presentation"><a href="#social" aria-controls="Social Media" role="tab"
                                   data-toggle="tab"><?= __('Social Media') ?></a></li>

    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
        <div role="tabpanel" id="general" class="tab-pane fade in active">
            <p></p>
            <div class="row">
                <div class="col-sm-2"><?= __('Site Name') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['site_name']['id'] . '.value', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'text',
                        'value' => $settings['site_name']['value'],
                        'templateVars' => ['help' => __('This is your site name as well as the site meta title.')],
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('SEO Site Meta Title') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['site_meta_title']['id'] . '.value', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'text',
                        'value' => $settings['site_meta_title']['value'],
                        'templateVars' => [
                            'help' => __('This is your site meta title. The recommended length is 50-60 characters.'),
                        ],
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Main Domain') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['main_domain']['id'] . '.value', [
                        'label' => false,
                        'placeholder' => env("HTTP_HOST", ""),
                        'required' => 'required',
                        'class' => 'form-control',
                        'type' => 'text',
                        'value' => $settings['main_domain']['value'],
                        'templateVars' => [
                            'help' => __("Main domain used for all pages expect the short link age. " .
                                "Make sure to remove the 'http' or 'https' and the trailing slash (/)!. " .
                                "Example: <b>domain.com</b>"),
                        ],
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Default Short URL Domain') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['default_short_domain']['id'] . '.value', [
                        'label' => false,
                        'placeholder' => __("Ex. domian.com"),
                        'class' => 'form-control',
                        'type' => 'text',
                        'value' => $settings['default_short_domain']['value'],
                    ]);
                    ?>
                    <span class="help-block"><?= __("Add the default domain used for the short links. " .
                            "If it is empty, the main domain will be used. Make sure to remove the 'http' or 'https' " .
                            "and the trailing slash (/)!. Example: <b>domain.com</b>") ?></span>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Multi Domains') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['multi_domains']['id'] . '.value', [
                        'label' => false,
                        'placeholder' => 'domain1.com,domain2.com',
                        'class' => 'form-control',
                        'type' => 'textarea',
                        'value' => $settings['multi_domains']['value'],
                    ]);
                    ?>
                    <span class="help-block"><?= __("Add the other domains(don't add the default short URL " .
                            "domain or the main domain) you want users to select between when short links. ex. " .
                            "<b>domain1.com,domain2.com</b> These domains should be parked/aliased to the main " .
                            "domain. Separate by comma, no spaces. Make sure to remove the 'http' or 'https' and the " .
                            "trailing slash (/)!") ?></span>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Prevent direct access to the multi domains') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['prevent_direct_access_multi_domains']['id'] . '.value',
                        [
                            'label' => false,
                            'options' => [
                                1 => __('Yes'),
                                0 => __('No'),
                            ],
                            'value' => $settings['prevent_direct_access_multi_domains']['value'],
                            'class' => 'form-control',
                        ]);
                    ?>
                    <span class="help-block">
                        <?= __("Display a warning message when directly access the multi domains.") ?>
                    </span>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Site Description') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['site_description']['id'] . '.value', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'textarea',
                        'value' => $settings['site_description']['value'],
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Maintenance Mode') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['maintenance_mode']['id'] . '.value',
                        [
                            'label' => false,
                            'options' => [
                                1 => __('Enable'),
                                0 => __('Disable'),
                            ],
                            'value' => $settings['maintenance_mode']['value'],
                            'class' => 'form-control',
                        ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Maintenance Message') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['maintenance_message']['id'] . '.value', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'textarea',
                        'value' => $settings['maintenance_message']['value'],
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Private Service') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['private_service']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            1 => __('Yes'),
                            0 => __('No'),
                        ],
                        'value' => $settings['private_service']['value'],
                        'class' => 'form-control',
                        'templateVars' => [
                            'help' => __('Only admin can use website and create accounts.'),
                        ],
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Time Zone') ?></div>
                <div class="col-sm-10">
                    <?php
                    $DateTimeZone = \DateTimeZone::listIdentifiers(DateTimeZone::ALL);
                    echo $this->Form->control('Options.' . $settings['timezone']['id'] . '.value', [
                        'label' => false,
                        'options' => array_combine($DateTimeZone, $DateTimeZone),
                        'value' => $settings['timezone']['value'],
                        //'empty'   => __( 'Choose' ),
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Display Cookie Notification Bar') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['cookie_notification_bar']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            1 => __('Yes'),
                            0 => __('No'),
                        ],
                        'value' => $settings['cookie_notification_bar']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>
        </div>

        <div role="tabpanel" id="language" class="tab-pane fade in">
            <p></p>

            <?php
            $locale = new \Cake\Filesystem\Folder(APP . 'Locale');
            $languages = $locale->subdirectories(null, false);
            ?>

            <div class="row">
                <div class="col-sm-2"><?= __('Default Language') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['language']['id'] . '.value', [
                        'label' => false,
                        'options' => array_combine($languages, $languages),
                        'value' => $settings['language']['value'],
                        //'empty'   => __( 'Choose' ),
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Site Languages') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['site_languages']['id'] . '.value', [
                        'label' => false,
                        'type' => 'select',
                        'multiple' => true,
                        'options' => array_combine($languages, $languages),
                        'value' => unserialize($settings['site_languages']['value']),
                        //'empty'   => __( 'Choose' ),
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Language Automatic Redirect') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['language_auto_redirect']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            1 => __('Yes'),
                            0 => __('No'),
                        ],
                        'value' => $settings['language_auto_redirect']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                    <span class="help-block">
                        <?= __("Automatically redirect the website visitors to browse the website based on " .
                            " their browser language if it is already available.") ?>
                    </span>
                </div>
            </div>
        </div>

        <div role="tabpanel" id="design" class="tab-pane fade in">
            <p></p>

            <?php
            $plugins_path = new \Cake\Filesystem\Folder(ROOT . '/plugins');
            $plugins = $plugins_path->subdirectories(null, false);
            $themes = [];
            foreach ($plugins as $key => $value) {
                if (preg_match('/Theme$/', $value)) {
                    $themes[$value] = $value;
                }
            }
            ?>

            <div class="row">
                <div class="col-sm-2"><?= __('Select Theme') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['theme']['id'] . '.value', [
                        'label' => false,
                        'options' => $themes,
                        'value' => $settings['theme']['value'],
                        //'empty'   => __( 'Choose' ),
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Logo URL') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['logo_url']['id'] . '.value', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'text',
                        'value' => $settings['logo_url']['value'],
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Logo URL - Alternative') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['logo_url_alt']['id'] . '.value', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'text',
                        'value' => $settings['logo_url_alt']['value'],
                    ]);
                    ?>
                    <span class="help-block"><?= __('Alternative logo used on the login page') ?></span>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Favicon URL') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['favicon_url']['id'] . '.value', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'text',
                        'value' => $settings['favicon_url']['value'],
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Assets CDN URL') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['assets_cdn_url']['id'] . '.value', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'text',
                        'value' => $settings['assets_cdn_url']['value'],
                    ]);
                    ?>
                </div>
            </div>
        </div>

        <div role="tabpanel" id="links" class="tab-pane fade in active">
            <p></p>

            <legend><?= __("Redirect Types") ?></legend>

            <div class="row">
                <div class="col-sm-2"><?= __('Enable Page Redirect') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['enable_redirect_page']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            1 => __('Yes'),
                            0 => __('No'),
                        ],
                        'value' => $settings['enable_redirect_page']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Enable Direct Redirect') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['enable_redirect_direct']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            1 => __('Yes'),
                            0 => __('No'),
                        ],
                        'value' => $settings['enable_redirect_direct']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>

            <legend><?= __("Default Redirects") ?></legend>

            <div class="row">
                <div class="col-sm-2"><?= __('Anonymous Default Redirect') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['anonymous_default_redirect']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            '1' => __('Direct Redirect'),
                            '2' => __('Page Redirect'),
                        ],
                        'value' => $settings['anonymous_default_redirect']['value'],
                        //'empty'   => __( 'Choose' ),
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Member Default Redirect') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['member_default_redirect']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            '1' => __('Direct Redirect'),
                            '2' => __('Page Redirect'),
                        ],
                        'value' => $settings['member_default_redirect']['value'],
                        //'empty'   => __( 'Choose' ),
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>

            <legend><?= __("Metadata Fetching") ?></legend>
            <p><?= __("When shortening a URL, the URL page is downloaded and title, description & image " .
                    "are fetched from this page. If you have performance issues you can disable this behaviour from " .
                    "the below options.") ?></p>

            <div class="row">
                <div class="col-sm-2"><?= __('Disable Metadata Fetching on Homepage') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['disable_meta_home']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            'yes' => __('Yes'),
                            'no' => __('No'),
                        ],
                        'value' => $settings['disable_meta_home']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Disable Metadata Fetching on Member Area') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['disable_meta_member']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            'yes' => __('Yes'),
                            'no' => __('No'),
                        ],
                        'value' => $settings['disable_meta_member']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Disable Metadata Fetching on API') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['disable_meta_api']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            'yes' => __('Yes'),
                            'no' => __('No'),
                        ],
                        'value' => $settings['disable_meta_api']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                    <span class="help-block"><?= __("This is applicable for Quick Tool, Mass Shrinker, " .
                            "Full Page Script & Developers API.") ?></span>
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-sm-2"><?= __('Make Link Info Available for Public') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['link_info_public']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            'yes' => __('Yes'),
                            'no' => __('No'),
                        ],
                        'value' => $settings['link_info_public']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Display Home URL Shortening Box') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['home_shortening']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            'yes' => __('Yes'),
                            'no' => __('No'),
                        ],
                        'value' => $settings['home_shortening']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Redirect Anonymous Users to Register') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['home_shortening_register']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            'yes' => __('Yes'),
                            'no' => __('No'),
                        ],
                        'value' => $settings['home_shortening_register']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Make Link Info Available for Members') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['link_info_member']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            'yes' => __('Yes'),
                            'no' => __('No'),
                        ],
                        'value' => $settings['link_info_member']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Mass Shrinker Limit') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['mass_shrinker_limit']['id'] . '.value', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'number',
                        'value' => $settings['mass_shrinker_limit']['value'],
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Blacklisted Domains') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['disallowed_domains']['id'] . '.value', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'textarea',
                        'value' => $settings['disallowed_domains']['value'],
                    ]);
                    ?>
                    <span class="help-block">
                        <?= __("Disallow links with certain domains from being shortened. Separate by " .
                            "comma, no spaces.") ?>
                    </span>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Reserved Aliases') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['reserved_aliases']['id'] . '.value', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'textarea',
                        'value' => $settings['reserved_aliases']['value'],
                    ]);
                    ?>
                    <span class="help-block">
                        <?= __("Disallow aliases from being used for short links. Separate by comma, no spaces.") ?>
                    </span>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Alias Min. Length') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['alias_min_length']['id'] . '.value', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'number',
                        'value' => $settings['alias_min_length']['value'],
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Alias Max. Length') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['alias_max_length']['id'] . '.value', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'number',
                        'max' => 50,
                        'value' => $settings['alias_max_length']['value'],
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Add the short links to the sitemap') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['sitemap_shortlinks']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            1 => __('Yes'),
                            0 => __('No'),
                        ],
                        'value' => $settings['sitemap_shortlinks']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>
        </div>


        <div role="tabpanel" id="users" class="tab-pane fade in">
            <p></p>

            <div class="row">
                <div class="col-sm-2"><?= __('Close Registration') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['close_registration']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            1 => __('Yes'),
                            0 => __('No'),
                        ],
                        'value' => $settings['close_registration']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Enable Paid Premium Membership') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['enable_premium_membership']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            1 => __('Yes'),
                            0 => __('No'),
                        ],
                        'value' => $settings['enable_premium_membership']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                    <span class="help-block">
                        <?= __("If enabled, your users will be able to buy a membership plan.") ?>
                    </span>
                </div>
            </div>

            <div class="row conditional"
                 data-cond-option="Options[<?= $settings['enable_premium_membership']['id'] ?>][value]"
                 data-cond-value="1">
                <div class="col-sm-2"><?= __('Trial Membership Plan') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['trial_plan']['id'] . '.value', [
                        'label' => false,
                        'options' => $plans,
                        'empty' => __('None'),
                        'value' => $settings['trial_plan']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>

            <div class="row conditional"
                 data-cond-option="Options[<?= $settings['enable_premium_membership']['id'] ?>][value]"
                 data-cond-value="1">
                <div class="col-sm-2"><?= __('Trial Membership Period') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['trial_plan_period']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            'm' => __('Month'),
                            'y' => __('Year'),
                        ],
                        'value' => $settings['trial_plan_period']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Enable Account Activation by Email') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['account_activate_email']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            'yes' => __('Yes'),
                            'no' => __('No'),
                        ],
                        'value' => $settings['account_activate_email']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Reserved Usernames') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['reserved_usernames']['id'] . '.value', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'textarea',
                        'value' => $settings['reserved_usernames']['value'],
                    ]);
                    ?>
                    <span class="help-block"><?= __('Separate by comma, no spaces.') ?></span>
                </div>
            </div>
        </div>

        <div role="tabpanel" id="integration" class="tab-pane fade in">
            <p></p>

            <div class="row">
                <div class="col-sm-2"><?= __('Front Head Code') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['head_code']['id'] . '.value', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'textarea',
                        'value' => $settings['head_code']['value'],
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Auth Head Code') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['auth_head_code']['id'] . '.value', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'textarea',
                        'value' => $settings['auth_head_code']['value'],
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Member Head Code') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['member_head_code']['id'] . '.value', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'textarea',
                        'value' => $settings['member_head_code']['value'],
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Admin Head Code') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['admin_head_code']['id'] . '.value', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'textarea',
                        'value' => $settings['admin_head_code']['value'],
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Footer Code') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['footer_code']['id'] . '.value', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'textarea',
                        'value' => $settings['footer_code']['value'],
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('After Body Tag Code') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['after_body_tag_code']['id'] . '.value', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'textarea',
                        'value' => $settings['after_body_tag_code']['value'],
                    ]);
                    ?>
                </div>
            </div>
        </div>

        <div role="tabpanel" id="admin-ads" class="tab-pane fade in">
            <p></p>

            <div class="row">
                <div class="col-sm-2"><?= __('Ads Area 1') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['ads_area1']['id'] . '.value', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'textarea',
                        'value' => $settings['ads_area1']['value'],
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Ads Area 2') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['ads_area2']['id'] . '.value', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'textarea',
                        'value' => $settings['ads_area2']['value'],
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Member Area Ad') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['ad_member']['id'] . '.value', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'textarea',
                        'value' => $settings['ad_member']['value'],
                    ]);
                    ?>
                </div>
            </div>
        </div>

        <div role="tabpanel" id="captcha" class="tab-pane fade in">
            <p></p>

            <div class="row">
                <div class="col-sm-2"><?= __('Enable Captcha') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['enable_captcha']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            'yes' => __('Yes'),
                            'no' => __('No'),
                        ],
                        'value' => $settings['enable_captcha']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Captcha Type') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['captcha_type']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            'recaptcha' => __('reCAPTCHA v2 Checkbox'),
                            'invisible-recaptcha' => __('reCAPTCHA v2 Invisible'),
                            'solvemedia' => __('Solve Media'),
                        ],
                        'value' => $settings['captcha_type']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>

            <div class="conditional" data-cond-option="Options[<?= $settings['captcha_type']['id'] ?>][value]"
                 data-cond-value="recaptcha">

                <legend><?= __('reCAPTCHA v2 Checkbox Settings') ?></legend>

                <div class="row">
                    <div class="col-sm-2"><?= __('reCAPTCHA v2 Checkbox Site key') ?></div>
                    <div class="col-sm-10">
                        <?=
                        $this->Form->control('Options.' . $settings['reCAPTCHA_site_key']['id'] . '.value', [
                            'label' => false,
                            'class' => 'form-control',
                            'type' => 'text',
                            'value' => $settings['reCAPTCHA_site_key']['value'],
                        ]);
                        ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-2"><?= __('reCAPTCHA v2 Checkbox Secret key') ?></div>
                    <div class="col-sm-10">
                        <?=
                        $this->Form->control('Options.' . $settings['reCAPTCHA_secret_key']['id'] . '.value', [
                            'label' => false,
                            'class' => 'form-control',
                            'type' => 'text',
                            'value' => $settings['reCAPTCHA_secret_key']['value'],
                        ]);
                        ?>
                    </div>
                </div>
            </div>

            <div class="conditional" data-cond-option="Options[<?= $settings['captcha_type']['id'] ?>][value]"
                 data-cond-value="invisible-recaptcha">

                <legend><?= __('reCAPTCHA v2 Invisible Settings') ?></legend>

                <div class="row">
                    <div class="col-sm-2"><?= __('reCAPTCHA v2 Invisible Site key') ?></div>
                    <div class="col-sm-10">
                        <?=
                        $this->Form->control('Options.' . $settings['invisible_reCAPTCHA_site_key']['id'] . '.value', [
                            'label' => false,
                            'class' => 'form-control',
                            'type' => 'text',
                            'value' => $settings['invisible_reCAPTCHA_site_key']['value'],
                        ]);
                        ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-2"><?= __('reCAPTCHA v2 Invisible Secret key') ?></div>
                    <div class="col-sm-10">
                        <?=
                        $this->Form->control('Options.' . $settings['invisible_reCAPTCHA_secret_key']['id'] . '.value',
                            [
                                'label' => false,
                                'class' => 'form-control',
                                'type' => 'text',
                                'value' => $settings['invisible_reCAPTCHA_secret_key']['value'],
                            ]);
                        ?>
                    </div>
                </div>
            </div>

            <div class="conditional" data-cond-option="Options[<?= $settings['captcha_type']['id'] ?>][value]"
                 data-cond-value="solvemedia">

                <legend><?= __('Solve Media Settings') ?></legend>

                <div class="row">
                    <div class="col-sm-2"><?= __('Solve Media Challenge Key') ?></div>
                    <div class="col-sm-10">
                        <?=
                        $this->Form->control('Options.' . $settings['solvemedia_challenge_key']['id'] . '.value', [
                            'label' => false,
                            'class' => 'form-control',
                            'type' => 'text',
                            'value' => $settings['solvemedia_challenge_key']['value'],
                        ]);
                        ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-2"><?= __('Solve Media Verification Key') ?></div>
                    <div class="col-sm-10">
                        <?=
                        $this->Form->control('Options.' . $settings['solvemedia_verification_key']['id'] . '.value', [
                            'label' => false,
                            'class' => 'form-control',
                            'type' => 'text',
                            'value' => $settings['solvemedia_verification_key']['value'],
                        ]);
                        ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-2"><?= __('Solve Media Authentication Hash Key') ?></div>
                    <div class="col-sm-10">
                        <?=
                        $this->Form->control('Options.' . $settings['solvemedia_authentication_key']['id'] . '.value', [
                            'label' => false,
                            'class' => 'form-control',
                            'type' => 'text',
                            'value' => $settings['solvemedia_authentication_key']['value'],
                        ]);
                        ?>
                    </div>
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-sm-2"><?= __('Enable on Home Anonymous Short Link Box') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['enable_captcha_shortlink_anonymous']['id'] . '.value',
                        [
                            'label' => false,
                            'options' => [
                                1 => __('Yes'),
                                0 => __('No'),
                            ],
                            'value' => $settings['enable_captcha_shortlink_anonymous']['value'],
                            'class' => 'form-control',
                        ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Enable on Signin Form') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['enable_captcha_signin']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            'yes' => __('Yes'),
                            'no' => __('No'),
                        ],
                        'value' => $settings['enable_captcha_signin']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Enable on Signup Form') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['enable_captcha_signup']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            'yes' => __('Yes'),
                            'no' => __('No'),
                        ],
                        'value' => $settings['enable_captcha_signup']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Enable on Forgot Password Form') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['enable_captcha_forgot_password']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            'yes' => __('Yes'),
                            'no' => __('No'),
                        ],
                        'value' => $settings['enable_captcha_forgot_password']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Enable on Contact Form') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['enable_captcha_contact']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            'yes' => __('Yes'),
                            'no' => __('No'),
                        ],
                        'value' => $settings['enable_captcha_contact']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>
        </div>

        <div role="tabpanel" id="security" class="tab-pane fade in">
            <p></p>

            <div class="row">
                <div class="col-sm-2"><?= __('Enable SSL Integration') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['ssl_enable']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            1 => __('Yes'),
                            0 => __('No'),
                        ],
                        'value' => $settings['ssl_enable']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                    <span class="help-block">
                        <?= __("You should install SSL into your website before enable SSL integration. " .
                            "For more information about SSL, pleask ask your hosting company.") ?>
                    </span>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Enable https for Short links') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['https_shortlinks']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            1 => __('Yes'),
                            0 => __('No'),
                        ],
                        'value' => $settings['https_shortlinks']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                    <span class="help-block">
                        <?= __('You should install SSL into your website before enable this option.') ?>
                    </span>
                </div>
            </div>


            <div class="row">
                <div class="col-sm-2"><?= __('Google Safe Browsing API Key') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['google_safe_browsing_key']['id'] . '.value', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'text',
                        'value' => $settings['google_safe_browsing_key']['value'],
                    ]);
                    ?>
                    <span class="help-block">
                        <?= __(
                            'You can get your key from <a href="{0}" target="_blank">here</a>.',
                            'https://developers.google.com/safe-browsing/v4/get-started'
                        )
                        ?></span>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('PhishTank API key') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['phishtank_key']['id'] . '.value', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'text',
                        'value' => $settings['phishtank_key']['value'],
                    ]);
                    ?>
                    <span class="help-block"><?= __(
                            'You can get your key from <a href="{0}" target="_blank">here</a>.',
                            'https://www.phishtank.com/api_register.php'
                        ) ?></span>
                </div>
            </div>

        </div>

        <div role="tabpanel" id="blog" class="tab-pane fade in">
            <p></p>
            <div class="row">
                <div class="col-sm-2"><?= __('Enable Blog') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['blog_enable']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            1 => __('Yes'),
                            0 => __('No'),
                        ],
                        'value' => $settings['blog_enable']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Enable Comments') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['blog_comments_enable']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            1 => __('Yes'),
                            0 => __('No'),
                        ],
                        'value' => $settings['blog_comments_enable']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Disqus Shortname') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['disqus_shortname']['id'] . '.value', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'text',
                        'value' => $settings['disqus_shortname']['value'],
                    ]);
                    ?>
                    <span class="help-block">
                        <?= __("To display comment box, you must create an account at Disqus website by " .
                            "signing up from <a href='https://disqus.com/profile/signup/' target='_blank'>here</a> " .
                            "then add your website their from <a href='https://disqus.com/admin/create/' " .
                            "target='_blank'>here</a> and get your shortname.") ?>
                    </span>
                </div>
            </div>
        </div>

        <div role="tabpanel" id="social" class="tab-pane fade in">
            <p></p>
            <div class="row">
                <div class="col-sm-2"><?= __('Facebook Page URL') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['facebook_url']['id'] . '.value', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'url',
                        'value' => $settings['facebook_url']['value'],
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Twitter Profile URL') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['twitter_url']['id'] . '.value', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'url',
                        'value' => $settings['twitter_url']['value'],
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Google Plus URL') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['googleplus_url']['id'] . '.value', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'url',
                        'value' => $settings['googleplus_url']['value'],
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('VKontakte URL') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['vkontakte_url']['id'] . '.value', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'url',
                        'value' => $settings['vkontakte_url']['value'],
                    ]);
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->Form->button(__('Save'), ['name' => 'save_settings', 'class' => 'btn btn-primary']); ?>
<?= $this->Form->end(); ?>

<?php $this->start('scriptBottom'); ?>
<script>
    $('.conditional').conditionize();
</script>
<?php $this->end(); ?>

