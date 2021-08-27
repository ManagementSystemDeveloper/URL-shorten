<?php

use Migrations\AbstractMigration;

class Version200 extends AbstractMigration
{
    public $autoId = false;

    public function up()
    {
        $this->execute("SET SESSION sql_mode = ''");

        $table_prefix = $this->getAdapter()->getOption('table_prefix');

        $this->execute('ALTER TABLE `' . $table_prefix . 'bundles` CHANGE `id` ' .
            '`id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT;');

        $this->table('bundles')
            ->renameColumn('name', 'title')
            ->renameColumn('updated', 'modified')
            ->changeColumn('user_id', 'biginteger', [
                'default' => '0',
                'limit' => 20,
                'null' => false,
                'signed' => false,
            ])
            ->changeColumn('user_bundle_id', 'biginteger', [
                'default' => '0',
                'limit' => 20,
                'null' => false,
                'signed' => false,
            ])
            ->changeColumn('views', 'biginteger', [
                'default' => '0',
                'limit' => 20,
                'null' => false,
                'signed' => false,
            ])
            ->removeIndex(['user_id'])
            ->addIndex(['user_id', 'user_bundle_id'], ['unique' => true, 'name' => 'idx_userId_bundleId'])
            ->update();

        $this->execute('ALTER TABLE `' . $table_prefix . 'links` CHANGE `id` ' .
            '`id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT;');

        $this->execute('ALTER TABLE `' . $table_prefix . 'links` CHANGE `alias` ' .
            '`alias` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL;');

        $this->table('links')
            ->addColumn('status', 'integer', [
                'comment' => '1=active, 2=hidden, 3=inactive',
                'default' => '1',
                'length' => 2,
                'null' => false,
            ])
            ->update();

        $this->table('links')
            ->removeColumn('timer')
            ->renameColumn('long_url', 'url')
            ->renameColumn('redirection_type', 'type')
            ->renameColumn('updated', 'modified')
            ->changeColumn('user_id', 'biginteger', [
                'default' => '0',
                'limit' => 20,
                'null' => false,
                'signed' => false,
            ])
            ->changeColumn('bundle_id', 'biginteger', [
                'after' => 'user_id',
                'default' => '0',
                'limit' => 20,
                'null' => false,
                'signed' => false,
            ])
            ->changeColumn('status', 'integer', [
                'after' => 'type',
                'comment' => '1=active, 2=hidden, 3=inactive',
                'default' => '1',
                'length' => 2,
                'null' => false,
            ])
            ->changeColumn('url', 'string', [
                'after' => 'status',
                'default' => '',
                'limit' => 2000,
                'null' => false,
            ])
            ->addColumn('domain', 'string', [
                'after' => 'url',
                'default' => '',
                'length' => 256,
                'null' => false,
            ])
            ->changeColumn('title', 'string', [
                'after' => 'alias',
                'default' => '',
                'limit' => 256,
                'null' => false,
            ])
            ->removeIndex(['user_id'])
            ->addIndex('alias', ['unique' => true, 'name' => 'idx_alias'])
            ->addIndex(['user_id', 'status', 'type'], ['name' => 'idx_userid_status_type'])
            ->addIndex('created', ['name' => 'idx_created'])
            ->addIndex('bundle_id', ['name' => 'idx_bundle_id'])
            ->update();

        $this->table('options')
            ->renameColumn('option_name', 'name')
            ->renameColumn('option_value', 'value')
            ->changeColumn('name', 'string', [
                'default' => '',
                'length' => 100,
                'null' => false,
            ])
            ->changeColumn('value', 'text', [
                'default' => null,
                'length' => 4294967295,
                'null' => false,
            ])
            ->addIndex('name', ['unique' => true, 'name' => 'idx_name'])
            ->update();

        $this->table('pages')
            ->renameColumn('updated', 'modified')
            ->changeColumn('slug', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->changeColumn('published', 'integer', [
                'default' => '0',
                'limit' => 2,
                'null' => false,
            ])
            ->changeColumn('content', 'text', [
                'default' => null,
                'limit' => 4294967295,
                'null' => true,
            ])
            ->addIndex('slug', ['name' => 'idx_slug',])
            ->update();

        $this->table('plans')
            ->removeColumn('redirections')
            ->removeColumn('sharing')
            ->removeColumn('api')
            ->renameColumn('ads_area1', 'disable_ads_area1')
            ->renameColumn('ads_area2', 'disable_ads_area2')
            ->renameColumn('updated', 'modified')
            ->changeColumn('title', 'string', [
                'after' => 'id',
                'default' => '',
                'limit' => 256,
                'null' => false,
            ])
            ->changeColumn('timer', 'integer', [
                'after' => 'disable_ads_area2',
                'default' => '5',
                'limit' => 3,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('description', 'text', [
                'after' => 'enable',
                'default' => null,
                'length' => null,
                'null' => true,
            ])
            ->addColumn('monthly_price', 'decimal', [
                'after' => 'description',
                'default' => '0.000000',
                'null' => false,
                'precision' => 10,
                'scale' => 6,
                'signed' => false,
            ])
            ->addColumn('yearly_price', 'decimal', [
                'after' => 'monthly_price',
                'default' => '0.000000',
                'null' => false,
                'precision' => 10,
                'scale' => 6,
                'signed' => false,
            ])
            ->addColumn('edit_link', 'boolean', [
                'after' => 'yearly_price',
                'default' => '0',
                'length' => null,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('edit_long_url', 'boolean', [
                'after' => 'edit_link',
                'default' => '0',
                'length' => null,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('alias', 'boolean', [
                'after' => 'edit_long_url',
                'default' => '0',
                'length' => null,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('password', 'boolean', [
                'after' => 'alias',
                'default' => '0',
                'length' => null,
                'null' => false,
                'signed' => false,
            ])
            ->changeColumn('stats', 'integer', [
                'after' => 'comments',
                'comment' => '1=no, 2= simple, 3=advanced',
                'default' => '0',
                'limit' => 2,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('api_quick', 'boolean', [
                'after' => 'stats',
                'default' => '0',
                'length' => null,
                'null' => false,
            ])
            ->addColumn('api_mass', 'boolean', [
                'after' => 'api_quick',
                'default' => '0',
                'length' => null,
                'null' => false,
            ])
            ->addColumn('api_full', 'boolean', [
                'after' => 'api_mass',
                'default' => '0',
                'length' => null,
                'null' => false,
            ])
            ->addColumn('api_developer', 'boolean', [
                'after' => 'api_full',
                'default' => '0',
                'length' => null,
                'null' => false,
            ])
            ->update();


        $this->execute("DELETE FROM `{$table_prefix}plans` WHERE `id` IN (1);");

        $plans = [
            [
                'id' => 1,
                'title' => 'Default',
                'enable' => 1,
                'description' => '',
                'monthly_price' => 0,
                'yearly_price' => 0,
                'edit_link' => 1,
                'edit_long_url' => 1,
                'alias' => 1,
                'password' => 1,
                'comments' => 1,
                'stats' => 3,
                'api_quick' => 1,
                'api_mass' => 1,
                'api_full' => 1,
                'api_developer' => 1,
                'feed' => 1,
                'disable_ads_area1' => 0,
                'disable_ads_area2' => 0,
                'timer' => 5,
                'modified' => date("Y-m-d H:i:s"),
                'created' => date("Y-m-d H:i:s"),
            ],
        ];
        $this->table('plans')
            ->insert($plans)
            ->saveData();

        $this->table('stats')
            ->rename('statistics')
            ->update();

        $this->execute('ALTER TABLE `' . $table_prefix . 'statistics` CHANGE `id` ' .
            '`id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT;');

        $this->table('statistics')
            ->changeColumn('link_id', 'biginteger', [
                'default' => '0',
                'limit' => 20,
                'null' => false,
                'signed' => false,
            ])
            ->changeColumn('user_id', 'biginteger', [
                'default' => '0',
                'limit' => 20,
                'null' => false,
                'signed' => false,
            ])
            ->changeColumn('ip', 'string', [
                'default' => '',
                'limit' => 45,
                'null' => false,
            ])
            ->changeColumn('is_mobile', 'boolean', [
                'default' => false,
                'limit' => null,
                'null' => false,
            ])
            ->changeColumn('is_tablet', 'boolean', [
                'default' => false,
                'limit' => null,
                'null' => false,
            ])
            ->changeColumn('user_agent', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->removeIndex(['user_id'])
            ->removeIndex(['link_id'])
            ->addIndex('link_id', ['name' => 'idx_linkId'])
            ->addIndex('user_id', ['name' => 'idx_userId'])
            ->addIndex('created', ['name' => 'idx_created'])
            ->addIndex('ip', ['name' => 'idx_ip'])
            ->update();

        $this->execute("UPDATE `{$table_prefix}users` SET `account_type` = '2' WHERE `account_type` = 'Free';");
        $this->execute("UPDATE `{$table_prefix}users` SET `account_type` = '1' WHERE `account_type` = 'Admin';");
        $this->execute("UPDATE `{$table_prefix}users` SET `account_type` = '3' WHERE `account_type` = 'Demo';");

        $this->execute("UPDATE `{$table_prefix}users` SET `status` = '1' WHERE `status` = 'Active';");
        $this->execute("UPDATE `{$table_prefix}users` SET `status` = '2' WHERE `status` = 'Inactive';");
        $this->execute("UPDATE `{$table_prefix}users` SET `status` = '3' WHERE `status` = 'Banned';");

        $this->execute('ALTER TABLE `' . $table_prefix . 'users` CHANGE `id` ' .
            '`id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT;');

        $this->table('users')
            ->removeColumn('change_email_key')
            ->removeColumn('country')
            ->removeColumn('timer')
            ->removeColumn('sharing')
            ->renameColumn('account_type', 'role')
            ->renameColumn('links', 'urls')
            ->renameColumn('redirection_type', 'redirect_type')
            ->renameColumn('api_key', 'api_token')
            ->renameColumn('user_activation_key', 'activation_key')
            ->renameColumn('updated', 'modified')
            ->changeColumn('status', 'integer', [
                'after' => 'id',
                'comment' => '1=active, 2=pending, 3=inactive',
                'default' => '0',
                'limit' => 2,
                'null' => false,
                'signed' => false,
            ])
            ->changeColumn('username', 'string', [
                'after' => 'status',
                'default' => '',
                'limit' => 255,
                'null' => false,
            ])
            ->changeColumn('password', 'string', [
                'after' => 'username',
                'default' => '',
                'limit' => 256,
                'null' => false,
            ])
            ->changeColumn('email', 'string', [
                'after' => 'password',
                'default' => '',
                'limit' => 255,
                'null' => false,
            ])
            ->changeColumn('temp_email', 'string', [
                'after' => 'email',
                'default' => '',
                'limit' => 255,
                'null' => false,
            ])
            ->changeColumn('last_bundle_id', 'biginteger', [
                'after' => 'role',
                'default' => '0',
                'limit' => 20,
                'null' => false,
                'signed' => false,
            ])
            ->changeColumn('plan_id', 'biginteger', [
                'after' => 'last_name',
                'default' => '0',
                'limit' => 20,
                'null' => false,
                'signed' => false,
            ])
            ->changeColumn('login_ip', 'string', [
                'after' => 'disqus_shortname',
                'default' => '',
                'limit' => 45,
                'null' => false,
            ])
            ->changeColumn('register_ip', 'string', [
                'default' => '',
                'limit' => 45,
                'null' => false,
            ])
            ->changeColumn('role', 'integer', [
                'after' => 'temp_email',
                'comment' => '1=admin, 2=member',
                'default' => '0',
                'limit' => 2,
                'null' => false,
                'signed' => false,
            ])
            ->changeColumn('api_token', 'string', [
                'after' => 'plan_id',
                'default' => '',
                'length' => 40,
                'null' => false,
            ])
            ->changeColumn('activation_key', 'string', [
                'after' => 'api_token',
                'default' => '',
                'length' => 40,
                'null' => false,
            ])
            ->changeColumn('urls', 'biginteger', [
                'after' => 'activation_key',
                'default' => '0',
                'length' => 20,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('expiration', 'datetime', [
                'after' => 'last_login',
                'default' => null,
                'length' => null,
                'null' => true,
            ])
            ->addIndex('username', ['unique' => true, 'name' => 'idx_username'])
            ->addIndex('email', ['unique' => true, 'name' => 'idx_email'])
            ->addIndex('api_token', ['unique' => true, 'name' => 'idx_apiToken'])
            ->addIndex('created', ['name' => 'idx_created'])
            ->addIndex('plan_id', ['name' => 'idx_planId'])
            ->addIndex('last_bundle_id', ['name' => 'idx_lastBundleId'])
            ->update();


        $this->table('activities')->drop()->save();

        $this->table('ads')->drop()->save();

        $this->table('announcements')
            ->addColumn('id', 'biginteger', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => 20,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('title', 'string', [
                'default' => '',
                'limit' => 256,
                'null' => false,
            ])
            ->addColumn('published', 'integer', [
                'default' => '0',
                'limit' => 2,
                'null' => false,
            ])
            ->addColumn('content', 'text', [
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
            ->addIndex(['published', 'id'], ['name' => 'idx_published_id'])
            ->create();

        $this->table('i18n')
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('locale', 'string', [
                'default' => null,
                'limit' => 6,
                'null' => false,
            ])
            ->addColumn('model', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('foreign_key', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => false,
            ])
            ->addColumn('field', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('content', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addIndex(['locale', 'model', 'foreign_key', 'field'], ['unique' => true, 'name' => 'locale'])
            ->addIndex(['model', 'foreign_key', 'field'], ['name' => 'model'])
            ->create();

        $this->table('invoices')
            ->addColumn('id', 'biginteger', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => 20,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('status', 'integer', [
                'comment' => '1=Paid, 2=Unpaid, 3=Canceled, 4=Invalid Payment, 5=Refunded',
                'default' => '0',
                'limit' => 2,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('user_id', 'biginteger', [
                'default' => '0',
                'limit' => 20,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('description', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('type', 'integer', [
                'comment' => '1 plan, 2 campaign',
                'default' => '0',
                'limit' => 2,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('rel_id', 'biginteger', [
                'default' => '0',
                'limit' => 20,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('payment_method', 'string', [
                'default' => '',
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('amount', 'decimal', [
                'default' => '0.000000',
                'null' => false,
                'precision' => 10,
                'scale' => 6,
            ])
            ->addColumn('data', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('paid_date', 'datetime', [
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
            ->addIndex('user_id', ['name' => 'idx_userId'])
            ->create();

        $this->table('posts')
            ->addColumn('id', 'biginteger', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => 20,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('title', 'string', [
                'default' => '',
                'limit' => 256,
                'null' => false,
            ])
            ->addColumn('slug', 'string', [
                'default' => '',
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('published', 'integer', [
                'default' => '0',
                'limit' => 2,
                'null' => false,
            ])
            ->addColumn('short_description', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('description', 'text', [
                'default' => null,
                'limit' => 4294967295,
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
            ->addIndex(['published', 'id'], ['name' => 'idx_published_id'])
            ->create();

        $this->table('social_profiles')
            ->addColumn('id', 'biginteger', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => 20,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('user_id', 'biginteger', [
                'default' => null,
                'limit' => 20,
                'null' => true,
            ])
            ->addColumn('provider', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('identifier', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('profile_url', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('website_url', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('photo_url', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('display_name', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('description', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('first_name', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('last_name', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('gender', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('language', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('age', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('birth_day', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('birth_month', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('birth_year', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('email', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('email_verified', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('phone', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('address', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('country', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('region', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('city', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('zip', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addIndex('user_id', ['name' => 'idx_userId'])
            ->create();

        $this->table('testimonials')
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => 11,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('name', 'string', [
                'default' => '',
                'limit' => 256,
                'null' => false,
            ])
            ->addColumn('position', 'string', [
                'default' => '',
                'limit' => 256,
                'null' => false,
            ])
            ->addColumn('image', 'string', [
                'default' => '',
                'limit' => 256,
                'null' => false,
            ])
            ->addColumn('published', 'boolean', [
                'default' => false,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('content', 'text', [
                'default' => null,
                'limit' => 4294967295,
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
            ->create();

        $rows = [
            [
                'name' => 'email_smtp_tls',
                'value' => 'false',
            ],
            [
                'name' => 'language',
                'value' => 'en_US',
            ],
            [
                'name' => 'language_direction',
                'value' => 'ltr',
            ],
            [
                'name' => 'timezone',
                'value' => 'Europe/Athens',
            ],
            [
                'name' => 'alias_min_length',
                'value' => '4',
            ],
            [
                'name' => 'alias_max_length',
                'value' => '8',
            ],
            [
                'name' => 'paypal_sandbox',
                'value' => 'no',
            ],
            [
                'name' => 'paypal_email',
                'value' => '',
            ],
            [
                'name' => 'currency_code',
                'value' => 'USD',
            ],
            [
                'name' => 'mass_shrinker_limit',
                'value' => '20',
            ],
            [
                'name' => 'currency_symbol',
                'value' => '$',
            ],
            [
                'name' => 'enable_captcha',
                'value' => 'no',
            ],
            [
                'name' => 'enable_captcha_signup',
                'value' => 'yes',
            ],
            [
                'name' => 'enable_captcha_forgot_password',
                'value' => 'yes',
            ],
            [
                'name' => 'logo_url',
                'value' => '',
            ],
            [
                'name' => 'logo_url_alt',
                'value' => '',
            ],
            [
                'name' => 'ad_member',
                'value' => '',
            ],
            [
                'name' => 'paypal_enable',
                'value' => 'no',
            ],
            [
                'name' => 'payza_enable',
                'value' => 'no',
            ],
            [
                'name' => 'payza_email',
                'value' => '',
            ],
            [
                'name' => 'account_activate_email',
                'value' => 'yes',
            ],
            [
                'name' => 'anonymous_default_redirect',
                'value' => '1',
            ],
            [
                'name' => 'member_default_redirect',
                'value' => '2',
            ],
            [
                'name' => 'enable_redirect_page',
                'value' => '1',
            ],
            [
                'name' => 'enable_redirect_direct',
                'value' => '1',
            ],
            [
                'name' => 'auth_head_code',
                'value' => '',
            ],
            [
                'name' => 'member_head_code',
                'value' => '',
            ],
            [
                'name' => 'admin_head_code',
                'value' => '',
            ],
            [
                'name' => 'link_info_public',
                'value' => 'yes',
            ],
            [
                'name' => 'link_info_member',
                'value' => 'yes',
            ],
            [
                'name' => 'coinbase_enable',
                'value' => 'no',
            ],
            [
                'name' => 'coinbase_api_key',
                'value' => '',
            ],
            [
                'name' => 'coinbase_api_secret',
                'value' => '',
            ],
            [
                'name' => 'coinbase_sandbox',
                'value' => 'no',
            ],
            [
                'name' => 'banktransfer_enable',
                'value' => 'no',
            ],
            [
                'name' => 'banktransfer_instructions',
                'value' => json_decode('"<p>Transfer the money to the bank account below<\/p>\n<table class=\"table table-striped\">\n    <tr>\n        <td>Account holder<\/td>\n        <td>----------<\/td>\n    <\/tr>\n    <tr>\n        <td>Bank Name<\/td>\n        <td>----------<\/td>\n    <\/tr>\n    <tr>\n        <td>City\/Town<\/td>\n        <td>----------<\/td>\n    <\/tr>\n    <tr>\n        <td>Country<\/td>\n        <td>----------<\/td>\n    <\/tr>\n    <tr>\n        <td>Account number<\/td>\n        <td>----------<\/td>\n    <\/tr>\n    <tr>\n        <td>SWIFT<\/td>\n        <td>----------<\/td>\n    <\/tr>\n    <tr>\n        <td>IBAN<\/td>\n        <td>----------<\/td>\n    <\/tr>\n    <tr>\n        <td>Account currency<\/td>\n        <td>----------<\/td>\n    <\/tr>\n    <tr>\n        <td>Reference<\/td>\n        <td>Invoice #[invoice_id]<\/td>\n    <\/tr>\n<\/table>"'),
            ],
            [
                'name' => 'home_shortening',
                'value' => 'yes',
            ],
            [
                'name' => 'home_shortening_register',
                'value' => 'no',
            ],
            [
                'name' => 'currency_position',
                'value' => 'before',
            ],
            [
                'name' => 'enable_captcha_contact',
                'value' => 'no',
            ],
            [
                'name' => 'site_languages',
                'value' => 'a:0:{}',
            ],
            [
                'name' => 'disable_meta_home',
                'value' => 'no',
            ],
            [
                'name' => 'disable_meta_member',
                'value' => 'no',
            ],
            [
                'name' => 'disable_meta_api',
                'value' => 'yes',
            ],
            [
                'name' => 'main_domain',
                'value' => env("HTTP_HOST", ""),
            ],
            [
                'name' => 'default_short_domain',
                'value' => '',
            ],
            [
                'name' => 'multi_domains',
                'value' => '',
            ],
            [
                'name' => 'theme',
                'value' => 'ClassicTheme',
            ],
            [
                'name' => 'webmoney_enable',
                'value' => 'no',
            ],
            [
                'name' => 'webmoney_merchant_purse',
                'value' => '',
            ],
            [
                'name' => 'social_login_facebook',
                'value' => '0',
            ],
            [
                'name' => 'social_login_facebook_app_id',
                'value' => '',
            ],
            [
                'name' => 'social_login_facebook_app_secret',
                'value' => '',
            ],
            [
                'name' => 'social_login_twitter',
                'value' => '0',
            ],
            [
                'name' => 'social_login_twitter_api_key',
                'value' => '',
            ],
            [
                'name' => 'social_login_twitter_api_secret',
                'value' => '',
            ],
            [
                'name' => 'social_login_google',
                'value' => '0',
            ],
            [
                'name' => 'social_login_google_client_id',
                'value' => '',
            ],
            [
                'name' => 'social_login_google_client_secret',
                'value' => '',
            ],
            [
                'name' => 'blog_enable',
                'value' => '0',
            ],
            [
                'name' => 'blog_comments_enable',
                'value' => '0',
            ],
            [
                'name' => 'disqus_shortname',
                'value' => '',
            ],
            [
                'name' => 'ssl_enable',
                'value' => '0',
            ],
            [
                'name' => 'google_safe_browsing_key',
                'value' => '',
            ],
            [
                'name' => 'phishtank_key',
                'value' => '',
            ],
            [
                'name' => 'close_registration',
                'value' => '0',
            ],
            [
                'name' => 'enable_captcha_shortlink_anonymous',
                'value' => '0',
            ],
            [
                'name' => 'skrill_enable',
                'value' => '0',
            ],
            [
                'name' => 'skrill_email',
                'value' => '',
            ],
            [
                'name' => 'skrill_secret_word',
                'value' => '',
            ],
            [
                'name' => 'enable_premium_membership',
                'value' => '0',
            ],
            [
                'name' => 'captcha_type',
                'value' => 'recaptcha',
            ],
            [
                'name' => 'solvemedia_challenge_key',
                'value' => '',
            ],
            [
                'name' => 'solvemedia_verification_key',
                'value' => '',
            ],
            [
                'name' => 'solvemedia_authentication_key',
                'value' => '',
            ],
        ];
        $this->table('options')
            ->insert($rows)
            ->saveData();

        $items = implode(",", [
            "'site_language'",
            "'salt'",
            "'cipherSeed'",
            "'site_maintenance'",
            "'site_api'",
            "'redirections'",
            "'google_analytics'",
            "'cache_disable'",
            "'api_usage_limit'",
            "'default_public_plan'",
            "'default_member_plan'",
            "'timer'",
        ]);
        $this->execute("DELETE FROM `{$table_prefix}options` WHERE `name` IN ({$items});");
    }

    public function down()
    {
        $this->execute("SET SESSION sql_mode = ''");

        $table_prefix = $this->getAdapter()->getOption('table_prefix');
    }
}
