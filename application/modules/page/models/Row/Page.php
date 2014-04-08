<?php
require_once ('Zend/Db/Table/Row/Abstract.php');
    /**
     * @author Joey Smith
     * @version 0.9.1
     * @package Page
     */
class Page_Model_Row_Page extends System_Db_Table_Row_Searchable
{


    /**
     * Name of the class of the Zend_Db_Table_Abstract object.
     *
     * @var string
     */
    protected $_tableClass = 'Page_Model_Page';

    /**
     * Primary row key(s).
     *
     * @var array
     */
    protected $_primary = 'id';
    private $comments = null;
	private $user = null;
	//protected $keyWords;
	protected $settings;

	public $log;

	public function init() {
	    parent::init();
	}
	public function getSearchIndexFields()
	{
	    $filter = new Zend_Filter_StripTags();
	    $fields['class'] = __CLASS__; // try changing this to get_called_class() to ident each rows class instead of the parent
	    $fields['key'] = $this->_data['id'];
	    $fields['docRef'] = $fields['class'].':'.$fields['key'];
	    $fields['url'] = '/'.$this->_data['url'];
	    $fields['title'] = $this->_data['name'];
	    $fields['contents'] = $this->_data['content'];
	    $fields['summary'] = substr($filter->filter($this->_data['content']), 0, 300);
	    $fields['createdBy'] = $this->_data['userId'];
	    $fields['dateCreated'] = $this->_data['createdDate'];

	    return $fields;
	}

	/**
	 * @return Model_Row_User
	 */
	public function getUser()
	{
		if (!$this->user) {
			$this->user = $this->findParentRow('User_Model_Users');
		}

		return $this->user;
	}
}