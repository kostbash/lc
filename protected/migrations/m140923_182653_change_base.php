<?php

class m140923_182653_change_base extends CDbMigration
{
	public function up()
	{
            $this->createTable('oed_courses_and_subjects', array(
                'id' => 'pk',
                'id_course' => "int(11) NOT NULL",
                'id_subject' => "int(11) NOT NULL",
            ));
            $this->createTable('oed_courses_and_classes', array(
                'id' => 'pk',
                'id_course' => "int(11) NOT NULL",
                'id_class' => "int(11) NOT NULL",
            ));
            $this->createTable('oed_course_needknows', array(
                'id' => 'pk',
                'id_course' => "int(11) NOT NULL",
                'name' => "VARCHAR(255) NOT NULL",
            ));
            $this->createTable('oed_course_yougets', array(
                'id' => 'pk',
                'id_course' => "int(11) NOT NULL",
                'name' => "VARCHAR(255) NOT NULL",
            ));
            $this->dropColumn('oed_courses', 'id_subject');
            $this->dropColumn('oed_courses', 'id_class');
	}

	public function down()
	{
            $this->dropTable('oed_courses_and_subjects');
            $this->dropTable('oed_courses_and_classes');
            $this->dropTable('oed_course_needknows');
            $this->dropTable('oed_course_yougets');
	}
}