<?php

class m141217_100338_change_base extends CDbMigration
{
    public function up()
    {
        $this->createTable('oed_mail_workpieces', array(
            'id' => 'pk',
            'id_user' => "INT(11) NOT NULL",
            'id_rule' => "INT(11) NOT NULL",
            'number' => "tinyint(3) NOT NULL",
            'subject' => "varchar(100)",
            'template' => "text",
            'form_date' => "datetime NOT NULL",
            'send' => "tinyint(1) NOT NULL",
        ));
    }

    public function down()
    {
        $this->dropTable('oed_mail_workpieces');
    }
}