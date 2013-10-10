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
 * Assert that the given user can approve a point
 *
 * @category Facepalm
 * @package Facepalm_Controller
 */
class Facepalm_Controller_Action_Helper_AssertCanApprove extends Zend_Controller_Action_Helper_Abstract
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
     * Assert that a user can approve a point
     *
     * @param string $point
     * @param string $user
     *
     * @see assertHasParameter()
     * @throws Zend_Controller_Action_Exception
     */
    public function direct($point, $user)
    {
        return $this->assertCanApprove($point, $user);
    }

    /**
     * Assert that a parameter is present
     *
     * @param string $point
     * @param string $user
     *
     * @throws Facepalm_Controller_Action_Exception_CannotApprove
     */
    public function assertCanApprove($point, $user)
    {
        $this->_user = $user;
        $this->_point = $point;

        if ($this->_userCanApprove() === false) {
            /** Bear_Controller_Action_Exception_ParameterMissing */
            require_once "Facepalm/Controller/Action/Exception/CannotApprove.php";

            throw new Facepalm_Controller_Action_Exception_CannotApprove(
                'You are not authorized to approve this point.'
            );
        }
    }

    private function _userCanApprove()
    {
        if ($this->_point->isApproved()){
            return true;
        }

        if ($this->_user->id === $this->_point->getAuthor()->id) {
            return false;
        }

        if (
            $this->_user->id === $this->_point->getRecipient()->id
            || $this->_user->id === $this->_point->getWitness()->id
        ) {
            return true;
        }

        return false;
    }
}