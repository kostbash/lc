<?php

class m140811_172812_create_table_students_of_teacher extends CDbMigration
{
	public function up()
	{
            $this->createTable('oed_students_of_teacher', array(
                'id' => 'pk',
                'id_teacher' => "int(11) NOT NULL",
                'id_student' => "int(11) NOT NULL",
                'student_name' => "varchar(255) NOT NULL",
                'student_surname' => "varchar(255) NOT NULL",
                'status' => "boolean NOT NULL DEFAULT 0",
            ));
	}

	public function down()
	{
            $this->dropTable('oed_students_of_teacher');
	}

	/*
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	}
  `id_teacher` int(11) NOT NULL,
  `id_student` int(11) NOT NULL,
  `student_name` varchar(255) NOT NULL,
  `student_surname` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0'
	public function safeDown()
	{
	}
	*/
}