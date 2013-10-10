<?php
/**
 * Votes Model Class File
 *
 * @package     Facepalm
 * @author      Edward R Pfremmer <tepes668@gmail.com>
 */

/**
 * Facepalm Votes Model Class
 *
 * @uses        Zend_Db_Table_Abstract
 * @package     Facepalm
 * @author      Edward R Pfremmer <tepes668@gmail.com>
 * @version     $Id$
 */
class Facepalm_Model_Votes extends Zend_Db_Table_Abstract
{

    /**
     * Table Name
     *
     * @var string
     */
    protected $_name = 'votes';

    /**
     * Row Class Name
     *
     * @var string
     */
    protected $_rowClass = 'Facepalm_Model_Row_Vote';

    /**
     * @param integer $id The id of the point for which we want votes
     *
     * @return Zend_Db_Table_Rowset_Abstract
     */
    public function fetchAllByPointId($id)
    {
        $select = $this->select()->where('point_id = ?', (int) $id);
        return $this->fetchAll($select);
    }

}