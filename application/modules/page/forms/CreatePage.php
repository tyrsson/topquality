<?php
/**
 * @author Joey Smith
 * @version 1.1.1
 * @package Aurora
 * @subpackage Page
 */
class Page_Form_CreatePage extends Zend_Dojo_Form
{
    public $url;
    public $name;

// 	public function __construct($options = null) {
// 	    if($options !== null) {
// 	        $this->setOptions($options);
// 	    }
// 	}

    //TODO: Populate the parent page name for editing page
    // May have to remove the dojo element and replace it in the child edit form
    // so that we can populate the page name, but that will remove the datastore
    public function init() {

    	$ext = 'jpg,jpeg,png,gif,bmp,tiff';

        $pages = new Page_Model_Page();

        $date = new Zend_Date();
        $today = $date->toString('MM/dd/yyyy');

        //$this->setAction('/admin/pages/edit'.$this->getPageUrl());
        //$this->setAction('/admin/pages/create');
        //$this->setMethod('post');
        
        $formContainer = new Zend_Dojo_Form_SubForm('mainForm');

        //Start Main Form
        $this->setAttribs(array(
            'name'  => 'wSFO',
            'data-dojo-id'  => 'wSFO',
            'action' => '/admin/page/create',
            'method' => 'post'
            
        ));
        $formContainer->setDecorators(array(
            'FormElements',
            array('TabContainer', array(
                'id' => 'tabContainer',
                'style' => 'width: 1000px; height: 500px;',
                'data-dojo-props' => array(
                    'tabPosition' => 'top'
                ),
            )),
            'DijitForm',
        ));

        //$hidden = new Zend_Form_Element_Hidden('edited');
        //$hidden->setAttrib('id', 'edited');
        
        $id = new Zend_Form_Element_Hidden('id');
        $id->setValue(null);
        
        $isLanding = new Zend_Dojo_Form_Element_CheckBox('isLanding');
        $isLanding->setAttrib('name', 'isLanding');
        $isLanding->setLabel('Is this a category landing page?');
        
        $featured = new Zend_Dojo_Form_Element_Checkbox('featured');
        $featured->setAttrib('name', 'featured');
        $featured->setLabel('Is this a featured page?');
        
        // start content options elements
        $name = new Zend_Dojo_Form_Element_TextBox('label');
        $name->setLabel('Page Label');

        
        $editor = new Zend_Form_Element_Textarea('content');
        $editor->setAttrib('class', 'ckeditor');

        $collections = new System_Db_Categories();
        
        $collection = new Zend_Dojo_Form_Element_FilteringSelect('categoryId');
        //$collection->setAttrib('name', 'categoryId');
        $collection->setLabel('Assign to Category?');
        $collection->setMultiOptions($collections->fetchPagecategoryDropDown('Page'));
        
        
        $image = new System_Form_Element_Thumbnail('image');
        $image->setLabel('Featured Image');
        $image->setDestination($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'featured');
        $image->addValidator('Count', false, 1);
        $image->addValidator('Size', false, 1002400);
        $image->addValidator('Extension', false, 'jpg,png,gif');
        $image->setOptions(array('thumbMaxHeight' => '134', 'thumbMaxWidth' => '172', 'thumbNamePrefix' => 'resized_'));

        // end content options elements

        // content options sub form
        $content = new Zend_Dojo_Form_SubForm();
        //$content->setAttrib('id', 'contentOptions');
        $content->setAttribs(array(
                'name'   => 'textboxtab',
                'legend' => 'Content',
        ));
        $content->setDecorators(array(
                'FormElements',
                array('HtmlTag', array('tag' => 'dl')),
                'ContentPane',
        ));

        $content->addElements(array($id, $isLanding, $featured, $collection, $name, $image, $editor));
        // end content options sub form
        $roles = new User_Model_Roles();
        $role = new Zend_Dojo_Form_Element_FilteringSelect('role');
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
        $visible = new Zend_Dojo_Form_Element_FilteringSelect('visible');
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

        $keywords = new Zend_Dojo_Form_Element_TextBox('keyWords');
        $keywords->setLabel('Keywords');

        $description = new Zend_Dojo_Form_Element_Textarea('description');
        $description->setLabel('Description');

        $reindex = new Zend_Dojo_Form_Element_TextBox('reindex');
        $reindex->setLabel('Reindex');

        $linkText = new Zend_Dojo_Form_Element_TextBox('linkText');
        $linkText->setLabel('Link Text');

        $submit = new Zend_Dojo_Form_Element_SubmitButton('submit_wSO', array('ignore' => true, 'label' => 'Save'));
        $submit->setAttrib('onSubmit', 'return false;');

        $seo->addElements(array($keywords, $description, $reindex, $linkText));

        $access->addElements(array($visible, $role));

        $formContainer->addSubForm($content, 'contentTab');
        $formContainer->addSubForm($seo, 'seoTab');
        $formContainer->addSubForm($access, 'accessTab');
        $this->populate(array('createdDate' => $today));
        
        $this->addSubForm($formContainer, 'wSoObj');
        //$this->addElement();
        $this->addElement($submit);

    }
    public function setOptions(array $options)
    {
        if(isset($options['uri'])) {
            call_user_func(array($this, 'set'.ucfirst($options['uri'])), $options['uri']);
            unset($options['uri']);
        }
        if(isset($options['label'])) {
            call_user_func(array($this, 'set'.ucfirst($options['label'])), $options['label']);
            unset($options['label']);
        }
        parent::setOptions($options);
    }
	/**
     * @return the $uri
     */
    public function getPageUri ()
    {
        return $this->uri;
    }

	/**
     * @param field_type $uri
     */
    public function setPageUri ($uri)
    {
        $this->uri = $uri;
    }

	/**
     * @return the $label
     */
    public function getPageLabel ()
    {
        return $this->label;
    }

	/**
     * @param field_type $label
     */
    public function setPageLabel ($label)
    {
        $this->label = $label;
    }
}