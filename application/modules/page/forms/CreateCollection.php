<?php
class Page_Form_CreateCollection extends Zend_Dojo_Form
{
    public function init() {

        $this->setMethod('post');
        $name = new Zend_Dojo_Form_Element_TextBox('name');
        $name->setLabel('Collection Name:')
                     ->setRequired(true)
                     ->addValidator('NotEmpty', true)
                     ->addFilter('HtmlEntities')
                     ->addFilter('StringTrim');
        
        $visible = new Zend_Dojo_Form_Element_FilteringSelect('visible');
        $visible->setLabel('Page Visibility');
        $visible->setMultiOptions(array('public' => 'public', 'private' => 'private'));
        
        $roles = new User_Model_Roles();
        $role = new Zend_Dojo_Form_Element_FilteringSelect('role');
        $role->setRequired(true);
        $role->setLabel('Min Access Role');
        $role->setMultiOptions($roles->fetchSelectOptions($order = 'DESC'));
        
        
        //Create submit button
        $submit = new Zend_Dojo_Form_Element_SubmitButton('save');
        $submit->setLabel('Save');
        
        $this
        ->addElement($name)
        ->addElement($visible)
        ->addElement($role)
        ->addElement($submit);
    }
}