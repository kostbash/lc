<?php

class m141109_184106_change_base extends CDbMigration
{
    public function up()
    {
        $this->addColumn('oed_courses', 'congratulation', 'TEXT NULL');
    }

    public function down()
    {
        $this->dropColumn('oed_courses', 'congratulation');
    }
}