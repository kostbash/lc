<?php

class ZFormatter extends CFormatter
{
	public function format($value,$type,$data=null, $name=null)
	{
		$method='format'.$type;
		if(method_exists($this,$method))
			return $this->$method($value,$data, $name);
		else
			throw new CException(Yii::t('yii','Unknown type "{type}".',array('{type}'=>$type)));
	}

	public function formatTextField($value, $data, $name)
	{
            if($data->isNewRecord)
		return CHtml::textField(get_class($data)."[$name]", $value, array('class'=>'form-control new-record', 'placeholder'=>'Введите название', 'data-type'=>$data->type));
            return CHtml::textField(get_class($data)."[$data->id][$name]", $value, array('class'=>'form-control update-record', 'placeholder'=>'Введите название'));
	}
        
	public function formatTextAreaSkill($value, $data, $name)
	{
            if($data->isNewRecord)
                return CHtml::textArea(get_class($data)."[$name]", $value, array('id'=>false, 'class'=>"form-control new-record", 'data-type'=>$data->type, 'placeholder'=>"Введите ".mb_strtolower($data->getAttributeLabel($name), 'UTF-8') ) );
            return CHtml::textArea(get_class($data)."[$data->id][$name]", $value, array('class'=>"form-control update-record", 'placeholder'=>"Введите ".mb_strtolower($data->getAttributeLabel($name), 'UTF-8') ) );
	}
        
	public function formatTextFieldCourse($value, $data, $name)
	{
            if($data->isNewRecord)
		return CHtml::textField(get_class($data)."[$name]", $value, array('class'=>'form-control new-record', 'placeholder'=>'Введите название нового урока'));
            return CHtml::textField(get_class($data)."[$data->id][$name]", $value, array('class'=>'form-control update-record', 'placeholder'=>'Введите название'));
	}
        
	public function formatTextArea($value, $data, $name)
	{
            if($data->isNewRecord)
                return CHtml::textArea(get_class($data)."[$name]", $value, array('id'=>false, 'class'=>"form-control new-record", 'placeholder'=>"Введите ".mb_strtolower($data->getAttributeLabel($name), 'UTF-8') ) );
            return CHtml::textArea(get_class($data)."[$data->id][$name]", $value, array('class'=>"form-control update-record", 'rows'=>3, 'placeholder'=>"Введите ".mb_strtolower($data->getAttributeLabel($name), 'UTF-8') ) );
	}
        
	public function formatForEditor($value, $data, $name)
	{
            if($data->isNewRecord)
                return CHtml::textArea(get_class($data)."[$name]", $value, array('id'=>false, 'class'=>"form-control new-record", 'placeholder'=>"Введите ".mb_strtolower($data->getAttributeLabel($name), 'UTF-8') ) );
            $return = CHtml::hiddenField(get_class($data)."[$data->id][$name]", $value);
            $return .= "<div class='for-editor-field' title='Нажмите, чтобы открыть редактор'>$value</div>";
            return $return;
	}
        
        // выводится на странице умений
	public function formatLabelSkill($value, $data, $name)
	{
            $output = '<div class="inputs-mini">';
            foreach($value as $key => $item)
            {
                $output .= '<div class="input-mini-container clearfix">';
                $output .= '<p class="name">'.$item->name.'</p>';
                $output .= CHtml::link('&times;', array('/admin/relationskills/delete', 'id'=>$data->id, 'id2'=> $item->id), array('class'=>'delete close'));
                $output .= '</div>';
            }
                  $output .= '<div class="input-group mydrop">';
                    $output .= CHtml::textField("newSkill-$data->id", '', array('placeholder'=>'Введите название', 'class'=>'form-control', 'data-id'=>$data->id));
                    $output .= '<div class="input-group-btn">';
                      $output .= '<button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" tabindex="-1">';
                        $output .= '<span class="caret"></span>';
                      $output .= '</button>';
                      $output .= '<ul class="dropdown-menu" role="menu">';
                      $output .= '</ul>';
                    $output .= '</div>';
                  $output .= '</div>';
            $output .= '</div>';
            return $output;
	}
        
	public function formatlabelCourseLessonSkill($value, $data, $name)
	{
            $output = '<div class="inputs-mini">';
            foreach($value as $key => $item)
            {
                $output .= "<div data-id=$item->id class='input-mini-container clearfix'>";
                $output .= '<p class="name">'.$item->name.'</p>';
                $output .= CHtml::link('&times;', '#', array('class'=>'close'));
                $output .= '</div>';
            }
                  $output .= '<div class="input-group mydrop">';
                    $output .= CHtml::textField("newSkill-$data->id", '', array('placeholder'=>'Введите название', 'class'=>'form-control', 'data-id'=>$data->id));
                    $output .= '<div class="input-group-btn">';
                      $output .= '<button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" tabindex="-1">';
                        $output .= '<span class="caret"></span>';
                      $output .= '</button>';
                      $output .= '<ul class="dropdown-menu" role="menu">';
                      $output .= '</ul>';
                    $output .= '</div>';
                  $output .= '</div>';
            $output .= '</div>';
            return $output;
	}
        
	public function formatSkillsWithHidden($value, $data, $name)
	{
            $output = '<div class="inputs-mini">';
            foreach($value as $key => $item)
            {
                $output .= "<div class='input-mini-container clearfix'>";
                $output .= '<p class="name">'.$item->name.'</p>';
                $output .= CHtml::link('&times;', '#', array('class'=>'close'));
                $output .= CHtml::hiddenField(get_class($data)."[$data->id][SkillsIds][]", $item->id);
                $output .= '</div>';
            }
                  $output .= '<div class="input-group mydrop">';
                    $output .= CHtml::textField("term", '', array('placeholder'=>'Введите название', 'class'=>'form-control', 'id'=>false, 'autocomplete'=>'off', 'data-id'=>$data->id));
                    $output .= '<div class="input-group-btn">';
                      $output .= '<button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" tabindex="-1">';
                        $output .= '<span class="caret"></span>';
                      $output .= '</button>';
                      $output .= '<ul class="dropdown-menu" role="menu">';
                      $output .= '</ul>';
                    $output .= '</div>';
                  $output .= '</div>';
            $output .= '</div>';
            return $output;
	}
        
	public function formatTags($value, $data, $name)
	{
            $output = '<div class="skills-mini">';
            $output .= '<div class="skills">';
                foreach($value as $key => $item)
                {
                    $output .= "<div class='skill clearfix' data-id='$item->id'>";
                    $output .= '<p class="name">'.$item->name.'</p>';
                    $output .= '<p class="remove">&times;</p>';
                    $output .= CHtml::hiddenField(get_class($data)."[$data->id][TagsIds][]", $item->id);
                    $output .= '</div>';
                }
            $output .= '</div>';
                  $output .= '<div class="input-group mydrop">';
                    $output .= CHtml::textField("term", '', array('placeholder'=>'Введите название', 'class'=>'form-control', 'id'=>false, 'autocomplete'=>'off', 'data-id'=>$data->id));
                    $output .= '<div class="input-group-btn">';
                      $output .= '<button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" tabindex="-1">';
                        $output .= '<span class="caret"></span>';
                      $output .= '</button>';
                      $output .= '<ul class="dropdown-menu" role="menu">';
                      $output .= '</ul>';
                    $output .= '</div>';
                  $output .= '</div>';
            $output .= '</div>';
            return $output;
	}
        
        // выводит умения на странице уроков
	public function formatDropSkillsLesson($value, $data, $name)
	{
           if(!$data->isNewRecord)
               return $data->Skill->name;
           
            $output .= '<div class="input-group mydrop">';
              $output .= CHtml::textField("newSkill", '', array('placeholder'=>'Введите название умения', 'class'=>'form-control'));
              $output .= '<div class="input-group-btn">';
                $output .= '<button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" tabindex="-1">';
                  $output .= '<span class="caret"></span>';
                $output .= '</button>';
                $output .= '<ul class="dropdown-menu" role="menu">';
                $output .= '</ul>';
              $output .= '</div>';
            $output .= '</div>';
            
            return $output;
	}
}
