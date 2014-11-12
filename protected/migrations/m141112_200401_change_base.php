<?php

class m141112_200401_change_base extends CDbMigration
{
    public function up()
    {
        $this->addColumn('oed_courses', 'visible', 'TINYINT(1) NOT NULL DEFAULT 1');
        $this->createTable('oed_course_user_list', array(
            'id' => 'pk',
            'id_course' => "INT(11) NOT NULL",
            'id_student' => "INT(11) NOT NULL",
        ));
    }

    public function down()
    {
        $this->dropColumn('oed_courses', 'visible');
        $this->dropTable('oed_course_user_list');
    }
}