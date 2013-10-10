<?php
/**
 * Facepalm_Notification_Abstract class file
 *
 * @package     Facepalm
 * @subpackage  Facepalm_Notification
 */

/**
 * Facepalm Notification Abstract
 *
 * @package     Facepalm
 * @subpackage  Facepalm_Notification
 * @author      Jason Spradlin <js@jaspr.net>
 * @version     $Id$
 */
abstract class Facepalm_Notification_Abstract
{
    /**
     * @var Zend_Db_Table_Row_Abstract
     */
    private $_object;

    /**
     * @var Facepalm_Model_Row_User
     */
    private $_actor;

    /**
     * @var array
     */
    private $_recipients = array();

    /**
     * @var string
     */
    private $_message;

    /**
     * @var string
     */
    protected $_url;

    /**
     * @var Facepalm_Model_Notifications
     */
    protected $_notificationsModel;

    /**
     * Constructor function
     *
     * @param Zend_Db_Table_Row_Abstract $object
     * @param Facepalm_Model_Row_User $actor
     *
     * @return Facepalm_Notification_Abstract
     */
    public function __construct(Zend_Db_Table_Row_Abstract $object, Facepalm_Model_Row_User $actor)
    {
        $this->_object = $object;
        $this->_actor  = $actor;
    }

    public function sendEmails()
    {
        $mail = new Zend_Mail();

        foreach ($this->getRecipients() as $recipient) {
            #var_dump($recipient->toArray());
            $mail->addTo($recipient->email, $recipient->name);
        }
        #exit();

        $mail->setSubject("You have a new notification!")
             ->setBodyText($this->getMessage() . "\r\n" . $this->getUrl())
             ->setBodyHtml('<a href="' . $this->getUrl() . '">' . $this->getMessage() . '</a>')
             ->send();
    }

    public function generateNotifications()
    {
        foreach ($this->getRecipients() as $recipient) {
            $this->_generateNotification($recipient);
        }
    }

    public abstract function process();

    public function send() {
        $this->process();
        $this->sendEmails();
        $this->generateNotifications();
    }

    /**
     * @return \Facepalm_Model_Row_User
     */
    public function getActor()
    {
        return $this->_actor;
    }

    /**
     * @return \Zend_Db_Table_Row_Abstract
     */
    public function getObject()
    {
        return $this->_object;
    }

    /**
     * @param Facepalm_Model_Row_User $recipient
     *
     * @return Facepalm_Notification_Abstract
     */
    public function addRecipient(Facepalm_Model_Row_User $recipient)
    {
        $this->_recipients[$recipient->id] = $recipient;
        return $this;
    }

    /**
     * @throws Exception
     * @return array
     */
    public function getRecipients()
    {
        if (!$this->_recipients)
        {
            throw new Exception('No Recipients Set');
        }
        return $this->_recipients;
    }

    /**
     * @param string $message
     *
     * @return Facepalm_Notification_Abstract
     */
    public function setMessage($message)
    {
        $this->_message = $message;

        return $this;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->_message;
    }

    /**
     * Clears the author out of the list of recipients
     *
     * @return Facepalm_Notification_Abstract
     */
    public function filterRecipients()
    {
        unset($this->_recipients[$this->getActor()->id]);

        return $this;
    }

    /**
     * @param string $url
     *
     * @return Facepalm_Notification_Abstract
     */
    public function setUrl($url)
    {
        $this->_url = $url;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->_url;
    }

    /**
     * @return Facepalm_Model_Notifications
     */
    private function _getNotificationsModel()
    {
        if (!$this->_notificationsModel) {
            $this->_notificationsModel = new Facepalm_Model_Notifications();
        }
        return  $this->_notificationsModel;
    }

    /**
     * Generates a single notification to be sent to a single recipient
     *
     * @param Facepalm_Model_Row_User
     *
     * @return void
     */
    private function _generateNotification(Facepalm_Model_Row_User $recipient)
    {
        $row = $this->_getNotificationsModel()->createRow(
            array(
                'link'          => $this->getUrl(),
                'user_id'       => $recipient->id,
                'text'          => $this->getMessage(),
                'created_date'  => new Zend_Db_Expr('NOW()'),
                'modified_date' => new Zend_Db_Expr('NOW()'),
            )
        );

        $row->save();
    }


}