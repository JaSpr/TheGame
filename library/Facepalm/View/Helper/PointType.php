<?php
/**
 * Facepalm_View_Helper_PointType class file
 *
 * @package     Facepalm
 */

/**
 * Facepalm Point Type View Helper
 *
 * @uses        Zend_View_Helper_Abstract
 * @package     Facepalm
 * @author      Jason Spradlin <js@jaspr.net>
 * @version     $Id$
 */
class Facepalm_View_Helper_PointType extends Zend_View_Helper_Abstract
{

    const POSITIVE = 'badass';
    const NEGATIVE = 'dumbass';

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
    public function pointType(Facepalm_Model_Row_Point $point)
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
        if ($this->_point->isPositive()) {
            return '<span class="text-success">' . self::POSITIVE . '</span>';
        }
        return '<span class="text-error">' . self::NEGATIVE . '</span>';
    }
}