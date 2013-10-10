<?php
/**
 * Facepalm_View_Helper_UserProfileUrl class file
 *
 * @package     Facepalm
 */

/**
 * Facepalm User Profile Url View Helper
 *
 * @uses        Zend_View_Helper_Abstract
 * @package     Facepalm
 * @author      Jason Spradlin <js@jaspr.net>
 * @version     $Id$
 */
class Facepalm_View_Helper_UserProfileUrl extends Zend_View_Helper_Abstract
{

    /**
     * @var Facepalm_Model_Row_User
     */
    private $_user;

    /**
     * Returns HTML displaying the point status.
     *
     * @param Facepalm_Model_Row_User $user
     *
     * @return string|void
     */
    public function userProfileUrl(Facepalm_Model_Row_User $user)
    {
        $this->_user = $user;
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
                'controller' => 'users',
                'action' => 'view',
                'user' => $this->_user->username
            ),null, true
        );
    }
}