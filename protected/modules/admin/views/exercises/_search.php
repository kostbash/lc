<?php

    Yii::app()->clientScript->registerScript('search-form', "

        $('.search-form form').change(function(){
                $('#exercises-grid').yiiGridView('update', { data: $(this).serialize()+'&filter=1' });
        });
    
        $('.search-form .mydrop input').live('keyup', function(){
            current = $(this);
            $.ajax({
                url: '".Yii::app()->createUrl('admin/exercises/skillsbyidsajax', array('id_group'=>$group->id))."',
                type:'POST',
                data: current.closest('form').serialize(),
                success: function(result) { 
                        current.siblings('.input-group-btn').find('.dropdown-menu li').remove();
                        current.siblings('.input-group-btn').find('.dropdown-menu').append(result);
                        current.siblings('.input-group-btn').addClass('open');
                }
            });
        });

        $('.search-form .mydrop .dropdown-toggle').live('click', function(){
            current = $(this);
            $.ajax({
                url: '".Yii::app()->createUrl('admin/exercises/skillsbyidsajax', array('id_group'=>$group->id))."',
                type: 'POST',
                data: current.closest('form').serialize(), 
                success: function(result) { 
                    if(result!='') {
                        current.siblings('.dropdown-menu').find('li').remove();
                        current.siblings('.dropdown-menu').append(result);
                    }
                }
            });
            
        });

        $('.search-form .mydrop .dropdown-menu li').live('click', function(){
            current = $(this);
            if(current.data('dontadd') == '')
            {
                dataId = current.data('id');
                nameSkill = current.find('a').html();
                current.closest('.search-form').find('.chosen-skills').append('<div class=\"skill clearfix\"><p class=\"name\">'+nameSkill+'</p><a href=\"#\" class=\"close\">&times;</a><input type=\"hidden\" name=\"Exercises[SkillsIds][]\" value='+dataId+' /></div>');
                $('#exercises-grid').yiiGridView('update', { data: $('.search-form form').serialize()+'&filter=1' });
            }
            current.parents('.input-group-btn').removeClass('open');
            return false;
        });
        
        $('.search-form .chosen-skills .close').live('click', function(){
            $(this).closest('.skill').remove();
            $('#exercises-grid').yiiGridView('update', { data: $('.search-form form').serialize()+'&filter=1' });
            return false;
        });
        
        $('.only-number').bind('change keyup input click', function() {
            if (this.value.match(/[^0-9]/g))
                this.value = this.value.replace(/[^0-9]/g, '');
        });
    ");

?>

<div class="well clearfix">

<?php $form=$this->beginWidget('CActiveForm', array(
	'method'=>'POST',
)); ?>
    <div class="row" style="margin-bottom: 10px">
	<div class="col-md-3">
		<?php echo $form->label($model,'difficulty'); ?>
                <?php echo $form->dropDownList($model,'difficulty', Exercises::getDataDifficulty(), array("class"=>"form-control input-sm", "empty"=>"Любая")) ?>
	</div>
    
	<div class="col-md-3">
            <?php echo CHtml::label('Требуемые умения', ''); ?>
            <div class="input-group mydrop">
              <input type="text" name="term" placeholder="Введите название умения" class="form-control input-sm" data-id="4" autocomplete="off" />
              <div class="input-group-btn">
                  <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" tabindex="-1">
                    <span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu" role="menu"></ul>
              </div>
            </div>
	</div>
        <div class="col-md-2">
            <div class="chosen-skills clearfix">
                <?php if($model->SkillsIds) : ?>
                    <?php foreach($model->SkillsIds as $skill_id) : ?>
                        <div class="skill clearfix">
                            <p class="name"><?php echo Skills::model()->findByPk($skill_id)->name; ?></p>
                            <a href="#" class="close">×</a><input type="hidden" name="Exercises[SkillsIds][]" value="<?php echo $skill_id; ?>">
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="row" style="margin-bottom: 10px">
       <div class="col-md-3">
            <?php echo $form->label($model,'pageSize'); ?>
            <?php echo $form->dropDownList($model,'pageSize', Exercises::$pageSizes, array("class"=>"form-control input-sm")) ?>
	</div>
    </div>
    <div class="row" style="margin-bottom: 10px">
       <div class="col-md-3">
            <?php echo $form->label($model,'need_answer'); ?>
            <?php echo $form->dropDownList($model,'need_answer', Exercises::$needAnswer, array("class"=>"form-control input-sm", "empty"=>"Все")) ?>
	</div>
    </div>
<?php $this->endWidget(); ?>

</div><!-- search-form -->