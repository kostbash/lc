<?php

class m150112_143916_change_base extends CDbMigration
{
	public function up()
	{
            $this->addColumn('oed_exercises_list_of_answers', 'number', "SMALLINT(4) NULL");
            $this->insert('oed_exercises_types', array(
                'name' => 'Универсальный',
            ));
            $this->insert('oed_exercises_visuals', array(
                'id_type' => 8,
                'name' => 'Универсальное задание',
            ));
	}

	public function down()
	{
            $this->dropColumn('oed_exercises_list_of_answers', 'number');
	}
}