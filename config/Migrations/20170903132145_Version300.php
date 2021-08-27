<?php

use Migrations\AbstractMigration;

class Version300 extends AbstractMigration
{
    public $autoId = false;

    public function up()
    {
        $this->execute("SET SESSION sql_mode = ''");

        $table_prefix = $this->getAdapter()->getOption('table_prefix');

        $rows = [
            [
                'name' => 'language_auto_redirect',
                'value' => 0
            ],
            [
                'name' => 'stripe_enable',
                'value' => 0
            ],
            [
                'name' => 'stripe_secret_key',
                'value' => ''
            ],
            [
                'name' => 'stripe_publishable_key',
                'value' => ''
            ],
            [
                'name' => 'coinpayments_enable',
                'value' => 0
            ],
            [
                'name' => 'coinpayments_public_key',
                'value' => ''
            ],
            [
                'name' => 'coinpayments_private_key',
                'value' => ''
            ],
            [
                'name' => 'coinpayments_merchant_id',
                'value' => ''
            ],
            [
                'name' => 'coinpayments_ipn_secret',
                'value' => ''
            ],
            [
                'name' => 'perfectmoney_enable',
                'value' => 0
            ],
            [
                'name' => 'perfectmoney_account',
                'value' => ''
            ],
            [
                'name' => 'perfectmoney_passphrase',
                'value' => ''
            ],
            [
                'name' => 'payeer_enable',
                'value' => 0
            ],
            [
                'name' => 'payeer_merchant_id',
                'value' => ''
            ],
            [
                'name' => 'payeer_secret_key',
                'value' => ''
            ],
            [
                'name' => 'payeer_encryption_key',
                'value' => ''
            ],
            [
                'name' => 'bitcoin_processor',
                'value' => 'coinbase'
            ],
            [
                'name' => 'links_banned_words',
                'value' => ''
            ],
            [
                'name' => 'private_service',
                'value' => 0
            ],
            [
                'name' => 'invisible_reCAPTCHA_site_key',
                'value' => ''
            ],
            [
                'name' => 'invisible_reCAPTCHA_secret_key',
                'value' => ''
            ]
        ];
        $this->table('options')
            ->insert($rows)
            ->saveData();

        $this->table('links')
            ->addColumn('smart', 'text', [
                'after' => 'url',
                'default' => null,
                'length' => null,
                'null' => true,
            ])
            ->addColumn('method', 'integer', [
                'after' => 'clicks',
                'comment' => '1=web, 2=quick, 3=mass, 4=full, 5=api, 6=bookmarklet',
                'default' => 0,
                'limit' => 2,
                'null' => false,
                'signed' => false
            ])
            ->update();

        $this->table('plans')
            ->addColumn('hidden', 'boolean', [
                'default' => 0,
                'limit' => null,
                'null' => false,
                'signed' => false,
                'after' => 'enable'
            ])
            ->addColumn('url_daily_limit', 'integer', [
                'after' => 'yearly_price',
                'default' => '0',
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('url_monthly_limit', 'integer', [
                'after' => 'url_daily_limit',
                'default' => '0',
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('delete_link', 'boolean', [
                'after' => 'password',
                'default' => 0,
                'limit' => null,
                'null' => false,
                'signed' => false
            ])
            ->addColumn('bundle', 'boolean', [
                'after' => 'delete_link',
                'default' => 1,
                'limit' => null,
                'null' => false,
                'signed' => false
            ])
            ->addColumn('bookmarklet', 'boolean', [
                'after' => 'api_developer',
                'default' => 0,
                'limit' => null,
                'null' => false,
                'signed' => false
            ])
            ->addIndex(['enable', 'hidden'], ['name' => 'idx_enable_hidden'])
            ->update();

        $this->execute("ALTER TABLE `{$table_prefix}users` CHANGE `id` " .
            "`id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT;");

        $this->execute("ALTER TABLE `{$table_prefix}i18n` CHANGE `id` " .
            "`id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT;");

        $this->execute("ALTER TABLE `{$table_prefix}links` CHANGE `clicks` " .
            "`clicks` BIGINT(20) UNSIGNED NOT NULL DEFAULT '0';");

        $this->execute("ALTER TABLE `{$table_prefix}pages` CHANGE `id` " .
            "`id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT;");

        $this->execute("ALTER TABLE `{$table_prefix}plans` CHANGE `id` " .
            "`id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT;");

        $this->execute("ALTER TABLE `{$table_prefix}testimonials` CHANGE `id` " .
            "`id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT;");
    }

    public function down()
    {
        $this->execute("SET SESSION sql_mode = ''");

        $table_prefix = $this->getAdapter()->getOption('table_prefix');
    }
}
