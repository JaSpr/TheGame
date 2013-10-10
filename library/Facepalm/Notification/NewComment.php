<?php
/**
 * Facepalm_Notification_NewComment class file
 *
 * @package     Facepalm
 * @subpackage  Facepalm_Notification
 */

/**
 * Facepalm New Comment Notification
 *
 * @package     Facepalm
 * @subpackage  Facepalm_Notification
 * @author      Jason Spradlin <js@jaspr.net>
 * @version     $Id$
 */
class Facepalm_Notification_NewComment extends Facepalm_Notification_Abstract
{

    /**
     * Constructor function
     *
     * @param Facepalm_Model_Row_Comment $object
     * @param Facepalm_Model_Row_User $actor
     *
     * @return Facepalm_Notification_NewComment
     */
    public function __construct(Facepalm_Model_Row_Comment $object, Facepalm_Model_Row_User $actor)
    {
        parent::__construct($object, $actor);
    }

    /**
     * Processes the incoming data
     */
    public function process()
    {
        /** @var Facepalm_Model_Row_Comment $comment  */

        $comment = $this->getObject();
        $actor   = $this->getActor();
        $view    = Zend_Layout::getMvcInstance()->getView();

        $point = $comment->getAssociatedPoint();
        $comments = $point->getComments();

        $this->addRecipient($point->getAuthor())
             ->addRecipient($point->getRecipient())
             ->addRecipient($point->getWitness());

        foreach ($comments as $comment) {
            $this->addRecipient($comment->getAuthor());
        }

        $this->filterRecipients()
             ->setMessage(
                 "{$actor->name} has commented on the point: " . $point->winning_statement
             )->setUrl($view->serverUrl($view->pointViewUrl($point)));
    }
}