<?php
/**
 * Application_Form_Point class file
 *
 * @package     Facepalm
 */

/**
 * Point Form
 *
 * @uses        Zend_Form
 * @package     Facepalm
 * @author      Jason Spradlin <js@jaspr.net>
 * @version     $Id$
 *
 */
class Facepalm_Form_Point extends Zend_Form
{

    /**
     * The point we're working with.
     *
     * @var Facepalm_Model_Row_Point|
     */
    private $_point;

    /**
     * The author (user) of the point we're working with.
     *
     * @var Facepalm_Model_Row_Point|
     */
    private $_user;

    /**
     * Constructor
     *
     * @param Facepalm_Model_Row_User       $user    User who is responsible for the point
     * @param Facepalm_Model_Row_Point|null $point   Existing point to modify
     * @param mixed                         $options Other constructor options
     *
     * @return Facepalm_Form_Point
     */
    public function __construct(Facepalm_Model_Row_User $user, Facepalm_Model_Row_Point $point = null, $options = null)
    {
        /** @var Facepalm_Model_Row_Point $point */

        if (is_null($point)) {
            $pointModel = new Facepalm_Model_Points();
            $point      = $pointModel->createRow();

            $point->created_date  = new Zend_Db_Expr('NOW()');
        }

        $this->_user  = $user;
        $this->_point = $point;

        parent::__construct($options);
    }

    /**
     * Initialize the form
     *
     * @return void
     */
    public function init()
    {
        $point = $this->_point;

        $this->addElementPrefixPath("Bear_", "Bear/");

        $this->addElement(
            $this->createElement("select", "recipient_id")
                 ->setLabel('Recipient')
                 ->setAttrib('id', 'recipient_id')
                 ->setMultiOptions($this->_getAllUsers())
                 ->setRequired(true)
                 ->addValidator("NotEmpty", true, array("messages" => "Please select a user."))
                 ->setValue($point->recipient_id)
        );

        $options = array(
            ''            => ' Select a Type ',
            'positive'    => 'Badass',
            'negative'    => 'Dumbass',
        );

        $this->addElement(
            $this->createElement("select", "point_type")
                 ->setLabel("Point Type")
                 ->setAttrib('id', 'point_type')
                 ->setMultiOptions($options)
                 ->setAttrib('placeholder', 'Make a selection')
                 ->setRequired(true)
                 ->addValidator("NotEmpty", true, array("messages" => "Please select which type of point to give."))
                 ->setValue($point->point_type)
        );

        $recipientId = Zend_Controller_Front::getInstance()->getRequest()->getParam('recipient_id');
        $validator   = new Facepalm_Validate_NotIdentical($recipientId);

        $this->addElement(
            $this->createElement("select", "witness_id")
                ->setLabel('Witness')
                ->setAttrib('id', 'witness_id')
                ->setMultiOptions($this->_getAllUsers())
                ->setRequired(true)
                ->addValidator("NotEmpty", true, array("messages" => "Please select a user."))
                ->addValidator($validator, true)
                ->setValue($point->witness_id)
        );

        $this->addElement(
            $this->createElement("text", "location")
                ->setAttrib("id", "event-location")
                ->setLabel("Location")
                ->setRequired(true)
                ->addValidator("NotEmpty", true, array("messages" => "The event location is required."))
                ->addValidator("StringLength", true, array(0, 255, "messages" => "The location cannot be more than 255 characters."))
                ->setValue($point->location)
        );

        $this->addElement(
            $this->createElement("text", "event_date")
                ->setAttrib("id", "event-date")
                ->setLabel("Date when this occurred")
                ->setRequired(true)
                ->addValidator("NotEmpty", true, array("messages" => "The event date is required."))
                ->addValidator("Date", true)
                ->setValue($point->event_date)
        );

        $this->addElement(
            $this->createElement('textarea', 'explanation')
                ->setLabel('Conversation')
                ->setAttrib('id', 'explanation')
                ->setAttrib('rows', 5)
                ->setRequired(true)
                ->addValidator("NotEmpty", true, array("messages" => "You have to tell us what happened!"))
                ->addValidator('StringLength', false, array(0, 500, "messages" => "Your recollection of the events cannot be more than %max% characters."))
                ->setValue($point->explanation)
        );

        $this->addElement(
            $this->createElement("text", "winning_statement")
                ->setAttrib("id", "winning_statement")
                ->setLabel("Winning Statement")
                ->setRequired(true)
                ->addValidator("NotEmpty", true, array("messages" => "This is a required field"))
                ->addValidator("StringLength", true, array(0, 255, "messages" => "The statement cannot be more than 255 characters."))
                ->setValue($point->winning_statement)
        );

        $this->addElement(
            $this->createElement("submit", "Submit")
                 ->setLabel("Submit New Point")
                 ->setAttrib('class', 'btn')
                 ->setIgnore(true)
        );
    }

    /**
     * Returns an array of all users where the key is the user's ID and the
     * value is the user's name
     *
     * @return array
     */
    private function _getAllUsers()
    {
        $userModel = new Facepalm_Model_Users();

        $placeholder = Array(
            '' => ' Select a User '
        );

        $response = array_merge($placeholder, $userModel->getIdToNameArray());

        return $response;
    }

    /**
     * Saves the form data to the point
     *
     * @return Facepalm_Model_Row_Point
     */
    public function persist()
    {
        $point = $this->_point;

        $point->setFromArray($this->getValues());

        $point->modified_date = new Zend_Db_Expr('NOW()');
        $point->author_id     = $this->_user->id;

        $point->save();

        return $point;
    }
}