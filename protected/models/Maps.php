<?php

/**
 * This is the model class for table "oed_maps".
 *
 * The followings are the available columns in table 'oed_maps':
 * @property integer $id
 * @property string $name
 * @property string $url_image
 * @property integer $id_user
 */
class Maps extends CActiveRecord
{
        public $imageFile;
        static $imageAccessFormats = array('gif', 'jpg', 'jpeg', 'png');
        public $id_tag;
        
	public function tableName()
	{
		return 'oed_maps';
	}

	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, url_image, id_user, is_link', 'required'),
			array('id_user, id_tag', 'numerical', 'integerOnly'=>true),
			array('is_link', 'boolean'),
                        array('url_image', 'fileTypes'),
			array('name, url_image', 'length', 'max'=>255),
                        array('imageFile', 'file', 'types' => 'jpg,gif,png,jpeg', 'message'=>'Выберите файл'),
			array('id, name, url_image, id_user', 'safe', 'on'=>'search'),
		);
	}
        
	public function relations()
	{
            return array(
                'Areas' => array(self::HAS_MANY, 'MapAreas', 'id_map'),
                'Tags' => array(self::MANY_MANY, 'MapTags', 'oed_maps_and_tags(id_map, id_tag)'),
            );
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Название',
			'url_image' => 'Ссылка',
			'id_user' => 'Id User',
                        'countAreas' => 'Число областей',
                        'tagsString' => 'Теги',
                        'id_tag' => 'Тег',
		);
	}
        
        public function fileTypes($attribute, $params)
        {
            if($this->$attribute != '')
            {
                $format = substr(strrchr($this->$attribute, '.'), 1);
                if(!self::accessFormatImage($format))
                    $this->addError($attribute, "Разрешены только форматы: ".  implode(', ', self::$imageAccessFormats));
            }
        }

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('url_image',$this->url_image,true);
                if(!Yii::app()->user->checkAccess('admin'))
                {
                    $criteria->compare('id_user', Yii::app()->user->id);
                }
                
                if($this->id_tag)
                {
                    $criteria->with = array('Tags');
                    $criteria->compare('Tags.id', $this->id_tag);
                    $criteria->together = true;
                }

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

        public static function accessFormatImage($format)
        {
            $format = strtolower($format);
            return in_array($format, self::$imageAccessFormats);
        }
        
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        public function getMapImageLink()
        {
            if($this->is_link)
            {
                return $this->url_image;
            }
            else
            {
                return '/'.Yii::app()->params['MapImagesPath']."/".$this->url_image;
            }
        }
        
        public function getCountAreas()
        {
            return MapAreas::model()->countByAttributes(array('id_map'=>$this->id));
        }
        
        public function getTagsString() {
            $res = array();
            if($this->Tags)
            {
                foreach($this->Tags as $tag)
                {
                    $res[] = $tag->name;
                }
                $res = implode(', ', $res);
            } else {
                $res = 'Нет';
            }
            return $res;
        }
        
        public static function MapById($id)
        {
            $id = (int) $id;
            if(Yii::app()->user->checkAccess('admin'))
                return Maps::model()->findByPk($id);
            return Maps::model()->findByAttributes(array('id'=>$id, 'id_user'=>Yii::app()->user->id));
        }
        
        public function afterDelete() {
            
            MapsAndTags::model()->deleteAllByAttributes(array('id_map'=>$this->id));
             // удаляем предыдущую картинку
            @unlink(Yii::app()->params['MapImagesPath']."/".$this->url_image);
            parent::afterDelete();
        }
}
