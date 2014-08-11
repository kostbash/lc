<?php

class m140811_180735_add_column_in_exist_table extends CDbMigration
{
	public function up()
	{
            $this->renameColumn('oed_users', 'type', 'role');
            $this->addColumn('oed_courses', 'id_editor', 'INT NOT NULL DEFAULT 0');
            $this->insert('oed_mail_templates', array(
                'template_name' => 'students_notifications',
                'template_subject' => 'Оповещение от студента',
                'template_body' => 'Новое оповещение от студента с электронным адресом "{student_email}": {text}',
            ));
	}

	public function down()
	{
            $this->renameColumn('oed_users', 'role', 'type');
            $this->dropColumn('oed_courses', 'id_editor');
	}

	/*
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	}

	public function safeDown()
	{
	}
	*/
}