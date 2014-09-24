<?php

class m140924_203904_change_base extends CDbMigration
{
	public function up()
	{
            $this->insert('oed_exercises_visuals', array(
                'id_type' => 1,
                'name' => 'Текст с пробелами',
            ));
	}

	public function down()
	{
		echo "m140924_203904_change_base does not support migration down.\n";
		return false;
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