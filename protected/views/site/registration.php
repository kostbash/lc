<?php
    $model->rememberMe = true;
    if(!$model->role)
    {
        $model->role = Users::$defaultRole;
    }
?>
<script>
    $('#reg-form').on('shown.bs.modal', function (e) {
      $('#Users_username').focus();
    });
    
    $(function(){
        $('#user-role-student').click(function(){
            $('.reg-as-parent').removeClass('show').addClass('hide');
            $('.reg-as-student').removeClass('hide').addClass('show');
        });
        $('#user-role-parent').click(function(){
            $('.reg-as-student').removeClass('show').addClass('hide');
            $('.reg-as-parent').removeClass('hide').addClass('show');
        });
        
        $('.submit-reg').click(function(){
            current = $(this);
            current.closest('.modal-content').find('.show form').submit();
            return false;
        });
        
        $('#Users_addParentOnReg').change(function(){
            current = $(this);
            if(current.is(':checked'))
            {
                $('#addParentToMe').show();
            }
            else
            {
                $('#addParentToMe').hide();
            }
        });
    });
</script>
<div class="modal fade" id="regModel" tabindex="-1" role="dialog" aria-labelledby="regModelLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="reachGoal('RegisterClose')">&times;</button>
        <h4 class="modal-title" id="regModelLabel">Регистрация</h4>
      </div>
      <div class="modal-body clearfix">
        <div class="row" id="reg-roles">
            <div class="role">
                <input id="user-role-student" type="radio" name="Users[role]"<?php if($model->role==2) echo ' checked="checked"'; ?> value="2" />
                <label for="user-role-student">Я ученик</label>
            </div>
            <div class="role">
                <input id="user-role-parent" type="radio" name="Users[role]"<?php if($model->role==4) echo ' checked="checked"'; ?> value="4" />
                <label for="user-role-parent">Я родитель</label>
            </div>
        </div>
          
        <div class="reg-as-student <?php echo $model->role==2 ? 'show' : 'hide'; ?>">
            <?php $formRegStudent=$this->beginWidget('CActiveForm', array(
                    'id'=>'reg-form-student',
                    'action'=>array('site/index')
            ));
            ?>
                <div class="row">
                    <?php echo $formRegStudent->labelEx($model,'username'); ?>
                    <div class="body-input">
                        <?php echo $formRegStudent->textField($model,'username', array('class'=>'form-control', 'placeholder'=>'Введите логин')); ?>
                        <?php echo $formRegStudent->error($model,'username'); ?>
                    </div>
                </div>
                <div class="row rememberMe">
                    <?php echo $formRegStudent->label($model,'rememberMe'); ?>
                    <?php echo $formRegStudent->checkBox($model,'rememberMe'); ?>
                    <?php echo $formRegStudent->error($model,'rememberMe'); ?>
                </div>
                <hr />
                <div class="row">
                    <div style="margin-bottom: 10px; text-align: left;">
                        <h4 style="margin-top: 0;">Восстановление пароля</h4>
                    </div>
                    <?php echo $formRegStudent->labelEx($model,'id_recovery_question'); ?>
                    <div class="body-input">
                        <?php echo $formRegStudent->dropDownList($model,'id_recovery_question', Users::$recoveryQuestions, array('class'=>'form-control', 'empty'=>'Выберите вопрос')); ?>
                        <?php echo $formRegStudent->error($model,'id_recovery_question'); ?>
                    </div>
                </div>
                <div class="row">
                    <?php echo $formRegStudent->labelEx($model,'recovery_answer'); ?>
                    <div class="body-input">
                        <?php echo $formRegStudent->textField($model,'recovery_answer', array('class'=>'form-control', 'placeholder'=>'Введите ответ')); ?>
                        <?php echo $formRegStudent->error($model,'recovery_answer'); ?>
                    </div>
                </div>
                <hr />
                <div class="row">
                    <div style="margin-bottom: 10px; text-align: left;">
                        <h4 style="margin-top: 0;">Родителям</h4>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <?php echo $formRegStudent->checkBox($model,'addParentOnReg', array('style'=>'float: left; margin-right: 4%;')); ?>
                        <?php echo $formRegStudent->label($model,'addParentOnReg', array('style'=>"line-height: 18px; width: 90%")); ?>
                    </div>
                </div>
                <div id="addParentToMe" class="row" <?php if(!$model->addParentOnReg) echo 'style="display: none;"'; ?>>
                    <div class="col-lg-offset-1 col-md-offset-1 col-lg-12 col-md-12">
                        <div class="row">
                            <div class="col-lg-3 col-md-3">
                                <?php echo $formRegStudent->labelEx($model,'emailParentOnReg', array('style'=>"line-height: 18px;")); ?>
                            </div>
                            <div class="col-lg-9 col-md-9">                  
                                <?php echo $formRegStudent->textField($model,'emailParentOnReg', array('class'=>'form-control', 'placeholder'=>'Введите Ваш e-mail')); ?>
                                <?php echo $formRegStudent->error($model,'emailParentOnReg'); ?>
                                <div style="margin-top: 8px;">
                                    <?php echo $formRegStudent->checkBox($model,'createParentOnReg', array('style'=>'float: left; margin-right: 4%;')); ?>
                                    <?php echo $formRegStudent->label($model,'createParentOnReg', array('style'=>"line-height: 18px; width: 90%")); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name='Users[role]' value='2' />
            <?php $this->endWidget(); ?>
        </div>
        <div class="reg-as-parent <?php echo $model->role==4 ? 'show' : 'hide'; ?>">
            <?php $formRegParent=$this->beginWidget('CActiveForm', array(
                    'id'=>'reg-form-parent',
                    'enableClientValidation'=>true,
                    'action'=>array('site/index'),
                    'clientOptions'=>array(
                            'validateOnSubmit'=>true,
                    ),
            ));
            ?>
                <div class="row">
                    <div style="margin-bottom: 10px; text-align: left;">
                        <h4 style="margin-top: 0;">Укажите адрес Вашей электронной почты</h4>
                        <div>На этот адрес будет отослано письмо, содержащее пароль доступа к вашей учетной записи в Курсис.</div>
                    </div>
                    <?php echo $formRegParent->labelEx($model,'email'); ?>
                    <div class="body-input">
                        <?php echo $formRegParent->textField($model,'email', array('class'=>'form-control', 'placeholder'=>'Введите электронный адрес')); ?>
                        <?php echo $formRegParent->error($model,'email'); ?>
                    </div>
                </div>
                <input type="hidden" name='Users[role]' value='4' />
            <?php $this->endWidget(); ?>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" onclick="reachGoal('RegisterClose')">закрыть</button>
        <?php echo CHtml::link('Зарегистрироваться', "#", array("class"=>"btn btn-primary submit-reg")); ?>
      </div>
    </div>
  </div>
</div>