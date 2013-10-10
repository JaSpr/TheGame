<?php
/**
 * CommentLike Model Row Class File
 *
 * @package     Facepalm
 * @author      Edward R Pfremmer <tepes668@gmail.com>
 */

/**
 * Facepalm CommentLike Model Row Class
 *
 * @uses        Zend_Db_Table_Row_Abstract
 * @package     Facepalm
 * @author      Edward R Pfremmer <tepes668@gmail.com>
 * @version     $Id$
 *
 * @property int $id
 * @property bool $enabled
 * @property int $author_id
 * @property int $comment_id
 * @property string $modified_date
 * @property string $created_date
 * @property string $type
 */
class Facepalm_Model_Row_CommentLike extends Zend_Db_Table_Row_Abstract
{
    /**
     * Row Class Name
     *
     * @var string
     */
    protected $_rowClass = 'Facepalm_Model_Row_CommentLike';

    /**
     * Table Class
     *
     * Name of the table class
     *
     * @var string
     */
    protected $_tableClass = 'Facepalm_Model_CommentLikes';

}