<?php

class m141010_181508_change_base extends CDbMigration
{
	public function up()
	{
            $this->addColumn('oed_exercises', 'id_map', 'INT NULL');
            $this->insert('oed_exercises_types', array(
                'name' => 'Hotmap',
            ));
            $this->insert('oed_exercises_visuals', array(
                'id_type' => 7,
                'name' => 'Указание',
            ));
	}

	public function down()
	{
            $this->dropColumn('oed_exercises', 'id_map');
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