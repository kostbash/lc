<?php

class m141208_171654_change_base extends CDbMigration
{
    public function up()
    {
        $this->alterColumn('oed_users', 'registration_day', 'DATETIME NOT NULL');
        $this->alterColumn('oed_users', 'last_activity', 'DATETIME NULL');
    }

    public function down()
    {
        $this->dropColumn('oed_users', 'registration_day');
        $this->dropColumn('oed_users', 'last_activity');
    }
}