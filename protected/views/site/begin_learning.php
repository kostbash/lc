<script>
    $(function(){
        $('#to-course').click(function(){
            current = $(this);
            current.closest('.modal-content').find('.tab-pane.active form').submit();
        });
    });
</script>

<div class="modal fade" id="beginLearning" tabindex="-1" role="dialog" aria-labelledby="beginLearningLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="beginLearningLabel">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#register-tab" data-toggle="tab">Вы не зарегистрированы</a></li>
              <li><a href="#login-tab" data-toggle="tab">Вы уже регистрировались ранее</a></li>
            </ul>
        </h4>
      </div>
      <div class="modal-body clearfix">
        <div class="tab-content">
            <div class="tab-pane active" id="register-tab">
                <?php $regFormLearn=$this->beginWidget('CActiveForm', array(
                        'id'=>'reg-form-begin-learning',
                        'action'=>array('site/index'),
                ));
                $user->rememberMe = true;
                ?>
                    <div class="row">
                        <div style="margin-bottom: 10px; text-align: left;">
                            <h4 style="margin-top: 0;">Укажите адрес Вашей электронной почты</h4>
                            <div>На этот адрес будет отослано письмо, содержащее пароль доступа к вашей учетной записи в Курсис.</div>
                        </div>
                        <?php echo $regFormLearn->labelEx($user,'username'); ?>
                        <div class="body-input">
                            <?php echo $regFormLearn->textField($user,'username', array('class'=>'form-control', 'placeholder'=>'Введите логин')); ?>
                            <?php echo $regFormLearn->error($user,'username'); ?>
                        </div>
                    </div>
                    <input type="hidden" name="Users[role]" value="2" />
                    <hr />
                    <div class="row">
                        <div style="margin-bottom: 10px; text-align: left;">
                            <h4 style="margin-top: 0;">Восстановление пароля</h4>
                        </div>
                        <?php echo $regFormLearn->labelEx($user,'id_recovery_question'); ?>
                        <div class="body-input">
                            <?php echo $regFormLearn->dropDownList($user,'id_recovery_question', Users::$recoveryQuestions, array('class'=>'form-control', 'empty'=>'Выберите вопрос')); ?>
                            <?php echo $regFormLearn->error($user,'id_recovery_question'); ?>
                        </div>
                    </div>
                    <div class="row">
                        <?php echo $regFormLearn->labelEx($user,'recovery_answer'); ?>
                        <div class="body-input">
                            <?php echo $regFormLearn->textField($user,'recovery_answer', array('class'=>'form-control', 'placeholder'=>'Введите ответ')); ?>
                            <?php echo $regFormLearn->error($user,'recovery_answer'); ?>
                        </div>
                    </div>
                <?php $this->endWidget(); ?>
            </div>
            <div class="tab-pane" id="login-tab">
                <?php $loginFormLearn=$this->beginWidget('CActiveForm', array(
                        'id'=>'login-form-begin-learning',
                        'action'=>array('site/index'),
                )); ?>
                    <div class="row">
                            <?php echo Chtml::label("Введите ваш псевдоним"); ?>
                        <div class="body-input">
                            <?php echo $loginFormLearn->textField($login,'username', array('class'=>'form-control', 'placeholder'=>'Введите логин')); ?>
                            <?php echo $loginFormLearn->error($login,'username'); ?>
                        </div>
                    </div>

                    <div class="row">
                            <?php echo $loginFormLearn->labelEx($login,'password'); ?>
                        <div class="body-input">
                            <?php echo $loginFormLearn->passwordField($login,'password', array('class'=>'form-control', 'placeholder'=>'Введите пароль')); ?>
                            <?php echo $loginFormLearn->error($login,'password'); ?>
                        </div>
                    </div>

                    <div class="row rememberMe">
                        <?php echo $loginFormLearn->label($login,'rememberMe'); ?>
                        <?php echo $loginFormLearn->checkBox($login,'rememberMe'); ?>
                        <?php echo $loginFormLearn->error($login,'rememberMe'); ?>
                    </div>

                    <div class="row">
                        <?php echo CHtml::link('Забыли пароль ?', array('users/forget')); ?>
                    </div>
                <?php $this->endWidget(); ?>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" onclick="reachGoal('RegisterClose')">закрыть</button>
        <?php echo CHtml::submitButton('Перейти к курсу', array("class"=>"btn btn-primary", "id"=>'to-course')); ?>
      </div>
    </div>
  </div>
</div>
