<?php

/**
 * This is the model class for table "oed_generators_words".
 *
 * The followings are the available columns in table 'oed_generators_words':
 * @property integer $id
 * @property integer $id_dictionary
 * @property string $word
 * @property string $translate
 * @property string $description
 * @property string $image
 */
class GeneratorsWords extends CActiveRecord
{

        public $idsTags;
    
	public function tableName()
	{
		return 'oed_generators_words';
	}
        
        static $imageAccessFormats = array('gif', 'jpg', 'jpeg', 'png');

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_dictionary, word, translate', 'required'),
			array('id_dictionary', 'numerical', 'integerOnly'=>true),
			array('word, translate, image', 'length', 'max'=>255),
			array('remote', 'boolean'),
                        array('image', 'fileTypes'),
                        array('description', 'safe'),
			array('id, id_dictionary, word, translate, description, image, idsTags', 'safe', 'on'=>'search'),
		);
	}
        
        public static function accessFormatImage($format)
        {
            $format = strtolower($format);
            return in_array($format, self::$imageAccessFormats);
        }

	public function relations()
	{
		return array(
                    'Tags' => array(self::MANY_MANY, 'GeneratorsTags', 'oed_generators_words_tags(id_word, id_tag)'),
                    'SelectedWords' => array(self::HAS_MANY, 'GeneratorsTemplatesSelectedWords', 'id_word'),
                    'Dictionary'=>array(self::BELONGS_TO, 'GeneratorsDictionaries', 'id_dictionary'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'id_dictionary' => 'Id Dictionary',
			'word' => 'Слово',
			'translate' => 'Перевод',
			'description' => 'Описание',
			'imageWithoutUpload' => 'Картинка',
			'imageWithUpload' => 'Картинка',
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

	public function search($id_template = null)
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;
		$criteria->compare('id',$this->id);
		$criteria->compare('t.id_dictionary', $this->id_dictionary);
		$criteria->compare('word', $this->word, true);
		$criteria->compare('translate', $this->translate, true);
		$criteria->compare('description', $this->description, true);
		$criteria->compare('image', $this->image, true);
                $with = array();
                if($this->idsTags)
                {
                    $idsTags = is_array($this->idsTags) ? $this->idsTags : array($this->idsTags);
                    $with[] = 'Tags';
                    $criteria->addInCondition('Tags.id', $idsTags, 'AND');
                }
                if($id_template)
                {
                    $with[] = 'SelectedWords';
                    $criteria->compare('SelectedWords.id_template', $id_template);
                }
                
                $criteria->with = $with;
                $criteria->together = true;
                
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

        public function afterDelete() {
            GeneratorsWordsTags::model()->deleteAllByAttributes(array('id_word'=>$this->id));
            parent::afterDelete();
        }
        
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
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
        
        public function getExistIdsTags()
        {
            $res = array();
            foreach($this->Tags as $tag)
            {
                $res[] = $tag->id;
            }
            return $res;
        }
        
        public function getImageWithoutUpload()
        {
            if($this->image)
            {
                $res = CHtml::link('Есть', $this->imageLink, array('target'=>'_blank'));
            } else {
                $res = 'Нет';
            }
            return $res;
        }
        
        public function getImageLink()
        {
            if($this->remote)
            {
                return $this->image;
            } else {
                return "/".Yii::app()->params['WordsImagesPath']."/".$this->image;
            }
        }

        public function getImageWithUpload()
        {
            $res = '<div class="word-image-container">';
            if($this->image)
            {
                $res .= CHtml::link('Есть', $this->imageLink, array('target'=>'_blank'));
                $res .= CHtml::link('<i class="glyphicon glyphicon-remove"></i>', array('/admin/generatorswords/removeImage', 'id_word'=>$this->id), array('class'=>'remove-image'));
            } else {
                $res .= " <a href='#' class='import-button'>Нет</a>";
                
                $res .= '<div class="upload-container">';
                    $res .= '<p style="margin-top: 0;"><b>Загрузите картинку</b></p>';
                    $res .= CHtml::fileField('ImportFile', '', array('id'=>'false', 'class'=>'upload-image'));
                    $res .= '<p><b>или укажите ссылку</b></p>';
                    $res .= CHtml::textField("GeneratorsWords[$this->id][image]", '', array('id'=>'false', 'class'=>'form-control input-sm', 'placeholder'=>'Введите прямую ссылку на картинку'));
                $res .= '</div>';
            }
            $res .= "</div>";
            return $res;
        }
}