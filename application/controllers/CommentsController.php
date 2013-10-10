<?php
/**
 * Comments Controller File
 *
 * @package Facepalm
 */

/**
 * Comments Controller Class
 *
 * @package Facepalm
 * @author Jason Spradlin <js@jaspr.net>
 * @version $Id$
 */
class CommentsController extends Facepalm_Controller_Action
{

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
     * Rank Action
     *
     * Allows a comment to be ranked by a user
     *
     * @return void
     */
    public function rankAction()
    {
        $this->_helper->assertHasParameter('id');
        $this->_helper->assertHasParameter('type');

        $userId    = $this->getCurrentUser()->id;
        $commentId = $this->_getParam('id');
        $type      = $this->_getParam('type');

        $likes = new Facepalm_Model_CommentLikes();
        $likes->addOrUpdateLike($userId, $commentId, $type);

        $commentsModel = new Facepalm_Model_Comments();
        $comment = $commentsModel->find($commentId)->current();

        $this->_helper->redirector->gotoRouteAndExit(
            array(
                'controller' => 'points',
                'action'     => 'add-comment',
                'point_id'   => $comment->point_id,
            ),
            null,
            true
        );
    }


}

