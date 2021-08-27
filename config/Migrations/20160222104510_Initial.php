<?php

use Migrations\AbstractMigration;

class Initial extends AbstractMigration
{
    public $autoId = false;

    public function up()
    {
        $this->execute("SET SESSION sql_mode = ''");

        $table_prefix = $this->getAdapter()->getOption('table_prefix');

        $databaseName = $this->getAdapter()->getOption('name');

        try {
            $this->execute("ALTER DATABASE `{$databaseName}` CHARACTER SET utf8 COLLATE utf8_general_ci;");
        } catch (\Exception $exception) {
        }

        $this->table('activities')
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('user_id', 'integer', [
                'default' => 0,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('type', 'string', [
                'comment' => 'Controller',
                'default' => '',
                'limit' => 256,
                'null' => false,
            ])
            ->addColumn('action', 'string', [
                'comment' => 'Action',
                'default' => '',
                'limit' => 256,
                'null' => false,
            ])
            ->addColumn('message', 'string', [
                'default' => '',
                'limit' => 256,
                'null' => false,
            ])
            ->addColumn('ip', 'string', [
                'default' => '',
                'limit' => 256,
                'null' => false,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addIndex(
                [
                    'user_id',
                ]
            )
            ->create();

        $this->table('ads')
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('type', 'string', [
                'comment' => '\'Interstitial Adverts\',\'Banner Adverts\'',
                'default' => '',
                'limit' => 256,
                'null' => false,
            ])
            ->create();

        $this->table('bundles')
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('user_id', 'integer', [
                'default' => 0,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('user_bundle_id', 'integer', [
                'default' => 0,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('name', 'string', [
                'default' => '',
                'limit' => 256,
                'null' => false,
            ])
            ->addColumn('private', 'boolean', [
                'default' => 0,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('description', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('views', 'integer', [
                'default' => 0,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('updated', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addIndex(
                [
                    'user_id',
                ]
            )
            ->create();

        $this->table('links')
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('user_id', 'integer', [
                'default' => 0,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('long_url', 'string', [
                'default' => '',
                'limit' => 2000,
                'null' => false,
            ])
            ->addColumn('alias', 'string', [
                'default' => '',
                'limit' => 50,
                'null' => false,
            ])
            ->addColumn('password', 'string', [
                'default' => '',
                'limit' => 70,
                'null' => false,
            ])
            ->addColumn('bundle_id', 'integer', [
                'default' => 0,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('redirection_type', 'integer', [
                'comment' => '0 default, 1 direct, 2 Cutom',
                'default' => 0,
                'limit' => 2,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('title', 'string', [
                'default' => '',
                'limit' => 256,
                'null' => false,
            ])
            ->addColumn('description', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('image', 'string', [
                'default' => '',
                'limit' => 256,
                'null' => false,
            ])
            ->addColumn('timer', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => true,
                'signed' => false,
            ])
            ->addColumn('clicks', 'integer', [
                'default' => 0,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('ip', 'string', [
                'default' => '',
                'limit' => 256,
                'null' => false,
            ])
            ->addColumn('updated', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addIndex(
                [
                    'user_id',
                ]
            )
            ->create();

        $this->execute('ALTER TABLE `' . $table_prefix . 'links` CHANGE `alias` ' .
            '`alias` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL;');

        $this->table('options')
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('option_name', 'string', [
                'default' => '',
                'limit' => 256,
                'null' => false,
            ])
            ->addColumn('option_value', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->create();

        $this->table('pages')
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => 10,
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
                'limit' => 256,
                'null' => false,
            ])
            ->addColumn('published', 'integer', [
                'default' => 0,
                'limit' => 2,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('content', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('updated', 'datetime', [
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

        $this->table('plans')
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('enable', 'boolean', [
                'default' => 0,
                'limit' => null,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('title', 'string', [
                'default' => '',
                'limit' => 256,
                'null' => false,
            ])
            ->addColumn('redirections', 'text', [
                'comment' => '1 -> Direct, 2-> Counter Page',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('stats', 'integer', [
                'comment' => 'no, simple, advanced',
                'default' => 0,
                'limit' => 2,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('api', 'boolean', [
                'default' => 0,
                'limit' => null,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('timer', 'boolean', [
                'default' => 0,
                'limit' => null,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('comments', 'boolean', [
                'default' => 0,
                'limit' => null,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('sharing', 'boolean', [
                'default' => 0,
                'limit' => null,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('feed', 'boolean', [
                'default' => 0,
                'limit' => null,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('ads_area1', 'boolean', [
                'default' => 0,
                'limit' => null,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('ads_area2', 'boolean', [
                'default' => 0,
                'limit' => null,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('updated', 'datetime', [
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

        $this->table('stats')
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('link_id', 'integer', [
                'default' => 0,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('user_id', 'integer', [
                'default' => 0,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('ip', 'string', [
                'default' => '',
                'limit' => 256,
                'null' => false,
            ])
            ->addColumn('continent', 'string', [
                'default' => '',
                'limit' => 256,
                'null' => false,
            ])
            ->addColumn('country', 'string', [
                'default' => '',
                'limit' => 256,
                'null' => false,
            ])
            ->addColumn('state', 'string', [
                'default' => '',
                'limit' => 256,
                'null' => false,
            ])
            ->addColumn('city', 'string', [
                'default' => '',
                'limit' => 256,
                'null' => false,
            ])
            ->addColumn('location', 'string', [
                'default' => '',
                'limit' => 256,
                'null' => false,
            ])
            ->addColumn('browser', 'string', [
                'default' => '',
                'limit' => 256,
                'null' => false,
            ])
            ->addColumn('platform', 'string', [
                'default' => '',
                'limit' => 256,
                'null' => false,
            ])
            ->addColumn('device_type', 'string', [
                'default' => '',
                'limit' => 256,
                'null' => false,
            ])
            ->addColumn('device_brand', 'string', [
                'default' => '',
                'limit' => 256,
                'null' => false,
            ])
            ->addColumn('device_name', 'string', [
                'default' => '',
                'limit' => 256,
                'null' => false,
            ])
            ->addColumn('is_mobile', 'integer', [
                'default' => 0,
                'limit' => 3,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('is_tablet', 'integer', [
                'default' => 0,
                'limit' => 3,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('language', 'string', [
                'default' => '',
                'limit' => 256,
                'null' => false,
            ])
            ->addColumn('timezone', 'string', [
                'default' => '',
                'limit' => 256,
                'null' => false,
            ])
            ->addColumn('referer_domain', 'string', [
                'default' => '',
                'limit' => 256,
                'null' => false,
            ])
            ->addColumn('referer', 'string', [
                'default' => '',
                'limit' => 256,
                'null' => false,
            ])
            ->addColumn('user_agent', 'string', [
                'default' => '',
                'limit' => 256,
                'null' => false,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addIndex(
                [
                    'link_id',
                ]
            )
            ->addIndex(
                [
                    'user_id',
                ]
            )
            ->create();

        $this->table('users')
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('username', 'string', [
                'default' => '',
                'limit' => 50,
                'null' => false,
            ])
            ->addColumn('status', 'text', [
                'comment' => '\'Active\',\'Inactive\',\'Banned\'',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('account_type', 'text', [
                'comment' => '\'Free\',\'admin\'',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('plan_id', 'integer', [
                'default' => 0,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('password', 'string', [
                'default' => '',
                'limit' => 256,
                'null' => false,
            ])
            ->addColumn('first_name', 'string', [
                'default' => '',
                'limit' => 100,
                'null' => false,
            ])
            ->addColumn('last_name', 'string', [
                'default' => '',
                'limit' => 100,
                'null' => false,
            ])
            ->addColumn('email', 'string', [
                'default' => '',
                'limit' => 256,
                'null' => false,
            ])
            ->addColumn('temp_email', 'string', [
                'default' => '',
                'limit' => 256,
                'null' => false,
            ])
            ->addColumn('change_email_key', 'string', [
                'default' => '',
                'limit' => 256,
                'null' => false,
            ])
            ->addColumn('links', 'integer', [
                'default' => 0,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('country', 'string', [
                'default' => '',
                'limit' => 2,
                'null' => false,
            ])
            ->addColumn('timer', 'integer', [
                'default' => 0,
                'limit' => 3,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('feed', 'string', [
                'default' => '',
                'limit' => 256,
                'null' => false,
            ])
            ->addColumn('redirection_type', 'integer', [
                'default' => 0,
                'limit' => 2,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('disqus_shortname', 'string', [
                'default' => '',
                'limit' => 256,
                'null' => false,
            ])
            ->addColumn('sharing', 'boolean', [
                'default' => 0,
                'limit' => null,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('api_key', 'string', [
                'default' => '',
                'limit' => 256,
                'null' => false,
            ])
            ->addColumn('last_bundle_id', 'integer', [
                'default' => 0,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('user_activation_key', 'string', [
                'default' => '',
                'limit' => 256,
                'null' => false,
            ])
            ->addColumn('login_ip', 'string', [
                'default' => '',
                'limit' => 256,
                'null' => false,
            ])
            ->addColumn('register_ip', 'string', [
                'default' => '',
                'limit' => 256,
                'null' => false,
            ])
            ->addColumn('last_login', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('updated', 'datetime', [
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
    }

    public function down()
    {
        $this->execute("SET SESSION sql_mode = ''");

        $table_prefix = $this->getAdapter()->getOption('table_prefix');

        $this->dropTable('activities');
        $this->dropTable('ads');
        $this->dropTable('bundles');
        $this->dropTable('links');
        $this->dropTable('options');
        $this->dropTable('pages');
        $this->dropTable('plans');
        $this->dropTable('stats');
        $this->dropTable('users');
    }
}
