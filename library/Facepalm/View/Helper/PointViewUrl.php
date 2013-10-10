<?php
/**
 * Facepalm_View_Helper_PointViewUrl class file
 *
 * @package     Facepalm
 */

/**
 * Facepalm Point View Url View Helper
 *
 * @uses        Zend_View_Helper_Abstract
 * @package     Facepalm
 * @author      Jason Spradlin <js@jaspr.net>
 * @version     $Id$
 */
class Facepalm_View_Helper_PointViewUrl extends Zend_View_Helper_Abstract
{

    /**
     * @var Facepalm_Model_Row_Point
     */
    private $_point;

    /**
     * Returns HTML displaying the point status.
     *
     * @param Facepalm_Model_Row_Point $point
     *
     * @return string|void
     */
    public function pointViewUrl(Facepalm_Model_Row_Point $point)
    {
        $this->_point = $point;
        return $this->_render();
    }


    /**
     * Renders the status
     *
     * @return string
     */
    private function _render() {
        return $this->view->url(
            array(
                'controller' => 'points',
                'action' => 'view',
                'id' => $this->_point->id,
            ),null, true
        );
    }
}