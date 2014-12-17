<?php

class m141217_100343_change_base extends CDbMigration
{
    public function up()
    {
        $this->addColumn('oed_users', 'send_mailing', "TINYINT(1) NOT NULL DEFAULT '1'");
        $this->addColumn('oed_users', 'unsubscribe_key', 'VARCHAR(32)');
    }

    public function down()
    {
        $this->dropColumn('oed_users', 'send_mailing');
        $this->dropColumn('oed_users', 'unsubscribe_key');
    }
}