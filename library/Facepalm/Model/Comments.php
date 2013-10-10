<?php
/**
 * Comments Model Class File
 *
 * @package     Facepalm
 * @author      Edward R Pfremmer <tepes668@gmail.com>
 */

/**
 * Facepalm Comments Model Class
 *
 * @uses        Zend_Db_Table_Abstract
 * @package     Facepalm
 * @author      Edward R Pfremmer <tepes668@gmail.com>
 * @version     $Id$
 */
class Facepalm_Model_Comments extends Zend_Db_Table_Abstract
{
    /**
     * Table Name
     *
     * @var string
     */
    protected $_name = 'comments';

    /**
     * Row Class Name
     *
     * @var string
     */
    protected $_rowClass = 'Facepalm_Model_Row_Comment';

}