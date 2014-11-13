<?php

class m141113_165435_change_base extends CDbMigration
{
    public function up()
    {
        $this->renameColumn('oed_users', 'email', 'username');
        $this->addColumn('oed_users', 'id_recovery_question', 'INT(11) NOT NULL');
        $this->addColumn('oed_users', 'recovery_answer', 'varchar(100) NOT NULL');
    }

    public function down()
    {
        $this->renameColumn('oed_users', 'username', 'email');
        $this->dropColumn('oed_users', 'id_recovery_question');
        $this->dropColumn('oed_users', 'recovery_answer');
    }
}