<?php

use Migrations\AbstractMigration;

class AddInitialData extends AbstractMigration
{
    public $autoId = false;

    public function up()
    {
        $this->execute("SET SESSION sql_mode = ''");

        $table_prefix = $this->getAdapter()->getOption('table_prefix');

        $options = $this->table('options');

        $rows = [
            [
                'option_name' => 'installed',
                'option_value' => '0',
            ],
            [
                'option_name' => 'app_version',
                'option_value' => APP_VERSION,
            ],
            [
                'option_name' => 'salt',
                'option_value' => sha1(uniqid()),
            ],
            [
                'option_name' => 'cipherSeed',
                'option_value' => mt_rand() . mt_rand() . mt_rand(),
            ],
            [
                'option_name' => 'site_name',
                'option_value' => 'Mighty URL Shortener',
            ],
            [
                'option_name' => 'site_description',
                'option_value' => '',
            ],
            [
                'option_name' => 'admin_email',
                'option_value' => '',
            ],
            [
                'option_name' => 'default_public_plan',
                'option_value' => '1',
            ],
            [
                'option_name' => 'default_member_plan',
                'option_value' => '1',
            ],
            [
                'option_name' => 'site_maintenance',
                'option_value' => 'no',
            ],
            [
                'option_name' => 'site_api',
                'option_value' => 'yes',
            ],
            [
                'option_name' => 'blacklisted_domains',
                'option_value' => '',
            ],
            [
                'option_name' => 'redirections',
                'option_value' => 'a:2:{i:0;s:1:"1";i:1;s:1:"2";}',
            ],
            [
                'option_name' => 'timer',
                'option_value' => '5',
            ],
            [
                'option_name' => 'reserved_aliases',
                'option_value' => '',
            ],
            [
                'option_name' => 'reserved_usernames',
                'option_value' => '',
            ],
            [
                'option_name' => 'google_analytics',
                'option_value' => '',
            ],
            [
                'option_name' => 'recaptcha_siteKey',
                'option_value' => '',
            ],
            [
                'option_name' => 'recaptcha_secretKey',
                'option_value' => '',
            ],
            [
                'option_name' => 'cache_disable',
                'option_value' => '0',
            ],
            [
                'option_name' => 'site_language',
                'option_value' => 'eng',
            ],
            [
                'option_name' => 'api_usage_limit',
                'option_value' => '1000',
            ],
            [
                'option_name' => 'head_code',
                'option_value' => '',
            ],
            [
                'option_name' => 'footer_code',
                'option_value' => '',
            ],
            [
                'option_name' => 'after_body_tag_code',
                'option_value' => '',
            ],
            [
                'option_name' => 'ads_area1',
                'option_value' => '<img src="https://via.placeholder.com/728x90/ffffff/808080/?text=Ads%20Area%201" style="border: 1px solid #e7e7e7;">',
            ],
            [
                'option_name' => 'ads_area2',
                'option_value' => '<img src="https://via.placeholder.com/468x60/ffffff/808080/?text=Ads%20Area%202" style="border: 1px solid #e7e7e7;">',
            ],
            [
                'option_name' => 'facebook_url',
                'option_value' => '',
            ],
            [
                'option_name' => 'twitter_url',
                'option_value' => '',
            ],
            [
                'option_name' => 'googleplus_url',
                'option_value' => '',
            ],
        ];

        $options->insert($rows);
        $options->saveData();

        $table = $this->table('plans');

        $rows = [
            'id' => 1,
            'enable' => 1,
            'title' => 'Default',
            'redirections' => 'a:2:{i:0;s:1:"1";i:1;s:1:"2";}',
            'stats' => 2,
            'api' => 1,
            'timer' => 1,
            'comments' => 1,
            'sharing' => 1,
            'feed' => 1,
            'ads_area1' => 1,
            'ads_area2' => 1,
            'updated' => date("Y-m-d H:i:s"),
            'created' => date("Y-m-d H:i:s"),
        ];
        $table->insert($rows);
        $table->saveData();

        $table = $this->table('pages');

        $rows = [
            [
                'id' => 1,
                'title' => 'Terms of Use',
                'slug' => 'terms',
                'published' => 1,
                'content' => '',
                'updated' => date("Y-m-d H:i:s"),
                'created' => date("Y-m-d H:i:s"),
            ],
            [
                'id' => 2,
                'title' => 'Privacy Policy',
                'slug' => 'privacy',
                'published' => 1,
                'content' => '',
                'updated' => date("Y-m-d H:i:s"),
                'created' => date("Y-m-d H:i:s"),
            ],
            [
                'id' => 3,
                'title' => 'DMCA',
                'slug' => 'dmca',
                'published' => 1,
                'content' => '',
                'updated' => date("Y-m-d H:i:s"),
                'created' => date("Y-m-d H:i:s"),
            ],
        ];
        $table->insert($rows);
        $table->saveData();

        $table = $this->table('users');

        $rows = [
            'id' => 1,
            'username' => 'anonymous',
            'email' => 'anonymous@' . env("HTTP_HOST", "example.com"),
            'status' => 'Active',
            'account_type' => 'Free',
            'plan_id' => 1,
            'password' => password_hash(uniqid(), PASSWORD_DEFAULT),
            'api_key' => sha1(uniqid()),
            'redirection_type' => 2,
            'country' => 'US',
            'timer' => 5,
            'updated' => date("Y-m-d H:i:s"),
            'created' => date("Y-m-d H:i:s"),
        ];
        $table->insert($rows);
        $table->saveData();
    }

    public function down()
    {
        $this->execute("SET SESSION sql_mode = ''");

        $table_prefix = $this->getAdapter()->getOption('table_prefix');

        $this->execute("TRUNCATE {$table_prefix}options;");
        $this->execute("TRUNCATE {$table_prefix}plans;");
        $this->execute("TRUNCATE {$table_prefix}pages;");
        $this->execute("TRUNCATE {$table_prefix}users;");
    }
}
