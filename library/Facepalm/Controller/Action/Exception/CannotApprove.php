<?php
/**
 * Facepalm
 *
 * @author Jason Spradlin <js@jaspr.net>
 * @category Facepalm
 * @package Facepalm_Controller
 */

/** Zend_Controller_Action_Exception */
require_once "Zend/Controller/Action/Exception.php";

/**
 * Parameter missing exception
 *
 * @category Facepalm
 * @package Facepalm_Controller
 */
class Facepalm_Controller_Action_Exception_CannotApprove extends Zend_Controller_Action_Exception
{

}