<?php
class User_Form_Login extends Zend_Dojo_Form {

    public function init() {

// initialize form
        $this->setMethod('post');
        $this->setName('login');
// create text input for name
        $username = new Zend_Dojo_Form_Element_TextBox('username');
        $username->setLabel('Username:')
                ->setOptions(array('size' => '35'))
                ->setRequired(true)
                ->addFilter('StringTrim');
// create text input for password
        $password = new Zend_Dojo_Form_Element_PasswordTextBox('password');
        $password->setLabel('Password:')
                ->setOptions(array('size' => '35'))
                ->setRequired(true)
                //->addFilter('HtmlEntities')
                ->addFilter('StringTrim');
// create submit button
        $submit = new Zend_Dojo_Form_Element_SubmitButton('logon');
        $submit->setLabel('Log In');
// attach elements to form
        $this->addElement($username)
             ->addElement($password)
             ->addElement($submit);
    }
}