<?php
/**
 * Point Model Row Class File
 *
 * @package     Facepalm
 * @author      Edward R Pfremmer <tepes668@gmail.com>
 */

/**
 * Facepalm Point Model Row Class
 *
 * @uses        Zend_Db_Table_Row_Abstract
 * @package     Facepalm
 * @author      Edward R Pfremmer <tepes668@gmail.com>
 * @version     $Id$
 *
 * @property int $id
 * @property int $author_id
 * @property int $witness_id
 * @property int $recipient_id
 * @property string $explanation
 * @property string $location
 * @property string $winning_statement
 * @property string $point_type
 * @property string $modified_date
 * @property string $created_date
 * @property bool $approved
 * @property bool $contested
 */
class Facepalm_Model_Row_Point extends Zend_Db_Table_Row_Abstract
{

    const POSITIVE = 'positive';
    const NEGATIVE = 'negative';

    /**
     * Row Class Name
     *
     * @var string
     */
    protected $_rowClass = 'Facepalm_Model_Row_Point';

    /**
     * Table Class
     *
     * Name of the table class
     *
     * @var string
     */
    protected $_tableClass = 'Facepalm_Model_Points';

    /**
     * Array of users associated with this point
     *
     * @var array
     */
    private $_associatedUsers = array();

    /**
     * Rowset of comments associated with this point
     *
     * @var Zend_Db_Table_Rowset
     */
    private $_comments = null;

    /**
     * Return all comments associated with the current point
     *
     * @return Zend_Db_Table_Rowset_Abstract
     */
    public function getComments()
    {
        if (!isset($this->_comments)) {
            $commentsModel   = new Facepalm_Model_Comments();
            $select          = $commentsModel->select()->where('point_id = ?', $this->id);
            $this->_comments = $commentsModel->fetchAll($select);
        }
        return $this->_comments;

    }

    /**
     * Returns the point's author's database user object
     *
     * @return Facepalm_Model_Row_User
     */
    public function getAuthor()
    {
        $id = $this->author_id;
        return $this->_getAssociatedUser($id);
    }

    /**
     * Returns the point's recipient's database user object
     *
     * @return Facepalm_Model_Row_User
     */
    public function getRecipient()
    {
        $id = $this->recipient_id;
        return $this->_getAssociatedUser($id);
    }

    /**
     * Returns the point's witness's database user object
     *
     * @return Facepalm_Model_Row_User
     */
    public function getWitness()
    {
        $id = $this->witness_id;
        return $this->_getAssociatedUser($id);
    }

    /**
     * Returns an associated user based on their user_id
     *
     * @param integer $id The ID of the user to cache and return
     *
     * @return Facepalm_Model_Row_User
     */
    private function _getAssociatedUser($id)
    {
        if (!isset($this->_associatedUsers[$id])) {
            $this->_fetchAssociatedUsers();
        }

        return $this->_associatedUsers[$id];
    }

    /**
     * Retrieves and stores all users associated with this point.
     *
     * @return Facepalm_Model_Row_Point
     */
    private function _fetchAssociatedUsers()
    {
        $userIds = array(
            $this->author_id,
            $this->witness_id,
            $this->recipient_id,
        );

        $usersModel = new Facepalm_Model_Users();
        $select     = $usersModel->select()->where('id IN (?)', $userIds);
        $users      = $usersModel->fetchAll($select);

        foreach ($users as $user) {
            $this->_associatedUsers[$user->id] = $user;
        }

        return $this;
    }

    public function isPositive()
    {
        return $this->point_type === self::POSITIVE;
    }

    public function isContested()
    {
        return (bool) $this->contested;
    }

    public function isApproved()
    {
        return (bool)$this->approved;
    }

    /**
     * @return int
     */
    public function pointValue()
    {
        /** @var Facepalm_Model_Row_Vote $vote */

        // Unapproved points have no score
        if (!$this->isApproved()) {
            return 0;
        }

        // Point scoring for contested points
        if ($this->isContested()) {
            $voteScore = 0;
            foreach ($this->getVotes() as $vote) {
                if (!$vote->enabled) {
                    continue;
                }
                if ($vote->status === Facepalm_Model_Row_Vote::POSITIVE) {
                    $voteScore += 1;
                } else if ($vote->status === Facepalm_Model_Row_Vote::NEGATIVE) {
                    $voteScore -= 1;
                }
            }
            if ($voteScore < 0) {
                return 0;
            }
        }
        // everything else returns the proper value.
        return ($this->isPositive()) ? 1 : -1;
    }

    /**
     * Returns all votes associated with a given point
     *
     * @return Zend_Db_Table_Rowset_Abstract
     */
    public function getVotes()
    {
        $votesModel = new Facepalm_Model_Votes();
        return $votesModel->fetchAllByPointId($this->id);
    }

    public function getEventDateString()
    {
        return date('l, F j, Y', strtotime($this->event_date));
    }

    public function getCreatedDateString()
    {
        return date('l, F j, Y', strtotime($this->created_date));
    }


}