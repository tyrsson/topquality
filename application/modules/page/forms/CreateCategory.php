<?php

/**
 * @author Joey Smith
 * @version 1.1.1
 * @package Aurora
 * @subpackage Page
 */

class Page_Form_CreateCategory extends Zend_Dojo_Form
{
    public $url;
    public $name;


    //TODO: Populate the parent page name for editing page
    // May have to remove the dojo element and replace it in the child edit form
    // so that we can populate the page name, but that will remove the datastore
    public function init() {

    	$ext = 'jpg,jpeg,png,gif,bmp,tiff';

        $pages = new Page_Model_Page();

        $date = new Zend_Date();
        $today = $date->toString('MM/dd/yyyy');

        $formContainer = new Zend_Dojo_Form_SubForm('mainForm');

        //Start Main Form
        $this->setAttribs(array(
            'name'  => 'wSFO',
            'data-dojo-id'  => 'wSFO',
            'action' => '/admin/page/create/category',
            'method' => 'post'
            
        ));
        $formContainer->setDecorators(array(
            'FormElements',
            array('TabContainer', array(
                'id' => 'tabContainer',
                'style' => 'width: 1000px; height: 700px;',
                'data-dojo-props' => array(
                    'tabPosition' => 'top'
                ),
            )),
            'DijitForm',
        ));
        
        $id = new Zend_Form_Element_Hidden('catId');
        $id->setName('catId');
        $id->setValue(null);
        
        // start content options elements
        $name = new Zend_Dojo_Form_Element_TextBox('catName');
        $name->setName('catName');
        $name->setLabel('Category Name');

        
        $editor = new Zend_Form_Element_Textarea('content');
        $editor->setName('content');
        $editor->setAttrib('class', 'ckeditor');

        $catModel = new System_Db_Categories();
        
        $cats = new Zend_Form_Element_Multiselect('categories');
        $cats->setAttrib('name', 'categories');
        $cats->setLabel('Assign to Categories?');
        
        //fetchParentDropDown()
        
        $cats->setMultiOptions($catModel->fetchParentDropDown($action = 'create', $catId = '0'));

        // end content options elements

        // content options sub form
        $content = new Zend_Dojo_Form_SubForm();
        $content->setAttribs(array(
                'name'   => 'textboxtab',
                'legend' => 'Content',
        ));
        $content->setDecorators(array(
                'FormElements',
                array('HtmlTag', array('tag' => 'dl')),
                'ContentPane',
        ));

        $content->addElements(array($id, $name, $cats, $editor));
        // end content options sub form
        $roles = new User_Model_Roles();

        $role = new Zend_Dojo_Form_Element_FilteringSelect('role');
        $role->setName('role');
        $role->setRequired(true);
        $role->setLabel('Min Access Role');
        $role->setMultiOptions($roles->fetchAllRoles());

        $access = new Zend_Dojo_Form_SubForm();
        $access->setAttribs(array(
            'name'   => 'accesstab',
            'legend' => 'Access Elements',
        ));
        $access->setDecorators(array(
                'FormElements',
                array('HtmlTag', array('tag' => 'dl')),
                'ContentPane',
        ));
        $visible = new Zend_Dojo_Form_Element_FilteringSelect('visibility');
        $visible->setName('visibility');
        $visible->setLabel('Page Visibility');
        $visible->setMultiOptions(array('public' => 'public', 'private' => 'private'));

        $seo = new Zend_Dojo_Form_SubForm();
        $seo->setAttribs(array(
                'name'   => 'seotab',
                'legend' => 'SEO',
        ));
        $seo->setDecorators(array(
                'FormElements',
                array('HtmlTag', array('tag' => 'dl')),
                'ContentPane',
        ));

        $keywords = new Zend_Dojo_Form_Element_TextBox('keywords');
        $keywords->setName('keywords');
        $keywords->setLabel('Keywords');

        $description = new Zend_Dojo_Form_Element_Textarea('description');
        $description->setName('description');
        $description->setLabel('Description');

        $reindex = new Zend_Dojo_Form_Element_TextBox('reindex');
        $reindex->setName('reindex');
        $reindex->setLabel('Reindex');

//         $linkText = new Zend_Dojo_Form_Element_TextBox('link text');
//         $linkText->setName('')
//         $linkText->setLabel('Link Text');

        $submit = new Zend_Dojo_Form_Element_SubmitButton('submit_wSO', array('ignore' => true, 'label' => 'Save'));
        $submit->setAttrib('onSubmit', 'return false;');

        $seo->addElements(array($keywords, $description, $reindex));

        $access->addElement($visible);
        $access->addElement($role);

        $formContainer->addSubForm($content, 'contentTab');
        $formContainer->addSubForm($seo, 'seoTab');
        $formContainer->addSubForm($access, 'accessTab');
        $this->populate(array('createdDate' => $today));
        
        $this->addSubForm($formContainer, 'wSoObj');
        $this->addElement($submit);

    }
    public function setOptions(array $options)
    {
        if(isset($options['url'])) {
            call_user_func(array($this, 'set'.ucfirst($options['url'])), $options['url']);
            unset($options['url']);
        }
        if(isset($options['name'])) {
            call_user_func(array($this, 'set'.ucfirst($options['name'])), $options['name']);
            unset($options['name']);
        }
        parent::setOptions($options);
    }
	/**
     * @return the $url
     */
    public function getPageUrl ()
    {
        return $this->url;
    }

	/**
     * @param field_type $url
     */
    public function setPageUrl ($url)
    {
        $this->url = $url;
    }

	/**
     * @return the $name
     */
    public function getPageName ()
    {
        return $this->name;
    }

	/**
     * @param field_type $name
     */
    public function setPageName ($name)
    {
        $this->name = $name;
    }
}