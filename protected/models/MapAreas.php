<?php

/**
 * This is the model class for table "oed_map_areas".
 *
 * The followings are the available columns in table 'oed_map_areas':
 * @property integer $id
 * @property integer $id_map
 * @property string $shape
 * @property string $coords
 */
class MapAreas extends CActiveRecord
{
        static $shapesRus = array(
            1 => 'Круг',
            2 => 'Прямоугольник',
            3 => 'Многоугольник',
        );
        
        static $shapesEng = array(
            1 => 'circle',
            2 => 'rect',
            3 => 'poly',
        );
    
	public function tableName()
	{
		return 'oed_map_areas';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_map, name, shape', 'required'),
			array('id_map', 'numerical', 'integerOnly'=>true),
			array('shape, name', 'length', 'max'=>255),
			array('shape', 'in', 'range'=>array(1,2,3)),
                    	array('coords', 'match', 'pattern'=>'/^[\d\s,]*$/iu'),
			array('id, id_map, shape, coords', 'safe', 'on'=>'search'),
		);
	}

	public function relations()
	{
            return array(
            );
	}

	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'id_map' => 'Id Map',
			'shape' => 'Shape',
			'coords' => 'Coords',
		);
	}

	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('id_map',$this->id_map);
		$criteria->compare('shape',$this->shape,true);
		$criteria->compare('coords',$this->coords,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
