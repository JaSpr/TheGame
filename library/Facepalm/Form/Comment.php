<?php
/**
 * Comments Form Class File
 *
 * @package     Facepalm
 * @author      Edward R Pfremmer <tepes668@gmail.com>
 */

/**
 * Facepalm Comments Form Class
 *
 * @uses        Zend_Form
 * @package     Facepalm
 * @author      Edward R Pfremmer <tepes668@gmail.com>
 * @version     $Id$
 */
class Facepalm_Form_Comment extends Zend_Form
{

    /**
     * Initialize add user form to be rendered
     *
     * @return void
     */
    public function init()
    {
        $this->setName('comments-form')
             ->setAction('/points/add-comment')
             ->setMethod('post');

        $text = new Zend_Form_Element_Textarea('text');
        $text->setLabel('Add Comment:')
             ->setRequired(true)
             ->setAttrib('class', 'comment-textbox')
             ->setAttrib('rows', 5)
             ->addFilter('StripTags')
             ->addFilter('StringTrim')
             ->addValidator('NotEmpty')
             ->addValidator('stringLength', false, array(1, 500));

        $pointId = new Zend_Form_Element_Hidden('point_id');
        $pointId->addValidator('NotEmpty');

        $this->addElements(array($text, $pointId))
             ->addElement('submit', 'submit');
    }

    /**
     * Set remaining values & save row
     *
     * @param Facepalm_Model_Row_User    $user
     * @param Facepalm_Model_Row_Comment $comment
     *
     * @return void
     */
    public function persist(Facepalm_Model_Row_User $user, Facepalm_Model_Row_Comment $comment)
    {
        $comment->created_date = new Zend_Db_Expr('NOW()');
        $comment->author_id = $user->id;

        $comment->save();
    }
}