<?php
/**
 * Users Form Class File
 *
 * @package     Facepalm
 * @author      Edward R Pfremmer <tepes668@gmail.com>
 */

/**
 * Facepalm User Form Class
 *
 * @uses        Zend_Form
 * @package     Facepalm
 * @author      Edward R Pfremmer <tepes668@gmail.com>
 * @version     $Id$
 */
class Facepalm_Form_User extends Zend_Form
{
    /**
     * User row to edit
     *
     * @var Facepalm_Model_Row_User
     */
    protected $_user;


    /**
     * Constructor
     *
     * @param Facepalm_Model_Row_User $user
     * @param  array|Zend_Config|null $options
     */
    public function __construct($user = null, $options = null)
    {
        if (is_null($user)) {
            $usersModel = new Facepalm_Model_Users();
            $user = $usersModel->createRow();

            $user->created_date = new Zend_Db_Expr('NOW()');
        }

        $this->setUser($user);

        parent::__construct($options);
    }

    /**
     * Initialize the form
     *
     * @return void
     */
    public function init()
    {
        $this->setAttrib('class', 'add-user');

        $this->addElementPrefixPath("Bear_Validate_", "Bear/Validate/", Zend_Form_Element::VALIDATE);

        $user = $this->getUser();

        $this->addElement(
            $this->createElement("text", "email")
                ->setLabel("Email Address")
                ->setRequired(true)
                ->setAttrib('placeholder', 'Email Address')
                ->addValidator("NotEmpty", true, array("messages" => "Cannot be empty"))
                ->addValidator("StringLength", true, array(0, 100, "messages" => "Cannot be more than %max% characters long"))
                ->addValidator("SimpleEmailAddress", true, array("messages" => "'%value%' is not a valid email address"))
                ->addValidator("Db_NoRecordExists", true, array('users', "email", $this->_getEmailExclude(), "messages" => "'%value%' is already registered"))
                ->setValue($user->email)
        );

        $this->addElement(
            $this->createElement("text", "name")
                ->setLabel("Full Name")
                ->setRequired(true)
                ->setAttrib('placeholder', 'Full Name')
                ->addValidator("NotEmpty", true, array("messages" => "Cannot be empty"))
                ->addValidator("StringLength", true, array(0, 100, "messages" => "Cannot be more than %max% characters long"))
                ->setValue($user->name)
        );

        $this->addElement(
            $this->createElement("text", "username")
                ->setLabel("Username")
                ->setRequired(true)
                ->setAttrib('placeholder', 'Username')
                ->addValidator("NotEmpty", true, array("messages" => "Cannot be empty"))
                ->addValidator("StringLength", true, array(0, 25, "messages" => "Cannot be more than %max% characters long"))
                ->addValidator("Db_NoRecordExists", true, array('users', "username", $this->_getUsernameExclude(), "messages" => "'%value%' is already registered"))
                ->setValue($user->username)
        );

        $this->addElement(
            $this->createElement("password", "password")
                ->setLabel("New Password")
                ->setAttrib('placeholder', 'New Password')
                ->setRequired(true)
                ->addValidator("NotEmpty", true, array("messages" => "Cannot be empty"))
                ->addValidator(new Facepalm_Validate_StrongPassword(null, 8), true)
                ->addValidator("CompareFields", true, array("confirmPassword", "messages" => "The password does not match the confirmation password"))

        );

        $this->addElement(
            $this->createElement("password", "confirmPassword")
                ->setLabel("Confirm Password")
                ->setAttrib('placeholder', 'Confirm Password')
                ->setRequired(true)
                ->setAutoInsertNotEmptyValidator(false)
        );

        $this->addElement(
            $this->createElement("button", "submit")
                ->setLabel('<span>Add User</span>')
                ->setAttrib('type', 'submit')
                ->setAttrib('escape', false)
                ->setAttrib('class', 'btn')
                ->setIgnore(true)
        );
    }

    /**
     * Get the user instance
     *
     * @return Facepalm_Model_Row_User
     * @throws Zend_Form_Exception
     */
    public function getUser()
    {
        if (!$this->_user) {
            throw new Zend_Form_Exception("No user set");
        }

        return $this->_user;
    }

    /**
     * Set the Doctrine base users model
     *
     * @param Facepalm_Model_Row_User $user User
     * @return Facepalm_Form_User
     */
    public function setUser(Facepalm_Model_Row_User $user)
    {
        $this->_user = $user;
        return $this;
    }


    /**
     * Get the email exclude for the base user
     *
     * @return array
     */
    protected function _getEmailExclude()
    {
        if (!$this->getUser()->id) {
            return null;
        }

        return array(
            "field" => "email",
            "value" => $this->getUser()->email
        );
    }


    /**
     * Get the email exclude for the base user
     *
     * @return array
     */
    protected function _getUsernameExclude()
    {
        if (!$this->getUser()->id) {
            return null;
        }

        return array(
            "field" => "username",
            "value" => $this->getUser()->username
        );
    }

    /**
     * Set remaining values & save row
     *
     * @return Facepalm_Model_Row_User
     */
    public function persist()
    {
        $user = $this->getUser();

        $user->setFromArray($this->getValues());
        $user->modified_date = new Zend_Db_Expr('NOW()');

        $user->setPassword($this->getElement('password')->getValue());
        $user->save();

        return $user;
    }
}