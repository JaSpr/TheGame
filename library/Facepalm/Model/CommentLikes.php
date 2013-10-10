<?php
/**
 * CommentLikes Model Class File
 *
 * @package     Facepalm
 * @author      Edward R Pfremmer <tepes668@gmail.com>
 */

/**
 * Facepalm CommentLikes Model Class
 *
 * @uses        Zend_Db_Table_Abstract
 * @package     Facepalm
 * @author      Edward R Pfremmer <tepes668@gmail.com>
 * @version     $Id$
 */
class Facepalm_Model_CommentLikes extends Zend_Db_Table_Abstract
{
    /**
     * Table Name
     *
     * @var string
     */
    protected $_name = 'comment_likes';

    /**
     * Row Class Name
     *
     * @var string
     */
    protected $_rowClass = 'Facepalm_Model_Row_CommentLike';

    /**
     * Insert or update a comment like
     *
     * @param Integer $userId    The user who is ranking the comment
     * @param Integer $commentId The comment that is being ranked
     * @param Boolean $type      Whether the comment is being rankedis positive
     *                           or negative
     *
     * @return Facepalm_Model_Row_CommentLike
     */
    public function addOrUpdateLike($userId, $commentId, $type)
    {
        /** @var Facepalm_Model_Row_CommentLike $like */

        $select = $this->select()
                       ->where('comment_id = ?', $commentId)
                       ->where('author_id = ?', $userId);

        $like = $this->fetchRow($select);

        if (!$like) {
            $like = $this->createRow(
                array(
                    'author_id'     => $userId,
                    'comment_id'    => $commentId,
                    'enabled'       => true,
                    'created_date'  => new Zend_Db_Expr('NOW()'),
                )
            );
        } else {
            if ($type === $like->type) {
                $like->enabled = !$like->enabled;
            } else {
                $like->enabled = true;
            }
        }

        $like->type = $type;
        $like->modified_date = new Zend_Db_Expr('NOW()');
        $like->save();

        return $like;
    }


}