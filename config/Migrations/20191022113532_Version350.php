<?php

use Migrations\AbstractMigration;

class Version350 extends AbstractMigration
{
    public $autoId = false;

    public function up()
    {
        $this->execute("SET SESSION sql_mode = ''");

        $table_prefix = $this->getAdapter()->getOption('table_prefix');

        $this->table('i18n')
            ->changeColumn('model', 'string', [
                'default' => null,
                'limit' => 20,
                'null' => false,
            ])
            ->changeColumn('field', 'string', [
                'default' => null,
                'limit' => 20,
                'null' => false,
            ])
            ->update();

        $email_smtp_tls = $this->getOption('email_smtp_tls');
        $email_smtp_host = $this->getOption('email_smtp_host');

        $email_smtp_security = 'none';

        if ($email_smtp_tls == 'true') {
            $email_smtp_security = 'tls';
        } elseif (preg_match('#^ssl://#i', $email_smtp_host)) {
            $email_smtp_security = 'ssl';
            $email_smtp_host = explode('ssl://', $email_smtp_host)[1];
            $this->execute("UPDATE `options` SET `value` = '{$email_smtp_host}' WHERE `name` = 'email_smtp_host';");
        }

        $this->execute("DELETE FROM `options` WHERE `name` = 'email_smtp_tls';");
        $this->execute("DELETE FROM `options` WHERE `name` = 'coinbase_sandbox';");

        $this->execute("UPDATE `{$table_prefix}options` SET `name` = 'reCAPTCHA_site_key' WHERE `name` = 'recaptcha_siteKey';");
        $this->execute("UPDATE `{$table_prefix}options` SET `name` = 'reCAPTCHA_secret_key' WHERE `name` = 'recaptcha_secretKey';");
        $this->execute("UPDATE `{$table_prefix}options` SET `name` = 'disallowed_domains' WHERE `name` = 'blacklisted_domains';");

        $rows = [
            [
                'name' => 'cookie_notification_bar',
                'value' => 1,
            ],
            [
                'name' => 'email_smtp_security',
                'value' => $email_smtp_security,
            ],
            [
                'name' => 'assets_cdn_url',
                'value' => '',
            ],
            [
                'name' => 'favicon_url',
                'value' => '',
            ],
            [
                'name' => 'enable_captcha_signin',
                'value' => 'no',
            ],
            [
                'name' => 'maintenance_mode',
                'value' => 0,
            ],
            [
                'name' => 'maintenance_message',
                'value' => 'The website is currently down for maintenance. We\'ll be back shortly!',
            ],
            [
                'name' => 'prevent_direct_access_multi_domains',
                'value' => 0,
            ],
            [
                'name' => 'sitemap_shortlinks',
                'value' => 0,
            ],
            [
                'name' => 'paystack_enable',
                'value' => 0,
            ],
            [
                'name' => 'paystack_secret_key',
                'value' => '',
            ],
            [
                'name' => 'paytm_enable',
                'value' => 0,
            ],
            [
                'name' => 'paytm_merchant_key',
                'value' => '',
            ],
            [
                'name' => 'paytm_merchant_mid',
                'value' => '',
            ],
            [
                'name' => 'paytm_merchant_website',
                'value' => '',
            ],
            [
                'name' => 'paytm_industry_type',
                'value' => '',
            ],
            [
                'name' => 'site_meta_title',
                'value' => get_option('site_name', ''),
            ],
            [
                'name' => 'trial_plan',
                'value' => '',
            ],
            [
                'name' => 'trial_plan_period',
                'value' => '',
            ],
        ];

        $this->table('options')
            ->insert($rows)
            ->saveData();

        $this->table('pages')
            ->addColumn('meta_title', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
                'after' => 'content',
            ])
            ->addColumn('meta_description', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
                'after' => 'meta_title',
            ])
            ->update();

        $this->table('posts')
            ->addColumn('meta_title', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
                'after' => 'description',
            ])
            ->addColumn('meta_description', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
                'after' => 'meta_title',
            ])
            ->update();

        $this->table('social_profiles')
            ->addColumn('access_token', 'blob', [
                'default' => null,
                'null' => false,
                'after' => 'provider',
            ])
            ->addColumn('username', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
                'after' => 'identifier',
            ])
            ->addColumn('full_name', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
                'after' => 'last_name',
            ])
            ->removeColumn('gender')
            ->removeColumn('profile_url')
            ->removeColumn('website_url')
            ->removeColumn('photo_url')
            ->removeColumn('display_name')
            ->removeColumn('description')
            ->removeColumn('language')
            ->removeColumn('age')
            ->removeColumn('birth_day')
            ->removeColumn('birth_month')
            ->removeColumn('birth_year')
            ->removeColumn('phone')
            ->removeColumn('address')
            ->removeColumn('country')
            ->removeColumn('region')
            ->removeColumn('city')
            ->removeColumn('zip')
            ->update();

        $this->table('remember_tokens')
            ->addColumn('id', 'biginteger', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => 20,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('selector', 'string', [
                'default' => null,
                'limit' => 12,
                'null' => true,
                'collation' => 'utf8_bin',
            ])
            ->addColumn('token', 'string', [
                'default' => null,
                'limit' => 191,
                'null' => true,
            ])
            ->addColumn('user_id', 'biginteger', [
                'default' => null,
                'limit' => 20,
                'null' => true,
                'signed' => false,
            ])
            ->addColumn('expires', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addIndex('selector', ['name' => 'idx_selector', 'unique' => true])
            ->addIndex('user_id', ['name' => 'idx_userid'])
            ->create();

        $this->table('bundles')
            ->removeIndexByName('idx_userId_bundleId')
            ->renameColumn('user_bundle_id', 'slug')
            ->changeColumn('slug', 'string', [
                'after' => 'title',
                'default' => null,
                'limit' => 191,
                'null' => true,
            ])
            ->addIndex(['user_id', 'slug'], ['name' => 'idx_userId_slug', 'unique' => true])
            ->update();

        $this->table('bundles_links')
            ->addColumn('id', 'biginteger', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => 20,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey('id')
            ->addColumn('bundle_id', 'biginteger', [
                'default' => null,
                'limit' => 20,
                'null' => true,
                'signed' => false,
            ])
            ->addColumn('link_id', 'biginteger', [
                'default' => null,
                'limit' => 20,
                'null' => true,
                'signed' => false,
            ])
            ->addIndex(['bundle_id', 'link_id'], ['name' => 'idx_bundleId_linkId', 'unique' => true])
            ->create();

        $this->execute("INSERT IGNORE INTO `{$table_prefix}bundles_links` (`bundle_id`, `link_id`) SELECT `bundle_id`, `id` FROM `{$table_prefix}links` WHERE `bundle_id` > 0;");

        $this->table('users')
            ->removeColumn('last_bundle_id')
            ->update();

        $this->table('links')
            ->removeColumn('bundle_id')
            ->removeIndexByName('idx_userid_status_type')
            ->addColumn('url_hash', 'string', [
                'after' => 'url',
                'default' => null,
                'limit' => 40,
                'null' => true,
            ])
            ->update();

        $this->execute('ALTER TABLE `links` CHANGE `url` ' .
            '`url` TEXT CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL;');

        $this->execute("UPDATE `links` SET `url_hash` = SHA1(`url`) WHERE `url_hash` IS NULL;");

        $this->table('links')
            ->addIndex('user_id', ['name' => 'idx_userid'])
            ->addIndex('url_hash', ['name' => 'idx_urlhash'])
            ->update();
    }

    public function getOption($name)
    {
        $table_prefix = $this->getAdapter()->getOption('table_prefix');

        $row = $this->fetchRow("SELECT * FROM `{$table_prefix}options` WHERE `name` = '{$name}'");

        if (empty($row)) {
            return '';
        }

        return $row['value'];
    }

    public function down()
    {
        $this->execute("SET SESSION sql_mode = ''");

        $table_prefix = $this->getAdapter()->getOption('table_prefix');
    }
}
