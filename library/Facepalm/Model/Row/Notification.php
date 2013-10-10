<?php
/**
 * Notification Model Row Class File
 *
 * @package     Facepalm
 * @author      Edward R Pfremmer <tepes668@gmail.com>
 * @author      Jason Spradlin <js@jaspr.net>
 */

/**
 * Facepalm Notification Model Row Class
 *
 * @uses        Zend_Db_Table_Row_Abstract
 * @package     Facepalm
 * @author      Edward R Pfremmer <tepes668@gmail.com>
 * @author      Jason Spradlin <js@jaspr.net>
 * @version     $Id$
 *
 * @property int $id
 * @property int $user_id
 * @property int $link
 * @property string $text
 * @property bool $is_read
 * @property string $created_date
 * @property string $modified_date
 */
class Facepalm_Model_Row_Notification extends Zend_Db_Table_Row_Abstract
{
    /**
     * Row Class Name
     *
     * @var string
     */
    protected $_rowClass = 'Facepalm_Model_Row_Notification';

    /**
     * Table Class
     *
     * Name of the table class
     *
     * @var string
     */
    protected $_tableClass = 'Facepalm_Model_Notifications';

    /**
     * Return the prettified date created string
     *
     * @return string
     */
    public function getCreatedDateString()
    {
        return date('l, F j, Y g:i:s A', strtotime($this->created_date));
    }

}