<?php

/**
 * This is the model class for table "oed_exercises_bags".
 *
 * The followings are the available columns in table 'oed_exercises_bags':
 * @property integer $id
 * @property integer $id_exercise
 * @property string $name
 * @property string $image
 */
class ExercisesBags extends CActiveRecord
{
    public $imageFile;
    public $deleteImage = false;
    public function tableName()
    {
       return 'oed_exercises_bags';
    }

    public function rules()
    {
        return array(
                array('id_exercise, name', 'required'),
                array('id_exercise', 'numerical', 'integerOnly'=>true),
                array('name, image', 'length', 'max'=>255),
                array('imageFile', 'file', 'types'=>'jpg,jpeg, png, gif', 'allowEmpty'=>true),
                array('deleteImage', 'boolean'),
                array('id, id_exercise, name, image', 'safe', 'on'=>'search'),
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
                    'id_exercise' => 'Id Exercise',
                    'name' => 'Name',
                    'image' => 'Image',
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
            $criteria->compare('id_exercise',$this->id_exercise);
            $criteria->compare('name',$this->name,true);
            $criteria->compare('image',$this->image,true);

            return new CActiveDataProvider($this, array(
                    'criteria'=>$criteria,
            ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ExercisesBags the static model class
     */
    public static function model($className=__CLASS__)
    {
            return parent::model($className);
    }

    public function getImageContainer()
    {
        $res = '';
        $res .= "<div class='bag-image'>";
            if($this->image)
            {
                $res .= CHtml::link('Есть', "/".Yii::app()->params['WordsImagesPath']."/".$this->image, array('target'=>'_blank'));
                $res .= CHtml::link('<i class="glyphicon glyphicon-remove"></i>', '#', array('class'=>'remove-image'));
            } else {
                $res .= " <a href='#' class='no-image'>Нет</a>";
            }
            $res .= "<input class='hide' type='file' name='Bags[$this->id][imageFile]' />";
        $res .= "</div>";
        return $res;
    }

    public function afterDelete() {
        if($this->image)
        {
            @unlink(Yii::app()->params['WordsImagesPath']."/".$this->image);
        }
        parent::afterDelete();
    }
}
