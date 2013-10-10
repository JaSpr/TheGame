<?php
/**
 * Facepalm_View_Helper_NotificationUrl class file
 *
 * @package     Facepalm
 */

/**
 * Facepalm Notification Url View Helper
 *
 * @uses        Zend_View_Helper_Abstract
 * @package     Facepalm
 * @author      Jason Spradlin <js@jaspr.net>
 * @version     $Id$
 */
class Facepalm_View_Helper_NotificationUrl extends Zend_View_Helper_Abstract
{

    /**
     * @var Facepalm_Model_Row_Notification
     */
    private $_notification;

    /**
     * Returns HTML displaying the point status.
     *
     * @param Facepalm_Model_Row_Notification $notification
     *
     * @return string|void
     */
    public function notificationUrl(Facepalm_Model_Row_Notification $notification)
    {
        $this->_notification = $notification;
        return $this->_render();
    }


    /**
     * Renders the status
     *
     * @return string
     */
    private function _render() {
        return $this->view->url(
            array(
                'controller' => 'notifications',
                'action' => 'view',
                'id' => $this->_notification->id,
            ),null, true
        );
    }
}