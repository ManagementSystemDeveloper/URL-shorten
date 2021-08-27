<?php

use Migrations\AbstractMigration;

class Version320 extends AbstractMigration
{
    public $autoId = false;

    public function up()
    {
        $this->execute("SET SESSION sql_mode = ''");

        $table_prefix = $this->getAdapter()->getOption('table_prefix');

        $rows = [
            [
                'name' => 'https_shortlinks',
                'value' => 0
            ]
        ];
        $this->table('options')
            ->insert($rows)
            ->saveData();
    }

    public function down()
    {
        $this->execute("SET SESSION sql_mode = ''");

        $table_prefix = $this->getAdapter()->getOption('table_prefix');
    }
}
