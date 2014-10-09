<?php

class m141009_085335_change_base extends CDbMigration
{
    public function up()
    {
        $this->createTable('oed_maps', array(
            'id' => 'pk',
            'name' => "varchar(255) NOT NULL",
            'url_image' => "varchar(255) NOT NULL",
            'is_link' => "tinyint(1) NOT NULL",
            'id_user' => "INT(11) NOT NULL",
        ));
        $this->createTable('oed_maps_tags', array(
            'id' => 'pk',
            'name' => "varchar(255) NOT NULL",
        ));
        $this->createTable('oed_maps_and_tags', array(
            'id' => 'pk',
            'id_map' => "INT(11) NOT NULL",
            'id_tag' => "INT(11) NOT NULL",
        ));
        $this->createTable('oed_map_areas', array(
            'id' => 'pk',
            'id_map' => "INT(11) NOT NULL",
            'name' => "varchar(255) NOT NULL",
            'shape' => "tinyint(1) NOT NULL",
            'coords' => "text",
        ));
    }

    public function down()
    {
        $this->dropTable('oed_maps');
        $this->dropTable('oed_maps_tags');
        $this->dropTable('oed_maps_and_tags');
        $this->dropTable('oed_map_areas');
    }
}