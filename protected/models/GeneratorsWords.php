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
                        array('description', 'safe'),

			array('id, id_dictionary, word, translate, description, image, idsTags', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
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
			'imageLink' => 'Картинка',
		);
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
        
        public function getImageLink()
        {
            $res = '<div class="word-image-container'.$this->id.'">';
            if($this->image)
            {
                $res .= CHtml::link('Есть', "/".Yii::app()->params['WordsImagesPath']."/".$this->image, array('target'=>'_blank'))." ".CHtml::ajaxLink('<i class="glyphicon glyphicon-remove"></i>', '/admin/generatorswords/removeImage/id_word/'.$this->id, array('success'=>'function(data){$(\'.word-image-container'.$this->id.'\').html(data);updateSWFUpload();}')/*, array('onclick'=>'return confirm("Вы уверены?");',)*/);
            } else {
                $file = CHtml::fileField('ImportFile', '', array('onchange' => '$(this).hide();', 'style' => 'width:100%;', 'class'=>'upload-image'.$this->id, 'data-id'=>$this->id));
                $res .= " <a href='javascript:;' onclick='$(this).hide();$(this).next().show();return false;' id='import-button{$this->id}' style='margin-left:10px;'>Нет</a><div id='import-input{$this->id}' style='display:none;float:left; line-height:35px; margin-left:10px;'>$file</div></div>"; //"<input type=\"file\" name=\"GeneratorsWords[$data->id][image]\">"
            }
            $res .= "</div>";
            return $res;
        }
}