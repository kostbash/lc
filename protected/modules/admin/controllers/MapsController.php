<?php

class MapsController extends Controller
{
    public $layout='//layouts/column2';

    public function filters()
    {
            return array(
                    'accessControl', 
                    'postOnly + delete',
            );
    }

    public function accessRules()
    {
        return array(
                array('allow',
                        'actions'=>array('index', 'create', 'delete', 'update'),
                        'roles'=>array('editor'),
                ),
                array('deny',  // deny all users
                        'users'=>array('*'),
                ),
        );
    }

    public function actionCreate()
    {
            $model=new Maps;
            
            if(isset($_POST['Maps']))
            {
                $model->attributes=$_POST['Maps'];
                $model->id_user=Yii::app()->user->id;
                if(!$model->is_link)
                {
                    $imageFile = CUploadedFile::getInstanceByName("Maps[imageFile]");
                    if($imageFile)
                    {
                        $imageName = substr(md5($imageFile->name.time()),0,16);
                        $model->url_image = $imageName.'.'.$imageFile->extensionName;
                    }
                }
                else
                {
                    // заглушка для валидатора imageFile
                    $_FILES['Maps'] = array
                    (
                        'name' => array('imageFile' => '000.png'),
                        'type' => array('imageFile' => 'image/png'),
                        'tmp_name' => array('imageFile' => 'C:\\Windows\\Temp\\phpEEBF.tmp'),
                        'error' => array('imageFile' => 0),
                        'size' => array('imageFile' => 700),
                    );
                }
                if($model->save())
                {
                    foreach($_POST['Tags'] as $id_tag)
                    {
                        if(MapTags::model()->exists('id=:id_tag', array('id_tag'=>$id_tag)))
                        {
                            $newMapsAndTags = new MapsAndTags;
                            $newMapsAndTags->id_map = $model->id;
                            $newMapsAndTags->id_tag = $id_tag;
                            $newMapsAndTags->save();
                        }
                    }
                    
                    if(isset($imageFile))
                    {
                        $imageFile->saveAs(Yii::app()->params['MapImagesPath']."/".$imageName.'.'.$imageFile->extensionName);
                    }
                    $this->redirect(array('update', 'id'=>$model->id));
                }
            }

            $this->render('create',array(
                    'model'=>$model,
            ));
    }

    public function actionUpdate($id)
    {
        $model=$this->loadModel($id);
        $clone = clone $model;
        if(isset($_POST['Maps']))
        {
            //CVarDumper::dump($_POST, 10, true);die;
            $model->attributes=$_POST['Maps'];
            $model->id_user=Yii::app()->user->id;
            if(!$model->is_link)
            {
                if($_FILES['Maps']['name']['imageFile'])
                {
                    $imageFile = CUploadedFile::getInstanceByName("Maps[imageFile]");
                    if($imageFile)
                    {
                        $imageName = substr(md5($imageFile->name.time()),0,16);
                        $model->url_image = $imageName.'.'.$imageFile->extensionName;
                    }
                }
                else
                {
                    $model->url_image = $clone->url_image;
                }
            }
            
            if($model->is_link or $_FILES['Maps']['name']['imageFile']=='')
            {
                // заглушка для валидатора imageFile
                $_FILES['Maps'] = array
                (
                    'name' => array('imageFile' => '000.png'),
                    'type' => array('imageFile' => 'image/png'),
                    'tmp_name' => array('imageFile' => 'C:\\Windows\\Temp\\phpEEBF.tmp'),
                    'error' => array('imageFile' => 0),
                    'size' => array('imageFile' => 700),
                );
            }
            
            if($model->save())
            {
                foreach($_POST['DeletedAreas'] as $id_area)
                {
                    $id_area = (int) $id_area;
                    $area = MapAreas::model()->findByAttributes(array('id'=>$id_area, 'id_map'=>$model->id));
                    if($area)
                    {
                        $area->delete();
                    }
                }
                
                foreach($_POST['Areas'] as $areaAttrs)
                {
                    $id_area = (int) $areaAttrs['id'];
                    if($id_area)
                    {
                        $area = MapAreas::model()->findByAttributes(array('id'=>$id_area, 'id_map'=>$model->id));
                        if($area)
                        {
                            $area->attributes = $areaAttrs;
                            $area->save();
                        }
                    }
                    else
                    {
                        $newArea = new MapAreas;
                        $newArea->attributes = $areaAttrs;
                        $newArea->id_map = $model->id;
                        $newArea->save();
                    }
                }
                
                foreach($_POST['DeletedTags'] as $id_tag)
                {
                    $id_tag = (int) $id_tag;
                    $tag = MapsAndTags::model()->findByAttributes(array('id_tag'=>$id_tag, 'id_map'=>$model->id));
                    if($tag)
                    {
                        $tag->delete();
                    }
                }
                
                foreach($_POST['Tags'] as $id_tag)
                {
                    if(!MapsAndTags::model()->exists('id_tag=:id_tag AND id_map=:id_map', array('id_tag'=>$id_tag, 'id_map'=>$model->id)))
                    {
                        if(MapTags::model()->exists('id=:id_tag', array('id_tag'=>$id_tag)))
                        {
                            $newMapsAndTags = new MapsAndTags;
                            $newMapsAndTags->id_map = $model->id;
                            $newMapsAndTags->id_tag = $id_tag;
                            $newMapsAndTags->save();
                        }
                    }
                }
                
                if(isset($imageFile))
                {
                    $imageFile->saveAs(Yii::app()->params['MapImagesPath']."/".$imageName.'.'.$imageFile->extensionName);
                }
                if($clone->url_image !== $model->url_image)
                {
                    // удаляем предыдущую картинку
                    @unlink(Yii::app()->params['MapImagesPath']."/".$clone->url_image);
                }
                $this->redirect(array('index'));
            }
        }

        $this->render('update',array(
                'model'=>$model,
                'clone'=>$clone,
        ));
    }

    public function actionDelete($id)
    {
        $this->loadModel($id)->delete();
    }

    public function actionIndex()
    {
        $model=new Maps('search');
        $model->unsetAttributes();
        
        if(isset($_POST['Maps']))
                $model->attributes=$_POST['Maps'];

        $this->render('index',array(
                'model'=>$model,
        ));
    }

    public function loadModel($id)
    {
        $model=Maps::MapById($id);
        if($model===null)
                throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }

    protected function performAjaxValidation($model)
    {
            if(isset($_POST['ajax']) && $_POST['ajax']==='maps-form')
            {
                    echo CActiveForm::validate($model);
                    Yii::app()->end();
            }
    }
}
