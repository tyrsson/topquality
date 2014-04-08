<?php
class Admin_Form_CreateModuleSettings extends Zend_Dojo_Form
{
	public function init() {
	    $moduleName = new Zend_Dojo_Form_Element_TextBox('moduleName');
		$moduleName->setLabel('Module Name')
		->setRequired(true)
		->addValidator('NotEmpty', true)
		->addFilter('StringTrim');

		$variable = new Zend_Dojo_Form_Element_TextBox('variable');
		$variable->setLabel('Variable Name')
		->setRequired(true)
		->addValidator('NotEmpty', true)
		->addFilter('StringTrim');

		$value = new Zend_Dojo_Form_Element_TextBox('value');
		$value->setLabel('Value')
		->setRequired(true)
		->addValidator('NotEmpty', true)
		->addFilter('StringTrim');

		$settingType = new Zend_Dojo_Form_Element_TextBox('settingType');
		$settingType->setLabel('Setting Type')
		->setRequired(true)
		->addValidator('NotEmpty', true)
		->addFilter('StringTrim');
		
		
		$roles = new User_Model_Roles();
		$role = new Zend_Dojo_Form_Element_FilteringSelect('role');
		$role->setRequired(true);
		$role->setLabel('Min Access Role');
		$role->setMultiOptions($roles->fetchAllRoles(true, true));
		

		$submit = new Zend_Dojo_Form_Element_SubmitButton('submit');
		$submit->setLabel('Submit')
				->setOptions(array('class' => 'submit'));

		$this->addElement($moduleName)
				->addElement($variable)
				->addElement($value)
				->addElement($settingType)
				->addElement($role)
		        ->addElement($submit);
	}

}