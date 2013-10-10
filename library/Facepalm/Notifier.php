<?php
/**
 * Facepalm_Notifier class file
 *
 * @package     Facepalm
 */

/**
 * Facepalm Notifier
 *
 * @package     Facepalm
 * @author      Jason Spradlin <js@jaspr.net>
 * @version     $Id$
 */
class Facepalm_Notifier
{
    const NEW_COMMENT    = 'newComment';
    const NEW_POINT      = 'newPoint';
    const POINT_APPROVAL = 'pointApproval';

    const COMMENT_CLASS = 'Facepalm_Model_Row_Comment';
    const POINT_CLASS   = 'Facepalm_Model_Row_Point';

    /**
     * @var Facepalm_Model_Row_User
     */
    private $_actor;

    /**
     * @var array
     */
    private $_recipients;

    /**
     * @var Zend_Db_Table_Row_Abstract
     */
    private $_object;


    /**
     * Constructor method
     *
     * @param Zend_Db_Table_Row_Abstract $object
     * @param Facepalm_Model_Row_User    $actor
     *
     * @return Facepalm_Notifier
     */
    public function __construct(Zend_Db_Table_Row_Abstract $object, Facepalm_Model_Row_User $actor)
    {
        $this->_actor      = $actor;
        $this->_object     = $object;

        $notification = $this->_createNotification();

        if ($notification) {
            $notification->send();
        }
    }

    /**
     * Returns a notification object or false if invalid notification was requested
     *
     * @return bool|Facepalm_Notification_Abstract
     */
    private function _createNotification()
    {
        if ($this->_object instanceof Facepalm_Model_Row_Comment) {
            return new Facepalm_Notification_NewComment(
                $this->_object,
                $this->_actor
            );
        }

        if ($this->_object instanceof Facepalm_Model_Row_Point) {
            if ($this->_object->isApproved()) {
                return new Facepalm_Notification_PointApproval(
                    $this->_object,
                    $this->_actor
                );
            } else {
                return new Facepalm_Notification_NewPoint(
                    $this->_object,
                    $this->_actor
                );
            }
        }
        return false;
    }

}