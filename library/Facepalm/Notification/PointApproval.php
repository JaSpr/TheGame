<?php
/**
 * Facepalm_Notification_PointApproval class file
 *
 * @package     Facepalm
 * @subpackage  Facepalm_Notification
 */

/**
 * Facepalm New Point Approval Notification
 *
 * @package     Facepalm
 * @subpackage  Facepalm_Notification
 * @author      Jason Spradlin <js@jaspr.net>
 * @version     $Id$
 */
class Facepalm_Notification_PointApproval extends Facepalm_Notification_Abstract
{

    /**
     * Constructor function
     *
     * @param Facepalm_Model_Row_Point $object
     * @param Facepalm_Model_Row_User $actor
     *
     * @return Facepalm_Notification_PointApproval
     */
    public function __construct(Facepalm_Model_Row_Point $object, Facepalm_Model_Row_User $actor)
    {
        parent::__construct($object, $actor);
    }

    /**
     * Processes the incoming data
     */
    public function process()
    {
        $point = $this->getObject();
        $actor = $this->getActor();
        $view  = Zend_Layout::getMvcInstance()->getView();

        $this->addRecipient($point->getWitness())
            ->addRecipient($point->getRecipient())
            ->filterRecipients()
            ->setMessage(
            "{$actor->name} has approved your point: " . $point->winning_statement
        )->setUrl($view->serverUrl($view->pointViewUrl($point)));
    }



}