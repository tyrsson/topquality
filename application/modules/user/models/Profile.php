<?php

/**
 * Profile
 *  
 * @author jsmith
 * @version 
 */
require_once 'Zend/Db/Table/Abstract.php';

class User_Model_Profile extends Zend_Db_Table_Abstract
{

    /**
     * The default table name
     */
    protected $_name = 'user_profile';
    protected $_primary = 'userId';
    
    public function fetchProfileRoles()
    {
        $table = new Zend_Db_Table('user_profile_roles');
        $table->select()->from('user_profile_roles', array('key' => 'roleId', 'value' => 'role'));
        //$roles = $table->fetchAll()->toArray();
        return $table->fetchAll()->toArray();
    }
}
