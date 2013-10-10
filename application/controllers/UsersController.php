<?php
/**
 * Users Controller File
 *
 * @package Facepalm
 */

/**
 * Users Controller Class
 *
 * @package Facepalm
 * @author Jason Spradlin <js@jaspr.net>
 * @version $Id$
 */
class UsersController extends Facepalm_Controller_Action
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
     * index Action
     *
     * Lists all users
     *
     * @return void
     */
    public function indexAction()
    {
        // action body
    }

    /**
     * View Action
     *
     * Allows a user to view another user's profile by user name
     *
     * @return void
     */
    public function viewAction()
    {
        $this->_helper->assertHasParameter('user');

        $username  = (string)$this->_getParam('user');
        $userModel = new Facepalm_Model_Users;

        $user = $userModel->findByUserName($username);

        $this->view->user = $user;
    }

    /**
     * New point form action
     *
     * @return void
     */
    public function newAction()
    {
        $form = new Facepalm_Form_User();

        if ($this->getRequest()->isPost()) {
            $post = $this->getRequest()->getPost();
            if ($form->isValid($post)) {
                $newUser = $form->persist();

                $this->_helper->getStaticHelper('redirector')
                              ->gotoRouteAndExit(
                                  array(
                                      'action' => 'view',
                                      'user'   => $newUser->username,
                                  )
                );
            }
        }

        $this->view->form = $form;
    }
}

