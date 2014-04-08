<?php

/**
 * Slider
 *  
 * @author Joey
 * @version 
 */
require_once 'Zend/Db/Table/Abstract.php';

class Slider_Model_Slider extends Zend_Db_Table_Abstract
{

    /**
     * The default table name
     */
    protected $_name = 'slider';
    protected $_primary = 'slideId'; 
    protected $_sequence = true; 

    public function fetchOrdered($order = 'ASC') 
    {
        $sql = $this->select()
        ->where('slideId > ?', '0')
        ->from($this->_name)
        ->order('order ASC');
        return $this->fetchAll($sql);
    }
    public function fetch($slideId)
    {
        $sql = $this->select()->from($this->_name)->where('slideId = ?', $slideId);
        return $this->fetchRow($sql);
    }

}
