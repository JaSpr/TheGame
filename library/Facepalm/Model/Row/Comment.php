<?php
/**
 * Comment Model Row Class File
 *
 * @package     Facepalm
 * @author      Edward R Pfremmer <tepes668@gmail.com>
 */

/**
 * Facepalm Comment Model Row Class
 *
 * @uses        Zend_Db_Table_Row_Abstract
 * @package     Facepalm
 * @author      Edward R Pfremmer <tepes668@gmail.com>
 * @version     $Id$
 *
 * @property int $id
 * @property int $author_id
 * @property int $point_id
 * @property string $text
 * @property string $created_date
 */
class Facepalm_Model_Row_Comment extends Zend_Db_Table_Row_Abstract
{
    /**
     * Row Class Name
     *
     * @var string
     */
    protected $_rowClass = 'Facepalm_Model_Row_Comment';

    /**
     * Table Class
     *
     * Name of the table class
     *
     * @var string
     */
    protected $_tableClass = 'Facepalm_Model_Comments';

    /**
     * Cache of likes associated with a comment
     *
     * @var Zend_Db_Table_Rowset_Abstract
     */
    private $_likes;

    /**
     * Cache of like score associated with a comment
     *
     * @var Zend_Db_Table_Rowset_Abstract
     */
    private $_likeScore;

    /**
     * Cache of like score associated with a comment
     *
     * @var Facepalm_Model_Row_Point
     */
    private $_point;

    /**
     * Return all likes associated with a comment
     *
     * @return Zend_Db_Table_Rowset_Abstract
     */
    public function getLikes()
    {
        if (!$this->_likes) {
            $likesModel = new Facepalm_Model_CommentLikes();

            $select = $likesModel->select()->where('enabled = true')
                ->where('comment_id = ?', $this->id);

            $this->_likes = $likesModel->fetchAll($select);
        }

        return $this->_likes;
    }

    /**
     * Return the name for this this comment's author
     *
     * @return Facepalm_Model_Row_User
     */
    public function getAuthor()
    {
        $usersModel = new Facepalm_Model_Users();

        $userId = $this->author_id;
        $user   = $usersModel->find($userId)->current();

        return $user;
    }

    /**
     * Return the prettified date created string
     *
     * @return string
     */
    public function getCreatedDateString()
    {
        return date('l, F j, Y g:i:s A', strtotime($this->created_date));
    }

    /**
     * Return the total likes for this comment
     *
     * @return Integer
     */
    public function getLikeScore()
    {
        if (!$this->_likeScore) {
            $likeModel = new Facepalm_Model_CommentLikes();

            $select = $likeModel->select()->where('enabled = true')
                ->where('type = "like"')
                ->where('comment_id = ?', $this->id)
                ->from('comment_likes', 'count(*) AS count');

            $likeCount = $likeModel->fetchRow($select)->count;

            $select = $likeModel->select()->where('enabled = true')
                ->where('type = "dislike"')
                ->where('comment_id = ?', $this->id)
                ->from('comment_likes', '(count(*) * -1) AS count');

            $dislikeCount = $likeModel->fetchRow($select)->count;

            $this->_likeScore = $likeCount + $dislikeCount;
        }

        return $this->_likeScore;
    }

    /**
     * Returns the point that this comment belongs to.
     *
     * @return Facepalm_Model_Row_Point
     */
    public function getAssociatedPoint()
    {
        if (!$this->_point) {
            $pointsModel = new Facepalm_Model_Points();
            $this->_point = $pointsModel->find($this->point_id)->current();
        }

        return $this->_point;
    }

}