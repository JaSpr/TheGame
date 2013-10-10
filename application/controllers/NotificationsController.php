<?php
/**
 * Notifications Controller File
 *
 * @package Facepalm
 */

/**
 * Notifications Controller Class
 *
 * @package Facepalm
 * @author Jason Spradlin <js@jaspr.net>
 * @version $Id$
 */
class NotificationsController extends Facepalm_Controller_Action
{

    public function init()
    {
        parent::init();
    }

    /**
     * View Action
     *
     * Allows a user to view a single notification
     *
     * @return void
     */
    public function viewAction()
    {
        $notifications = new Facepalm_Model_Notifications();
        $this->_helper->assertHasParameter('id');

        $notificationId = $this->_getParam('id');
        $notification = $notifications->find($notificationId)->current();

        $this->_helper->assertResourceExists($notification);

        if ($this->getCurrentUser()->id === $notification->user_id) {
            $notification->is_read = true;
            $notification->save();
        }

        $this->_helper->redirector->gotoUrlAndExit($notification->link);
    }

    /**
     * Vote Action
     *
     * Allows a user to vote on a single notification, if the notification is presently being
     * contested and the user is neither the witness nor the recipient.
     *
     * @return void
     */
    public function voteAction()
    {

    }

    /**
     * Approve Action
     *
     * Allows a notifications witness or recipient (whichever is not the notification's
     * author) to validate a notification.
     *
     * @return void
     */
    public function approveAction()
    {
        /** @var Facepalm_Model_Row_Notification $notification */
        $this->_helper->assertHasParameter('id');

        $notificationsModel = new Facepalm_Model_Notifications();
        $notification = $notificationsModel->find($this->_getParam('id'))->current();

        $this->_helper->assertResourceExists($notification);

        $this->_helper->assertCanApprove($notification, $this->getCurrentUser());

        $notification->approved = true;
        $notification->save();

        $this->_helper->sendNotifications(
            $notification, $this->getCurrentUser()
        );

        $this->_helper->redirector->gotoRouteAndExit(
            array(
                'action' => 'view',
                'id'     => $notification->id,
            )
        );

    }

    /**
     * Add Comment Action
     *
     * Allows a user to comment on a single notification.
     *
     * @return void
     */
    public function addCommentAction()
    {
        /** @var Facepalm_Model_Row_Comment $comment */

        $this->_helper->assertHasParameter('notification_id');

        $form = new Facepalm_Form_Comment();

        if($this->getRequest()->isPost()) {
            $post = $this->getRequest()->getPost();

            if($form->isValid($post)) {
                $comments = new Facepalm_Model_Comments();
                $comment = $comments->createRow($form->getValues());

                $form->persist($this->getCurrentUser(), $comment);

                $this->_helper->sendNotifications(
                    $comment, $this->getCurrentUser()
                );

                $form->reset();
            }
        }

        $notifications = new Facepalm_Model_Notifications();
        $notification  = $notifications->find($this->_getParam('notification_id'))->current();

        $form->getElement('notification_id')->setValue($notification->id);

        $this->view->notification = $notification;
        $this->view->form  = $form;

        $this->_helper->layout->disableLayout();
    }


}