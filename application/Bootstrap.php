<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    /**
     * Initialize the authentication object
     *
     * @return Zend_Auth
     */
    protected function _initAuth()
    {
        $this->bootstrap("session");

        return Zend_Auth::getInstance();
    }

    /**
     * Initializes the session
     *
     * @return void
     */
    protected function _initSession()
    {

        Zend_Session::start();

        register_shutdown_function(array("Zend_Session", "writeClose"), true);
    }

    /**
     * Initialize user DB table
     *
     * @return Facepalm_Model_Users
     */
    protected function _initUserDbTable()
    {
        $this->bootstrap('db');

        return new Facepalm_Model_Users();
    }

    /**
     * Initialize the auth adapter
     *
     * @return Zend_Auth_Adapter_DbTable
     */
    protected function _initAuthAdapter()
    {
        $this->bootstrap('userDbTable');

        $dbAdapter = $this->bootstrap('db')
                          ->getResource('db');

        if (Bear_Crypt_Blowfish::isSupported()) {
            $authAdapter = new Bear_Auth_Adapter_BlowfishDbTable(
                $dbAdapter,
                $this->getResource('userDbTable'),
                'email',
                'password'
            );
        } else {
            $authAdapter = new Zend_Auth_Adapter_DbTable(
                $dbAdapter,
                $this->getResource('userDbTable')->info(Zend_Db_Table::NAME),
                'email',
                'password',
                'MD5(CONCAT(salt,?))'
            );
        }

        return $authAdapter;
    }



    /**
     * Initialize the assert auth has identity action helper
     *
     * @return Bear_Controller_Action_Helper_AssertAuthHasIdentity
     */
    protected function _initAssertAuthHasIdentityActionHelper()
    {
        $this->bootstrap("auth")
             ->bootstrap("frontController");

        /* @var $assertHelper Bear_Controller_Action_Helper_AssertAuthHasIdentity */
        $assertHelper = Zend_Controller_Action_HelperBroker::getStaticHelper("assertAuthHasIdentity");

        $assertHelper->setAuth($this->getResource("auth"));



        return $assertHelper;
    }

    /**
     * Fetch the current user from the database
     *
     * @return Facepalm_Model_Row_User
     */
    protected function _initCurrentUser()
    {
        $this->bootstrap('auth')
             ->bootstrap('db');

        $auth = $this->getResource('auth');

        /** @var $currentUserHelper Bear_Controller_Action_Helper_CurrentUser */
        $currentUserHelper = Zend_Controller_Action_HelperBroker::getStaticHelper('currentUser');
        $currentUserHelper->setAuth($auth);

        return $currentUserHelper;
    }

}

