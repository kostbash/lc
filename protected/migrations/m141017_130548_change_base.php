<?php

class m141017_130548_change_base extends CDbMigration
{
	public function up()
	{
            $this->insert('oed_exercises_visuals', array(
                'id_type' => 5,
                'name' => 'Мешки',
            ));
            $this->createTable('oed_exercises_bags', array(
                'id' => 'pk',
                'id_exercise' => "INT(11) NOT NULL",
                'name' => "varchar(255) NOT NULL",
                'image' => "varchar(255) NULL",
            ));
	}

	public function down()
	{
            $this->dropTable('oed_exercises_visuals');
	}
}