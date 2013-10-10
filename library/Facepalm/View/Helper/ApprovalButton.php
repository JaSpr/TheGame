<?php
/**
 * Facepalm_View_Helper_ApprovalButton class file
 *
 * @package     Facepalm
 */

/**
 * Facepalm Approval Button View Helper
 *
 * @uses        Zend_View_Helper_Abstract
 * @package     Facepalm
 * @author      Jason Spradlin <js@jaspr.net>
 * @version     $Id$
 */
class Facepalm_View_Helper_ApprovalButton extends Zend_View_Helper_Abstract
{

    /**
     * @var Facepalm_Model_Row_User
     */
    private $_user;

    /**
     * @var Facepalm_Model_Row_Point
     */
    private $_point;

    /**
     * Returns HTML for an approval button, if the user is approved.
     *
     * @param Facepalm_Model_Row_Point $point
     * @param Facepalm_Model_Row_User $user
     *
     * @return string|void
     */
    public function approvalButton(
        Facepalm_Model_Row_Point $point,
        Facepalm_Model_Row_User $user
    )
    {
        $this->_user  = $user;
        $this->_point = $point;

        if ($this->_userCanApprove()) {
            return $this->_render();
        }
        return '';
    }

    /**
     * Validates whether a user can approve a point.
     *
     * @return bool
     */
    private function _userCanApprove()
    {
        // Should not be able to approve if already approved.
        if ($this->_point->isApproved()) {
            return false;
        }

        // The author cannot approve her own point
        if ($this->_user->id === $this->_point->getAuthor()->id) {
            return false;
        }

        // If the user is the recipient or witness (and not the author), then
        // that user can approve.
        if (
            $this->_user->id === $this->_point->getRecipient()->id
            || $this->_user->id === $this->_point->getWitness()->id
        ) {
            return true;
        }
        return false;
    }

    /**
     * Renders the button
     *
     * @return string
     */
    private function _render() {
        $url = $this->view->url(
            array(
                'controller' => 'points',
                'action'     => 'approve',
                'id'         => $this->_point->id,
            ), null, true
        );

        return '<a href="' . $url . '" class="btn">Approve</a>';
    }
}