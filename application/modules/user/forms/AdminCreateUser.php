<?php
class User_Form_AdminCreateUser extends User_Form_EditUser
{
    public function init() 
    {
        parent::init();
        $this->setAction('/admin/user/create');
        $this->removeElement('captcha');
        
    }
}