<?php

/**
 * @author Joey Smith
 * @version 1.1.1
 * @package Aurora
 * @subpackage Page
 */

class Page_Model_Rowset_Categories extends Zend_Db_Table_Rowset_Abstract 
{
    protected $_rowClass = 'Page_Model_Row_Category'; 
    protected $_tableClass = 'Page_Model_Categories'; 
}

