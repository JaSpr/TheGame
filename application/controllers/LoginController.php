<?php
/**
 * Login Controller File
 *
 * @package Facepalm
 */

/**
 * Login Controller Class
 *
 * @package Facepalm
 * @author Jason Spradlin <js@jaspr.net>
 * @version $Id$
 */
class LoginController extends Zend_Controller_Action
{

    /**
     * Stores the login form
     *
     * @var Facepalm_Form_Login
     */
    private $_loginForm;

    /**
     * @var Zend_Session_Namespace
     */
    private $_session;

    /**
     * Init function
     *
     * Initializes the controller
     *
     * @return void
     */
    public function init()
    {
        $this->_helper->layout->disableLayout();
    }

    /**
     * Login Action
     *
     * Screen used for users to login
     *
     * @return void
     */
    public function indexAction()
    {
        $form = $this->_getLoginForm();

        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getPost())) {
                $return = $form->getElement("return")->getValue();
                $this->_helper->redirector->goToUrlAndExit(
                    $return,
                    array(
                        "prependBase" => empty($return)
                    )
                );
            }
        }

        $this->view->form = $form;
    }

    public function logoutAction()
    {
        $this->_getAuth()->clearIdentity();

        $this->_helper
             ->flashMessenger
             ->setNamespace('auth')
             ->addMessage('You have been logged out.');


        $this->_helper->redirector->gotoRoute(
            array(
                'controller' => 'login',
                'action'     => 'index',
            ),
            null,
            true
        );
    }

    /**
     * Returns the Auth instance
     *
     * @return Zend_Auth
     */
    private function _getAuth()
    {
        return $this->getInvokeArg("bootstrap")
                    ->getResource('auth');
    }

    /**
     * Returns the Auth Adapter instance
     *
     * @return Zend_Auth_Adapter_Interface
     */
    private function _getAuthAdapter()
    {
        return $this->getInvokeArg("bootstrap")
                    ->getResource('authAdapter');
    }

    /**
     * Get the login form
     *
     * @return Facepalm_Form_Login
     */
    private function _getLoginForm()
    {
        if (!$this->_loginForm) {
            $this->_loginForm = new Facepalm_Form_Login(array(
                "auth"        => $this->_getAuth(),
                "authAdapter" => $this->_getAuthAdapter(),
            ));

            $session = $this->_getSession();

            if (isset($session->postLoginUrl)) {
                $this->_loginForm
                     ->getElement("return")
                     ->setValue($this->_getSession()->postLoginUrl);

                unset($session->postLoginUrl);
            }
        }

        return $this->_loginForm;
    }

    /**
     * Get the session namespace
     *
     * @return Zend_Session_Namespace
     */
    private function _getSession()
    {
        if (!$this->_session) {
            $this->_session = new Zend_Session_Namespace("login");
        }

        return $this->_session;
    }


}

