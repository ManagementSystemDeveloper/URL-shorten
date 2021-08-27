<?php

use Migrations\AbstractMigration;

class Version310 extends AbstractMigration
{
    public $autoId = false;

    public function up()
    {
        $this->execute("SET SESSION sql_mode = ''");

        $table_prefix = $this->getAdapter()->getOption('table_prefix');

        $this->table('users')
            ->removeIndexByName('idx_email')
            ->addIndex('email', ['name' => 'idx_email'])
            ->update();
    }

    public function down()
    {
        $this->execute("SET SESSION sql_mode = ''");

        $table_prefix = $this->getAdapter()->getOption('table_prefix');
    }
}
