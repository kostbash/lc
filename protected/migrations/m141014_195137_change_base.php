<?php

class m141014_195137_change_base extends CDbMigration
{
    public function up()
    {
        $this->addColumn('oed_exercises_list_of_answers', 'name', 'VARCHAR(255) NULL');
        $this->addColumn('oed_exercises_list_of_answers', 'image', 'VARCHAR(255) NULL');
        $this->insert('oed_exercises_visuals', array(
            'id_type' => 5,
            'name' => 'Hotmap предметы',
        ));
    }

    public function down()
    {
        $this->dropColumn('oed_exercises_list_of_answers', 'name');
        $this->dropColumn('oed_exercises_list_of_answers', 'image');
    }
}