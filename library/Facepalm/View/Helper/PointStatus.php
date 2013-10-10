<?php
/**
 * Facepalm_View_Helper_PointStatus class file
 *
 * @package     Facepalm
 */

/**
 * Facepalm Point Status View Helper
 *
 * @uses        Zend_View_Helper_Abstract
 * @package     Facepalm
 * @author      Jason Spradlin <js@jaspr.net>
 * @version     $Id$
 */
class Facepalm_View_Helper_PointStatus extends Zend_View_Helper_Abstract
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
    public function pointStatus(Facepalm_Model_Row_Point $point)
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
        if ($this->_point->isApproved()) {
            return '<span class="point-status text-success">(confirmed)</span>';
        }

        if ($this->_point->isContested()) {
         return '<span class="point-status text-error">(contested)</span>';
        }

        return '<span class="point-status text-warning">(unconfirmed)</span>';
    }
}