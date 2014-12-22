<?php
    $messages = SourceMessages::MessagesByCategories(array('reg-login-form'));
    if(!$user->role)
    {
        $user->rememberMe = true;
        $login->rememberMe = true;
        $user->role = Users::$defaultRole;
    }
?>

<script>
    $(function(){
        $('.login-button').click(function(){
            loginButton('login');
        });
        
        $('.reg-as-student').click(function(){
            registrationButton('reg', true);
        });
        
        $('.reg-as-parent, .reg-as-teacher').click(function(){
            parentButton('reg', true);
        });
        
        $('.begin-learning').click(function(){
            beginLearningButton('reg', true);
        });
        
        $('.login-target').click(function(){
            current = $(this);
            if(!current.closest('li').hasClass('active'))
            {
                regLogin = $('#regLogin');
                type = regLogin.data('type');
                submitButton = $('#regLogin #submit-reg');
                if(type==='login')
                {
                    submitButton.val('<?php echo Yii::t('reg-login-form', $messages[47]->message); ?>');
                }
                else if(type==='registration')
                {
                    submitButton.val('<?php echo Yii::t('reg-login-form', $messages[51]->message); ?>');
                }
                else if(type==='begin-learning')
                {
                    submitButton.val('<?php echo Yii::t('reg-login-form', $messages[55]->message); ?>');
                }
            }
        });
        
        $('.register-target').click(function(){
            current = $(this);
            if(!current.closest('li').hasClass('active'))
            {
                regLogin = $('#regLogin');
                type = regLogin.data('type');
                submitButton = $('#regLogin #submit-reg');
                if(type==='login')
                {
                    submitButton.val('<?php echo Yii::t('reg-login-form', $messages[48]->message); ?>');
                }
                else if(type==='registration')
                {
                    submitButton.val('<?php echo Yii::t('reg-login-form', $messages[52]->message); ?>');
                }
                else if(type==='begin-learning')
                {
                    submitButton.val('<?php echo Yii::t('reg-login-form', $messages[56]->message); ?>');
                }
            }
        });

        $('#role-student').click(function(){
            roleStudentShow();
        });
        
        $('#role-parent').click(function(){
            roleParentShow();
        });
        
        $('#submit-reg').click(function(){
            current = $(this);
            modal = $('#regLogin');
            tab = current.closest('.modal-content').find('.tab-pane.active');
            if(tab.attr('id')==='register-tab')
            {
                form = tab.find('.show form');
            }
            else
            {
                form = tab.find('form');
            }
            form.append('<input type="hidden" name="regLoginType" value="'+ modal.data('type')+ '" />');
            form.submit();
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
    
    function roleStudentShow()
    {
        $('#reg-parent').removeClass('show').addClass('hide');
        $('#reg-student').removeClass('hide').addClass('show');
    }
    
    function roleParentShow()
    {
        $('#reg-student').removeClass('show').addClass('hide');
        $('#reg-parent').removeClass('hide').addClass('show');
    }
    
    function loginButton(tab)
    {
        $('#regLogin').data('type', 'login');;
        $('#reg-roles').show();
        
        regTabLink = $('.register-target').html('<?php echo Yii::t('reg-login-form', $messages[45]->message); ?>');
        loginTabLink = $('.login-target').html('<?php echo Yii::t('reg-login-form', $messages[46]->message); ?>');
        
        if(tab==='reg')
        {
            tabTitle = regTabLink;
            submitText = '<?php echo Yii::t('reg-login-form', $messages[48]->message); ?>';
        }
        else if(tab==='login')
        {
            tabTitle = loginTabLink;
            submitText = '<?php echo Yii::t('reg-login-form', $messages[47]->message); ?>';
        }
        
        tabTitle.tab('show');
        $('#regLogin #submit-reg').val(submitText);
        
    }
    
    function registrationButton(tab, check)
    {
        $('#regLogin').data('type', 'registration');
        
        if(check)
        {
            $('#role-student').attr('checked', 'checked');
            roleStudentShow();
        }
        
        $('#reg-roles').show();
        
        regTabLink = $('.register-target').html('<?php echo Yii::t('reg-login-form', $messages[49]->message); ?>');
        loginTabLink = $('.login-target').html('<?php echo Yii::t('reg-login-form', $messages[50]->message); ?>');
        
        if(tab==='reg')
        {
            tabTitle = regTabLink;
            submitText = '<?php echo Yii::t('reg-login-form', $messages[52]->message); ?>';
        }
        else if(tab==='login')
        {
            tabTitle = loginTabLink;
            submitText = '<?php echo Yii::t('reg-login-form', $messages[51]->message); ?>';
        }
        
        tabTitle.tab('show');
        $('#regLogin #submit-reg').val(submitText);
    }
    
    function parentButton(tab, check)
    {
        $('#regLogin').data('type', 'registration');
        
        if(check)
        {
            $('#role-parent').attr('checked', 'checked');
            roleParentShow();
        }
        
        $('#reg-roles').show();
        
        regTabLink = $('.register-target').html('<?php echo Yii::t('reg-login-form', $messages[49]->message); ?>');
        loginTabLink = $('.login-target').html('<?php echo Yii::t('reg-login-form', $messages[50]->message); ?>');
        
        if(tab==='reg')
        {
            tabTitle = regTabLink;
            submitText = '<?php echo Yii::t('reg-login-form', $messages[52]->message); ?>';
        }
        else if(tab==='login')
        {
            tabTitle = loginTabLink;
            submitText = '<?php echo Yii::t('reg-login-form', $messages[51]->message); ?>';
        }
        
        tabTitle.tab('show');
        $('#regLogin #submit-reg').val(submitText);
    }
    
    function beginLearningButton(tab, check)
    {
        $('#regLogin').data('type', 'begin-learning');
        
        if(check)
        {
            $('#role-student').attr('checked', 'checked');
            roleStudentShow();
        }
        
        $('#reg-roles').hide();
        
        regTabLink = $('.register-target').html('<?php echo Yii::t('reg-login-form', $messages[53]->message); ?>');
        loginTabLink = $('.login-target').html('<?php echo Yii::t('reg-login-form', $messages[54]->message); ?>');
        
        if(tab==='reg')
        {
            tabTitle = regTabLink;
            submitText = '<?php echo Yii::t('reg-login-form', $messages[56]->message); ?>';
        }
        else if(tab==='login')
        {
            tabTitle = loginTabLink;
            submitText = '<?php echo Yii::t('reg-login-form', $messages[55]->message); ?>';
        }
        
        tabTitle.tab('show');
        $('#regLogin #submit-reg').val(submitText);
    } 
</script>

<div class="modal fade" id="regLogin" tabindex="-1" role="dialog" aria-labelledby="regLoginLabel" aria-hidden="true" data-type="">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title" id="regLoginLabel">
              <ul class="nav nav-tabs">
                <li class="active"><a class="register-target" href="#register-tab" data-toggle="tab">Регистрация</a></li>
                <li><a class="login-target" href="#login-tab" data-toggle="tab">Вход</a></li>
              </ul>
          </h4>
        </div>
        <div class="modal-body clearfix">
            <div class="tab-content">
                <div class="tab-pane active" id="register-tab">
                    <div class="row" id="reg-roles">
                        <div class="role">
                            <input id="role-student" type="radio" name="Users[role]"<?php if($user->role==2) echo ' checked="checked"'; ?> value="2" />
                            <label for="role-student">Я ученик</label>
                        </div>
                        <div class="role">
                            <input id="role-parent" type="radio" name="Users[role]"<?php if($user->role==4) echo ' checked="checked"'; ?> value="4" />
                            <label for="role-parent">Я родитель</label>
                        </div>
                    </div>
                    
                    <div id="reg-student" class="<?php echo $user->role==2 ? 'show' : 'hide'; ?>">
                        <?php $formRegStudent=$this->beginWidget('CActiveForm', array(
                                'id'=>'reg-form-student',
                                'action'=>array('site/index')
                        ));
                        ?>
                        <div class="row">
                            <?php echo $formRegStudent->labelEx($user,'username', array('class'=>'label-row')); ?>
                            <div class="body-input">
                                <?php echo $formRegStudent->textField($user,'username', array('class'=>'form-control', 'placeholder'=>'Введите логин')); ?>
                                <?php echo $formRegStudent->error($user,'username'); ?>
                            </div>
                        </div>
                        <div class="row rememberMe">
                            <?php echo $formRegStudent->label($user,'rememberMe', array('class'=>'label-row', 'style'=>'margin-right: 5px;')); ?>
                            <?php echo $formRegStudent->checkBox($user,'rememberMe'); ?>
                            <?php echo $formRegStudent->error($user,'rememberMe'); ?>
                        </div>
                        <hr />
                        <div class="row">
                            <div style="margin-bottom: 10px; text-align: left;">
                                <h4 style="margin-top: 0;">Восстановление пароля</h4>
                            </div>
                            <?php echo $formRegStudent->labelEx($user,'id_recovery_question', array('class'=>'label-row')); ?>
                            <div class="body-input">
                                <?php echo $formRegStudent->dropDownList($user,'id_recovery_question', Users::$recoveryQuestions, array('class'=>'form-control', 'empty'=>'Выберите вопрос')); ?>
                                <?php echo $formRegStudent->error($user,'id_recovery_question'); ?>
                            </div>
                        </div>
                        <div class="row">
                            <?php echo $formRegStudent->labelEx($user,'recovery_answer', array('class'=>'label-row')); ?>
                            <div class="body-input">
                                <?php echo $formRegStudent->textField($user,'recovery_answer', array('class'=>'form-control', 'placeholder'=>'Введите ответ')); ?>
                                <?php echo $formRegStudent->error($user,'recovery_answer'); ?>
                            </div>
                        </div>
                        <hr />
                        <div class="row">
                            <div style="margin-bottom: 10px; text-align: left;">
                                <h4 style="margin-top: 0;">Родителям</h4>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <?php echo $formRegStudent->checkBox($user,'addParentOnReg', array('style'=>'float: left; margin-right: 4%;')); ?>
                                <?php echo $formRegStudent->label($user,'addParentOnReg', array('style'=>"line-height: 18px; width: 90%")); ?>
                            </div>
                        </div>
                        <div id="addParentToMe" class="row" <?php if(!$user->addParentOnReg) echo 'style="display: none;"'; ?>>
                            <div class="col-lg-offset-1 col-md-offset-1 col-lg-12 col-md-12">
                                <div class="row">
                                    <div class="col-lg-3 col-md-3">
                                        <?php echo $formRegStudent->labelEx($user,'emailParentOnReg', array('style'=>"line-height: 18px;")); ?>
                                    </div>
                                    <div class="col-lg-9 col-md-9">                  
                                        <?php echo $formRegStudent->textField($user,'emailParentOnReg', array('class'=>'form-control', 'placeholder'=>'Введите Ваш e-mail')); ?>
                                        <?php echo $formRegStudent->error($user,'emailParentOnReg'); ?>
                                        <div style="margin-top: 8px;">
                                            <?php echo $formRegStudent->checkBox($user,'createParentOnReg', array('style'=>'float: left; margin-right: 4%;')); ?>
                                            <?php echo $formRegStudent->label($user,'createParentOnReg', array('style'=>"line-height: 18px; width: 90%")); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name='Users[role]' value='2' />
                        <?php $this->endWidget(); ?>
                    </div>
                    
                    <div id="reg-parent" class="<?php echo $user->role==4 ? 'show' : 'hide'; ?>">
                        <?php $formRegParent=$this->beginWidget('CActiveForm', array(
                                'id'=>'reg-form-parent',
                                'action'=>array('site/index'),
                        ));
                        ?>
                        <div class="row">
                            <div style="margin-bottom: 10px; text-align: left;">
                                <h4 style="margin-top: 0;">Укажите адрес Вашей электронной почты</h4>
                                <div>На этот адрес будет отослано письмо, содержащее пароль доступа к вашей учетной записи в Курсис.</div>
                            </div>
                            <?php echo $formRegParent->labelEx($user,'email', array('class'=>'label-row')); ?>
                            <div class="body-input">
                                <?php echo $formRegParent->textField($user,'email', array('class'=>'form-control', 'placeholder'=>'Введите электронный адрес')); ?>
                                <?php echo $formRegParent->error($user,'email'); ?>
                            </div>
                        </div>
                        <input type="hidden" name='Users[role]' value='4' />
                        <?php $this->endWidget(); ?>
                    </div>
                </div>

                <div class="tab-pane" id="login-tab">
                    <?php $loginFormLearn=$this->beginWidget('CActiveForm', array(
                            'id'=>'login-form',
                            'action'=>array('site/index'),
                    )); ?>
                    <div class="row">
                            <?php echo CHtml::label("Введите ваш псевдоним", '', array('class'=>'label-row')); ?>
                        <div class="body-input">
                            <?php echo $loginFormLearn->textField($login, 'username', array('class'=>'form-control', 'placeholder'=>'Введите логин', 'id'=>false)); ?>
                            <?php echo $loginFormLearn->error($login, 'username'); ?>
                        </div>
                    </div>
                    <div class="row">
                            <?php echo $loginFormLearn->labelEx($login,'password', array('class'=>'label-row')); ?>
                        <div class="body-input">
                            <?php echo $loginFormLearn->passwordField($login,'password', array('class'=>'form-control', 'placeholder'=>'Введите пароль', 'id'=>false)); ?>
                            <?php echo $loginFormLearn->error($login,'password'); ?>
                            <?php echo CHtml::link('Забыли пароль ?', array('users/forget'), array('class'=>'forget-pass')); ?>
                        </div>
                    </div>
                    <div class="row rememberMe">
                        <?php echo $loginFormLearn->label($login,'rememberMe', array('class'=>'label-row', 'style'=>'margin-right:5px')); ?>
                        <?php echo $loginFormLearn->checkBox($login,'rememberMe'); ?>
                        <?php echo $loginFormLearn->error($login,'rememberMe'); ?>
                    </div>
                    <?php $this->endWidget(); ?>
                </div>
            </div><!-- tab-content -->
        </div><!-- modal-body -->
        
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal" onclick="reachGoal('RegisterClose')">закрыть</button>
          <?php echo CHtml::submitButton('Войти', array("class"=>"btn btn-primary", "id"=>'submit-reg')); ?>
        </div>
    </div>
  </div>
</div>
