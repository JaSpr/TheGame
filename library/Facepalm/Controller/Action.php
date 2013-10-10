<?php
/**
 * Facepalm_Controller_Action File
 *
 * @package Facepalm
 * @author  Jason Spradlin <js@jaspr.net>
 */

/**
 * Action Controller
 *
 * @uses        Zend_Controller_Action
 * @package     Facepalm
 * @author      Jason Spradlin <js@jaspr.net>
 * @version     $Id$
 */
class Facepalm_Controller_Action extends Zend_Controller_Action
{

    /**
     * Initialize the Controller
     *
     * @return void
     */
    public function init()
    {
        $this->_helper->assertAuthHasIdentity();

        $this->view->currentNav    = $this->getActiveNavItem();
        $this->view->currentUser   = $this->getCurrentUser();
        $this->view->sortedUsers   = $this->getUsersSortedByScore();
        $this->view->notifications = $this->getNotifications();
    }

    /**
     * Handles post-dispatch logic.
     *
     * @return void;
     */
    public function postDispatch()
    {
        $this->getCurrentUser()->setLastActivity();
    }

    /**
     * Returns the currently logged in user object.
     * @return Facepalm_Model_Row_User
     */
    public function getCurrentUser()
    {
        $usersModel = new Facepalm_Model_Users();
        $identity   = $this->_helper->currentUser();
        $user       = $usersModel->findByIdentity($identity);

        return $user;
    }

    /**
     * Return a string denoting the current menu item
     * @return string
     */
    public function getActiveNavItem()
    {
        $controller = $this->getRequest()->getControllerName();
        $action     = $this->getRequest()->getActionName();

        if ($controller === 'index' && $action === 'index') {
            return 'home';
        } elseif ($controller === 'points' && $action === 'index') {
            return 'points';
        } elseif ($controller === 'points' && $action === 'new') {
            return 'newpoint';
        } elseif ($controller === 'points' && $action === 'new') {
            return 'about';
        }
    }

    /**
     * Custom sort all users by their score
     * @return array
     */
    public function getUsersSortedByScore()
    {
        $usersModel = new Facepalm_Model_Users();

        $users = $usersModel->fetchAll();
        $rows  = array();

        foreach($users as $user) {
            $rows[] = $user;
        }

        usort($rows, function($a,$b) {
            $a = $a->getScore();
            $b = $b->getScore();

            if ($a == $b) {
                return 0;
            }
            return ($a < $b) ? 1 : -1;
        });

        return array_slice($rows, 0, 9);
    }

    /**
     * Get all notifications for the current user
     * @return Zend_Db_Table_Rowset_Abstract
     */
    public function getNotifications()
    {
        $notificationsModel = new Facepalm_Model_Notifications();
        $user               = $this->getCurrentUser();
        $notifications      = $notificationsModel->fetchAllUnreadByUserID($user->id);

        return $notifications;
    }

}