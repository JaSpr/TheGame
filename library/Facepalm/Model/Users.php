<?php
/**
 * Users Model Class File
 *
 * @package     Facepalm
 * @author      Edward R Pfremmer <tepes668@gmail.com>
 */

/**
 * Facepalm Users Model Class
 *
 * @uses        Zend_Db_Table_Abstract
 * @package     Facepalm
 * @author      Edward R Pfremmer <tepes668@gmail.com>
 * @version     $Id$
 */
class Facepalm_Model_Users extends Zend_Db_Table_Abstract
{
    /**
     * Table Name
     *
     * @var string
     */
    protected $_name = 'users';

    /**
     * Row Class Name
     *
     * @var string
     */
    protected $_rowClass = 'Facepalm_Model_Row_User';

    /**
     * Returns an array of all users as ($user_id => $user_name)
     *
     * @return array
     */
    public function getIdToNameArray()
    {
        $response = array();

        $select  = $this->select()->from($this->_name, array('id', 'name'));
        $results = $this->fetchAll($select)->toArray();

        foreach ($results as $result) {
            $response["0{$result['id']}"] = $result['name'];
        }

        return $response;
    }

    /**
     * Find accounts by identity
     *
     * Checks for a matching email
     *
     * @param string $identity
     *
     * @return Facepalm_Model_Row_User
     */
    public function findByIdentity($identity)
    {
        return $this->fetchRow(
            $this->select()
                 ->where("email = ?", $identity)
        );
    }

    /**
     * Fetches a user object by user name
     *
     * @param string $username User to return (by username)
     *
     * @return null|Zend_Db_Table_Row_Abstract
     */
    public function findByUserName($username)
    {
        return $this->fetchRow(
            $this->select()
                ->where("username = ?", $username)
        );
    }

}