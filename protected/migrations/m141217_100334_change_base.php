<?php

class m141217_100334_change_base extends CDbMigration
{
    public function up()
    {
        $this->createTable('oed_mail_rules', array(
            'id' => 'pk',
            'name' => "varchar(100) NOT NULL",
            'use_number' => "tinyint(3) NOT NULL",
            'interval' => "smallint(3) NOT NULL",
            'roles' => "varchar(50)",
            'passed_reg_days' => "smallint(6)",
            'unactivity_days' => "smallint(6)",
            'number_of_passed_lessons' => "smallint(6)",
            'passed_course' => "int(11)",
            'number_of_passed_courses' => "smallint(6)",
            'unpassed_check_test' => "smallint(6)",
        ));
    }

    public function down()
    {
        $this->dropTable('oed_mail_rules');
    }
}