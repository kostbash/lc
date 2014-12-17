<?php

class MailingCommand extends CConsoleCommand
{
    public $mailRules;
    
    public function run($args)
    {
        $this->mailRules = MailRules::model()->findAll();
        $this->createMailWorkpieces();
    }
    
    public function createMailWorkpieces()
    {
        foreach($this->mailRules as $rule)
        {
            $selectedUsers = $rule->selectUsers();

            foreach($selectedUsers as $user)
            {
                $workPiece = new MailWorkpieces;
                $workPiece->id_user = $user->id;
                $workPiece->id_rule = $rule->id;
                $workPiece->number = $user->numberWorkPiece($rule->id);
                $workPiece->form_date = date('Y-m-d H:i:s');
                $workPiece->send = 0;
                $workPiece->save();
            }
        }
    }
}
