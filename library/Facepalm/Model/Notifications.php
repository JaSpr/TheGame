<?php
/**
 * Notifications Model Class File
 *
 * @package     Facepalm
 * @author      Jason Spradlin <js@jaspr.net>
 */

/**
 * Facepalm Notifications Model Class
 *
 * @uses        Zend_Db_Table_Abstract
 * @package     Facepalm
 * @author      Jason Spradlin <js@jaspr.net>
 * @version     $Id$
 */
class Facepalm_Model_Notifications extends Zend_Db_Table_Abstract
{
    /**
     * Table Name
     *
     * @var string
     */
    protected $_name = 'notifications';

    /**
     * Row Class Name
     *
     * @var string
     */
    protected $_rowClass = 'Facepalm_Model_Row_Notification';

    /**
     * Returns all notifications for a given user.
     *
     * @param int $id User's id
     *
     * @return Zend_Db_Table_Rowset_Abstract
     */
    public function fetchAllByUserID($id)
    {
        $select = $this->select()->where('user_id = ?', $id);
        return $this->fetchAll($select);
    }

    /**
     * Returns all notifications for a given user.
     *
     * @param int $id User's id
     *
     * @return Zend_Db_Table_Rowset_Abstract
     */
    public function fetchAllUnreadByUserID($id)
    {
        $select = $this->select()->where('user_id = ?', $id)
                                 ->where('is_read = ?', 0);
        return $this->fetchAll($select);
    }

}