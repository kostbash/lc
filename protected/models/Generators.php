<?php

/**
 * This is the model class for table "oed_generators".
 *
 * The followings are the available columns in table 'oed_generators':
 * @property integer $id
 * @property string $name
 */
class Generators extends CActiveRecord
{
        const DEFAULT_VISUAL = 1;
        const DEFAULT_NUMBER_EXERCISES = 10;
        const DEFAULT_NUMBER_WORDS = 10;
        
        public static $typiesBuilding = array(
            1=>'Точный ответ: картинка-слово',
            2=>'Точный ответ: перевод-слово',
            3=>'Точный ответ: слово-перевод',
            4=>'Выбор из списка: картинка-слово',
            5=>'Выбор из списка: перевод-слово',
            6=>'Выбор из списка: слово-перевод',
            7=>'Выбор из списка: слово-тег',
            8=>'Выбор из списка: исключи лишнее',
            9=>'Сопоставление: картинка-слово',
            10=>'Сопоставление: перевод-слово',
            11=>'Сопоставление: слово-тег',
        );


        public function tableName()
	{
		return 'oed_generators';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'required'),
			array('name', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
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
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('name',$this->name,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Generators the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        function getTemplate()
        {
            $template = GeneratorsTemplates::model()->findByAttributes(array('id_generator'=>$this->id, 'id_user'=>Yii::app()->user->id));
            if(!$template)
            {
                $template = new GeneratorsTemplates;
                $template->id_user = Yii::app()->user->id;
                $template->id_generator = $this->id;
                $template->number_exercises = self::DEFAULT_NUMBER_EXERCISES;
                $template->number_words = self::DEFAULT_NUMBER_WORDS;
                $template->save(false);
            }
            return $template;
        }
        
        function saveVariables($variables=array())
        {
            $template = $this->template;
            GeneratorsTemplatesVariables::model()->deleteAllByAttributes(array('id_template'=>$template->id));
            if($variables)
            {
                $newVar = new GeneratorsTemplatesVariables;
                foreach($variables as $attributes)
                {
                    if($attributes['values_type']==1)
                    {
                        if($attributes['value_max'] > $attributes['value_min'])
                        {
                            $newVar->attributes = $attributes;
                            $newVar->values = null;
                            $newVar->id_template = $template->id;
                            $newVar->save();
                            $newVar->isNewRecord = true;
                            $newVar->id = false;
                        }
                    } elseif($attributes['values_type']==2 && $attributes['values'])
                    {
                        $newVar->attributes = $attributes;
                        $newVar->value_min = null;
                        $newVar->value_max = null;
                        $newVar->id_template = $template->id;
                        $newVar->save();
                        $newVar->isNewRecord = true;
                        $newVar->id = false;
                    }
                }
            }
        }
        
        function saveConditions($conditions=array())
        {
            $template = $this->template;
            GeneratorsTemplatesConditions::model()->deleteAllByAttributes(array('id_template'=>$template->id));
            
            if($conditions)
            {
                $newCond = new GeneratorsTemplatesConditions;
                foreach($conditions as $attributes)
                {
                        $newCond->attributes = $attributes;
                        $newCond->id_template = $template->id;
                        $newCond->save();
                        $newCond->isNewRecord = true;
                        $newCond->id = false;
                }
            }
        }
        
        function saveWrongAnswers($wrongAnswers=array())
        {
            $template = $this->template;
            GeneratorsTemplatesWrongAnswers::model()->deleteAllByAttributes(array('id_template'=>$template->id));
            if($wrongAnswers)
            {
                $newWrongAnswer = new GeneratorsTemplatesWrongAnswers();
                foreach($wrongAnswers as $wrongAnswer)
                {
                    $newWrongAnswer->wrong_answer = $wrongAnswer;
                    $newWrongAnswer->id_template = $template->id;
                    $newWrongAnswer->save();
                    $newWrongAnswer->isNewRecord = true;
                    $newWrongAnswer->id = false;
                }
            }
        }
        
        function addSelectedWords($words)
        {
            $template = $this->template;
            if($words)
            {
                $selectedWord = new GeneratorsTemplatesSelectedWords();
                foreach($words as $id_word)
                {
                    if(!GeneratorsTemplatesSelectedWords::model()->exists('id_template=:id_template AND id_word=:id_word', array('id_template'=>$template->id, 'id_word'=>$id_word)))
                    {
                        $selectedWord->id_template = $template->id;
                        $selectedWord->id_word = $id_word;
                        $selectedWord->save();
                        $selectedWord->isNewRecord = true;
                        $selectedWord->id = false;
                    }
                }
            }
        }
        
        static function ListGenerators($id=0, $type='group') {
            $gens = Generators::model()->findAll();
            $list = '';
            $id = (int) $id;
            $params = array('/admin/generators/settings');
            if($id)
            {
                $params["id_$type"] = $id;
            }
            if($gens)
            {
                foreach($gens as $gen)
                {
                    $params['id'] = $gen->id;
                    $list .= "<li data-id='$gen->id'>". CHtml::link($gen->name, $params) ."</li>";
                }
            } else {
                $list .= "<li><a href='javascript:void(0)'>Нет генераторов</a></li>";
            }
            return $list;
        }
        
        static function getVisualsForGenerator2() {
            $visuals = ExercisesVisuals::model()->findAllByAttributes(array('id'=>array(1,2,3)));
            $res = array();
            foreach($visuals as $visual)
            {
                $res[$visual->id] = "{$visual->Type->name}: $visual->name";
            }
            return $res;
        }
        
        static function getConvertStrings(array $patterns, array $replacements, $strings) {
            return preg_replace($patterns, $replacements, $strings);
        }
        
        static function executeCode($str)
        {
            return @eval("return $str;");
        }
        
}
