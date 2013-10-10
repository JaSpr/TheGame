<?php
/**
 * Vote Model Row Class File
 *
 * @package     Facepalm
 * @author      Edward R Pfremmer <tepes668@gmail.com>
 */

/**
 * Facepalm Vote Model Row Class
 *
 * @uses        Zend_Db_Table_Row_Abstract
 * @package     Facepalm
 * @author      Edward R Pfremmer <tepes668@gmail.com>
 * @version     $Id$
 *
 * @property int $id
 * @property int $author_id
 * @property int $point_id
 * @property string $status
 * @property bool $enabled
 * @property string $modified_date
 * @property string $created_date
 */
class Facepalm_Model_Row_Vote extends Zend_Db_Table_Row_Abstract
{
    const POSITIVE = 'positive';
    const NEGATIVE = 'negative';
    /**
     * Row Class Name
     *
     * @var string
     */
    protected $_rowClass = 'Facepalm_Model_Row_Vote';

    /**
     * Table Class
     *
     * Name of the table class
     *
     * @var string
     */
    protected $_tableClass = 'Facepalm_Model_Votes';

}