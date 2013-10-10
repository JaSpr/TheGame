<?php
/**
 * Points Model Class File
 *
 * @package     Facepalm
 * @author      Edward R Pfremmer <tepes668@gmail.com>
 */

/**
 * Facepalm Points Model Class
 *
 * @uses        Zend_Db_Table_Abstract
 * @package     Facepalm
 * @author      Edward R Pfremmer <tepes668@gmail.com>
 * @version     $Id$
 */
class Facepalm_Model_Points extends Zend_Db_Table_Abstract
{
    /**
     * Table Name
     *
     * @var string
     */
    protected $_name = 'points';

    /**
     * Row Class Name
     *
     * @var string
     */
    protected $_rowClass = 'Facepalm_Model_Row_Point';

    /**
     * Returns only uncontested points
     *
     * @param bool   $contested Whether to return contested or uncontested points
     * @param int    $limit     Total number to return
     * @param string $sortOrder Order in which to sort (ASC / DESC)
     * @param string $sortField Field to sort by
     *
     * @return Zend_Db_Table_Rowset_Abstract
     */
    public function fetchByContestedStatus($contested = true, $limit = null, $sortOrder = 'DESC', $sortField = 'created_date')
    {
        $select = $this->_getBaseSelect($limit, $sortOrder, $sortField);
        $select->where('contested = ?', (bool) $contested);

        return $this->fetchAll($select);
    }

    /**
     * Returns only uncontested points
     *
     * @param bool   $approved  Whether to return contested or uncontested points
     * @param int    $limit     Total number to return
     * @param string $sortOrder Order in which to sort (ASC / DESC)
     * @param string $sortField Field to sort by
     *
     * @return Zend_Db_Table_Rowset_Abstract
     */
    public function fetchByApprovalStatus($approved = true, $limit = null, $sortOrder = 'DESC', $sortField = 'created_date')
    {
        $select = $this->_getBaseSelect($limit, $sortOrder, $sortField);
        $select->where('approved = ?', (bool) $approved);

        return $this->fetchAll($select);
    }

    /**
     * Returns only uncontested points
     *
     * @param int    $limit     Total number to return
     * @param string $sortOrder Order in which to sort (ASC / DESC)
     * @param string $sortField Field to sort by
     *
     * @return Zend_Db_Table_Select
     */
    private function _getBaseSelect($limit, $sortOrder, $sortField)
    {
        $select = $this->select()->order("{$sortField} {$sortOrder}");

        if (!is_null($limit) && is_numeric($limit)) {
            $select->limit($limit);
        }

        return $select;
    }

    /**
     * Returns only uncontested points
     *
     * @param int    $userId    User ID to get by involvement
     * @param string $userRole  Recipient|Witness|Author
     * @param int    $limit     Total number to return
     * @param string $sortOrder Order in which to sort (ASC / DESC)
     * @param string $sortField Field to sort by
     *
     * @return Zend_Db_Table_Rowset_Abstract
     */
    public function fetchByUserInvolvement($userId, $userRole = '', $limit = null, $sortOrder = 'DESC', $sortField = 'created_date')
    {
        $userId = (int) $userId;
        $select = $this->_getBaseSelect($limit, $sortOrder, $sortField);


        switch (strtolower((string)$userRole)) {
            case 'recipient':
                $select->where('recipient_id = ?', $userId);
                break;
            case 'witness':
                $select->where('witness_id = ?', $userId);
                break;
            case 'author':
                $select->where('author_id = ?', $userId);
                break;
            default:
                $select->where('author_id = ?', $userId)
                       ->orWhere('recipient_id = ?', $userId)
                       ->orWhere('witness_id = ?', $userId);
        }

        return $this->fetchAll($select);
    }


}