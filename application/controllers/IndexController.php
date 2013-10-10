<?php
/**
 * Index Controller File
 *
 * @package Facepalm
 */

/**
 * Index Controller Class
 *
 * @package Facepalm
 * @author Jason Spradlin <js@jaspr.net>
 * @version $Id$
 */
class IndexController extends Facepalm_Controller_Action
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
        parent::init();
    }

    /**
     * Index Action
     *
     * Displays the game dashboard to the logged in user
     *
     * @return void
     */
    public function indexAction()
    {
        $user = $this->getCurrentUser();

        $pointsModel = new Facepalm_Model_Points();

        $allPoints      = $pointsModel->fetchByUserInvolvement($user->id);
        $givenPoints    = $pointsModel->fetchByUserInvolvement($user->id, 'witness');
        $receivedPoints = $pointsModel->fetchByUserInvolvement($user->id, 'recipient');

        $this->view->allPoints      = $allPoints;
        $this->view->givenPoints    = $givenPoints;
        $this->view->receivedPoints = $receivedPoints;
    }

    /**
     * Login Action
     *
     * Screen used for users to login
     *
     * @return void
     */
    public function loginAction()
    {
        $form = $this->_getLoginForm();

        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getPost())) {
                $this->_helper->redirector->goToSimpleAndExit('index');
            }
        }

        $this->view->form = $form;
    }

    private function _getAuth()
    {
        return $this->getFrontController()
                    ->getParam('bootstrap')
                    ->getResource('auth');
    }

    private function _getAuthAdapter()
    {
        return $this->getFrontController()
                    ->getParam('bootstrap')
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

