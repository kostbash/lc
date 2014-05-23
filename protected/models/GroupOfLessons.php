<?php

/**
 * This is the model class for table "oed_group_of_lessons".
 *
 * The followings are the available columns in table 'oed_group_of_lessons':
 * @property integer $id
 * @property string $name
 */
class GroupOfLessons extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'oed_group_of_lessons';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
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
                    'LessonsRaw'=>array(self::MANY_MANY, 'Lessons', 'oed_group_and_lessons(id_group, id_lesson)', 'order'=>'LessonsRaw_LessonsRaw.order ASC'),
                    'GroupAndLessons'=>array(self::HAS_MANY, 'GroupAndLessons', 'id_group', 'order'=>'GroupAndLessons.order ASC'),
                    'Courses'=>array(self::MANY_MANY, 'Courses', 'oed_course_and_lesson_group(id_group_lesson, id_course)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Название группы',
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
	 * @return GroupOfLessons the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        public function getLessons() {
            $mass = $this->LessonsRaw;
            $mass[] = new Lessons();
            return $mass;
        }
        
        public function getHtmlForCourse($active = false)
        {
            $exerciseGroupHtml = '';
            $lessonHtml = '';
            foreach($this->LessonsRaw as $lesson)
            {
                $html = $lesson->getHtmlForCourse($active);
                $exerciseGroupHtml .= $html['exerciseGroupHtml'];
                $lessonHtml .= $lesson->HtmlForCourse['lessonHtml'];
            }
            
            return "
            <tr class='theme' data-id='$this->id'>
                <td class='blocks-container'>
                    $exerciseGroupHtml
                </td>
                
                <td class='lessons-container'>
                    <table><tbody>$lessonHtml</tbody></table>
                </td>

                <td class='theme-name'>
                    ".CHtml::textArea("name", $this->name, array('id'=>false, 'class'=>'form-control'))."
                    ".CHtml::link("<i class='glyphicon glyphicon-remove'></i>", array("/admin/groupoflessons/delete", 'id'=>$this->id))."    
                </td>
             </tr>";
        }
}
