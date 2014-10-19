<?php

class m141019_180146_change_base extends CDbMigration
{
    public function up()
    {
        $this->insert('oed_exercises_visuals', array(
            'id_type' => 5,
            'name' => 'Hotmap мешки',
        ));
        $this->insert('oed_exercises_visuals', array(
            'id_type' => 6,
            'name' => 'Hotmap',
        ));
        $this->addColumn('oed_exercises_list_of_answers', 'id_area', 'INT(11) NULL');
    }

    public function down()
    {
        $this->dropColumn('oed_exercises_list_of_answers', 'id_area');
    }
}