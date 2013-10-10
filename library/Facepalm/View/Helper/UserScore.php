<?php
/**
 * Facepalm_View_Helper_UserScore class file
 *
 * @package     Facepalm
 */

/**
 * Facepalm User Score View Helper
 *
 * @uses        Zend_View_Helper_Abstract
 * @package     Facepalm
 * @author      Jason Spradlin <js@jaspr.net>
 * @version     $Id$
 */
class Facepalm_View_Helper_UserScore extends Zend_View_Helper_Abstract
{

    const POS_PRE = '+';

    /**
     * @var Facepalm_Model_Row_User
     */
    private $_user;

    /**
     * Returns HTML displaying the point status.
     *
     * @param Facepalm_Model_Row_User $user
     *
     * @return string|void
     */
    public function userScore(Facepalm_Model_Row_User $user)
    {
        $this->_user = $user;
        return $this->_render();
    }


    /**
     * Renders the status
     *
     * @return string
     */
    private function _render() {
        $score   = (int) $this->_user->getScore();
        $offsets = $this->_user->getScoreOffsets();

        if ($offsets->pos) {
            $offsets->pos = self::POS_PRE . (string)$offsets->pos;
        }

        return $score . "\n"
                . '<span class="pos-score text-success">( ' . $offsets->pos . '</span>'."\n"
                . '/' . "\n"
                . '<span class="neg-score text-error">' . $offsets->neg . ' )</span>'."\n";
    }
}