<?php
/**
 * Facepalm
 *
 * @author Jason Spradlin <js@jaspr.net>
 * @category Facepalm
 * @package Facepalm_Controller
 */

/** Zend_Controller_Action_Helper_Abstract */
require_once "Zend/Controller/Action/Helper/Abstract.php";

/**
 * Sends notifications to anyone who needs notifications
 *
 * @category Facepalm
 * @package Facepalm_Controller
 */
class Facepalm_Controller_Action_Helper_SendNotifications extends Zend_Controller_Action_Helper_Abstract
{

    /**
     * Assert that a user can approve a point
     *
     * @param Zend_Db_Table_Row_Abstract $object
     * @param Facepalm_Model_Row_User    $actor
     *
     * @see sendNotifications()
     *
     * @return Facepalm_Notifier
     */
    public function direct($object, $actor)
    {
        return $this->sendNotifications($object, $actor);
    }

    /**
     * Assert that a parameter is present
     *
     * @param Zend_Db_Table_Row_Abstract $object
     * @param Facepalm_Model_Row_User    $actor
     *
     * @return Facepalm_Notifier
     */
    public function sendNotifications($object, $actor)
    {
        return new Facepalm_Notifier($object, $actor);
    }
}