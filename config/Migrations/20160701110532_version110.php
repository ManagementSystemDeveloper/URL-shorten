<?php

use Migrations\AbstractMigration;

class Version110 extends AbstractMigration
{
    public $autoId = false;

    public function up()
    {
        $this->execute("SET SESSION sql_mode = ''");

        $table_prefix = $this->getAdapter()->getOption('table_prefix');

        $options = $this->table('options');

        $rows = array(
            array(
                'option_name' => 'vkontakte_url',
                'option_value' => ''
            ),
            array(
                'option_name' => 'email_from',
                'option_value' => 'no_reply@' . env('HTTP_HOST')
            ),
            array(
                'option_name' => 'email_method',
                'option_value' => 'default'
            ),
            array(
                'option_name' => 'email_smtp_host',
                'option_value' => ''
            ),
            array(
                'option_name' => 'email_smtp_port',
                'option_value' => ''
            ),
            array(
                'option_name' => 'email_smtp_username',
                'option_value' => ''
            ),
            array(
                'option_name' => 'email_smtp_password',
                'option_value' => ''
            )
        );

        $options->insert($rows);
        $options->saveData();
    }

    public function down()
    {
        $this->execute("SET SESSION sql_mode = ''");

        $table_prefix = $this->getAdapter()->getOption('table_prefix');

        $items = implode(",", array(
            "'vkontakte_url'",
            "'email_from'",
            "'email_method'",
            "'email_smtp_host'",
            "'email_smtp_port'",
            "'email_smtp_username'",
            "'email_smtp_password'"
        ));
        $this->execute("DELETE FROM `{$table_prefix}options` WHERE `option_name` IN ({$items});");
    }
}
