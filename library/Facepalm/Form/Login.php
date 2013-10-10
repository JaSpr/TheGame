<?php
/**
 * Bear Users Module
 *
 * @category Bear
 * @package Module
 * @subpackage Users
 * @author Konr Ness <kness@sierra-bravo.com>
 */

/**
 * Login form
 *
 * @category Bear
 * @package Module
 * @subpackage Users
 * @author Konr Ness <kness@sierra-bravo.com>
 * @version $Id$
 */
class Facepalm_Form_Login extends Zend_Form
{
    /**
     * Auth
     * @var Zend_Auth
     */
    protected $_auth;

    /**
     * Auth adapter
     * @var Zend_Auth_Adapter_Interface
     */
    protected $_authAdapter;

    /**
     * Get the auth
     *
     * @return Zend_Auth
     * @throws Zend_Controller_Action_Exception
     */
    public function getAuth()
    {
        if (!$this->_auth) {
            throw new Zend_Controller_Action_Exception(
                "No auth set"
            );
        }

        return $this->_auth;
    }

    /**
     * Get the auth adapter
     *
     * @return Zend_Auth_Adapter_DbTable
     * @throws Zend_Controller_Action_Exception
     */
    public function getAuthAdapter()
    {
        if (!$this->_authAdapter) {
            throw new Zend_Controller_Action_Exception(
                "No auth adapter set"
            );
        }

        return $this->_authAdapter;
    }

    /**
     * Initialize the form
     *
     * @return void
     */
    public function init()
    {
        $this->addElement(
            $this->createElement("hidden", "return")
        );

        $this->setAttrib('class', 'login');

        $this->addElement(
            $this->createElement("text", "email")
                ->setLabel("Email Address")
                ->setAttrib('placeholder', 'Email Address *')
                ->setRequired(true)
                ->addValidator("NotEmpty", true, array("messages" => "Cannot be empty"))
        );

        $this->addElement(
            $this->createElement("password", "password")
                ->setLabel("Password")
                ->setAttrib('placeholder', 'Password *')
                ->setRequired(true)
                ->addValidator("NotEmpty", true, array("messages" => "Cannot be empty"))
        );

        $this->addElement(
            $this->createElement("submit", "login")
                 ->setAttrib('class', 'btn')
                 ->setDescription('Login')
                 ->setIgnore(true)
        );
    }


    /**
     * Validate the form
     *
     *
     * @param array $data Form POST data
     * @return boolean
     */
    public function isValid($data)
    {
        if (!parent::isValid($data)) {
            $this->getElement('password')->setValue('');

            return false;
        }

        $adapter = $this->getAuthAdapter()
                        ->setIdentity($this->getValue("email"))
                        ->setCredential($this->getValue("password"));

        $result = $this->getAuth()
                       ->authenticate($adapter);

        if ($result->isValid()) {
            return true;
        }

        $this->getElement('email')->markAsError();
        $this->getElement('password')->markAsError();

        $this->addError('The email address or password you entered is incorrect');
        $this->getElement('password')->setValue('');

        return false;
    }

    /**
     * Set the auth
     *
     * @param Zend_Auth $auth Auth to use for storing authenticated user
     * @return Facepalm_Form_Login
     */
    public function setAuth(Zend_Auth $auth)
    {
        $this->_auth = $auth;

        return $this;
    }

    /**
     * Set the Auth adapter
     *
     * @param Zend_Auth_Adapter_DbTable $authAdapter Adapter for authenticating user
     * @return Facepalm_Form_Login
     */
    public function setAuthAdapter(Zend_Auth_Adapter_DbTable $authAdapter)
    {
        $this->_authAdapter = $authAdapter;

        return $this;
    }

}