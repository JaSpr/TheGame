<?php
/**
 * User Model Row Class File
 *
 * @package     Facepalm
 * @author      Edward R Pfremmer <tepes668@gmail.com>
 */

/**
 * Facepalm User Model Row Class
 *
 * @uses        Zend_Db_Table_Row_Abstract
 * @package     Facepalm
 * @author      Edward R Pfremmer <tepes668@gmail.com>
 * @version     $Id$
 *
 * @property int $id
 * @property string $username
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $salt
 * @property string $created_date
 * @property string $modified_date
 * @property string $last_activity_date
 */
class Facepalm_Model_Row_User extends Zend_Db_Table_Row_Abstract
{
    /**
     * Row Class Name
     *
     * @var string
     */
    protected $_rowClass = 'Facepalm_Model_Row_User';

    /**
     * Table Class
     *
     * Name of the table class
     *
     * @var string
     */
    protected $_tableClass = 'Facepalm_Model_Users';

    /**
     * Stores a rowset of points
     *
     * @var Zend_Db_Table_Rowset_Abstract
     */
    private $_points;

    /**
     * Sets and encrypts a user's password and salt value
     *
     * @param $password
     *
     * @return Facepalm_Model_Row_User
     */
    public function setPassword($password)
    {
        if (Bear_Crypt_Blowfish::isSupported()) {
            // use Blowfish crypt
            $this->salt     = Bear_Crypt_Blowfish::generateSalt();
            $this->password = Bear_Crypt_Blowfish::hash($password, $this->salt);
        } else {
            // fall back to MD5
            $this->salt     = Bear_Crypt_Blowfish::generateSalt();
            $this->password = md5($this->salt . $password);
        }

        return $this;
    }

    /**
     * Sets the last activity date for a user to the current date and time
     *
     * @return Facepalm_Model_Row_User
     */
    public function setLastActivity()
    {
        $this->last_activity_date = new Zend_Db_Expr('NOW()');
        $this->save();

        return $this;
    }

    /**
     * Returns the user's aggregate score
     *
     * @return int
     */
    public function getScore()
    {
        /** @var Facepalm_Model_Row_Point $point */

        if (!$this->_points) {
            $pointsModel = new Facepalm_Model_Points();
            $this->_points = $pointsModel->fetchByUserInvolvement($this->id, 'recipient');
        }

        $score = 0;

        foreach ($this->_points as $point) {
            $score += $point->pointValue();
        }

        return $score;
    }

    /**
     * Calculate & return the negative/positive score offsets
     *
     * @return object
     */
    public function getScoreOffsets()
    {
        if (!$this->_points) {
            $this->getScore();
        }

        $pos = 0;
        $neg = 0;

        foreach($this->_points as $point) {
            if ($point->pointValue() > 0) {
                $pos += $point->pointValue();
            }
            if ($point->pointValue() < 0) {
                $neg += $point->pointValue();
            }
        }

        return (object)array(
            'pos' => $pos,
            'neg' => $neg,
        );
    }

    /**
     * Returns all posts that a user is involved with
     *
     * @return Zend_Db_Table_Rowset_Abstract
     */
    public function getAssociatedPoints()
    {
        $pointsModel = new Facepalm_Model_Points;
        $points = $pointsModel->fetchByUserInvolvement($this->id);

        return $points;
    }

}