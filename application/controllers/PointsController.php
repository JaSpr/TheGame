<?php
/**
 * Points Controller File
 *
 * @package Facepalm
 */

/**
 * Points Controller Class
 *
 * @package Facepalm
 * @author Jason Spradlin <js@jaspr.net>
 * @version $Id$
 */
class PointsController extends Facepalm_Controller_Action
{

    public function init()
    {
        parent::init();
    }

    /**
     * Index Action
     *
     * Displays a list of points
     *
     * @return void
     */
    public function indexAction()
    {
        $pointsModel = new Facepalm_Model_Points();
        $points      = $pointsModel->fetchAll(null, 'created_date DESC');

        $approvedPoints   = $pointsModel->fetchByApprovalStatus();
        $unapprovedPoints = $pointsModel->fetchByApprovalStatus(false);
        $contestedPoints  = $pointsModel->fetchByContestedStatus();

        $this->view->points     = $points;
        $this->view->approved   = $approvedPoints;
        $this->view->unapproved = $unapprovedPoints;
        $this->view->contested  = $contestedPoints;
    }

    /**
     * New Action
     *
     * Allows a user to create a new point
     *
     * @return void
     */
    public function newAction()
    {
        $user = $this->getCurrentUser();
        $form = new Facepalm_Form_Point($user, null);

        if ($this->getRequest()->isPost()) {
            $post = $this->getRequest()->getPost();
            if ($form->isValid($post)) {
                $point = $form->persist();

                $this->_helper->sendNotifications(
                    $point, $this->getCurrentUser()
                );

                $this->_helper->getStaticHelper('redirector')
                              ->gotoSimpleAndExit('index');
            }
        }

        $this->view->form = $form;
    }

    /**
     * View Action
     *
     * Allows a user to view a single point
     *
     * @return void
     */
    public function viewAction()
    {
        $points = new Facepalm_Model_Points();
        $this->_helper->assertHasParameter('id');

        $pointId = $this->_getParam('id');
        $point = $points->find($pointId)->current();

        $this->_helper->assertResourceExists($point);

        #var_dump($point->toArray()); exit();
        $form = new Facepalm_Form_Comment();
        $form->getElement('point_id')->setValue($pointId);

        $this->view->point = $point;
        $this->view->form = $form;
    }

    /**
     * Vote Action
     *
     * Allows a user to vote on a single point, if the point is presently being
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
     * Allows a points witness or recipient (whichever is not the point's
     * author) to validate a point.
     *
     * @return void
     */
    public function approveAction()
    {
        /** @var Facepalm_Model_Row_Point $point */
        $this->_helper->assertHasParameter('id');

        $pointsModel = new Facepalm_Model_Points();
        $point = $pointsModel->find($this->_getParam('id'))->current();

        $this->_helper->assertResourceExists($point);

        $this->_helper->assertCanApprove($point, $this->getCurrentUser());

        $point->approved = true;
        $point->save();

        $this->_helper->sendNotifications(
            $point, $this->getCurrentUser()
        );

        $this->_helper->redirector->gotoRouteAndExit(
            array(
                'action' => 'view',
                'id'     => $point->id,
            )
        );

    }

    /**
     * Add Comment Action
     *
     * Allows a user to comment on a single point.
     *
     * @return void
     */
    public function addCommentAction()
    {
        /** @var Facepalm_Model_Row_Comment $comment */

        $this->_helper->assertHasParameter('point_id');

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

        $points = new Facepalm_Model_Points();
        $point  = $points->find($this->_getParam('point_id'))->current();

        $form->getElement('point_id')->setValue($point->id);

        $this->view->point = $point;
        $this->view->form  = $form;

        $this->_helper->layout->disableLayout();
    }


}