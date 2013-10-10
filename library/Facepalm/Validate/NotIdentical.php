<?php
/**
 * Application_Form_Point class file
 *
 * @package     Facepalm
 */

/** @see Zend_Validate_Abstract */
require_once 'Zend/Validate/Abstract.php';

/**
 * Not Identical Validator
 *
 * @uses        Zend_Form
 * @package     Facepalm
 * @author      Jason Spradlin <js@jaspr.net>
 * @version     $Id$
 */
class Facepalm_Validate_NotIdentical extends Zend_Validate_Abstract
{
    /**
     * Error codes
     * @const string
     */
    const SAME          = 'same';
    const MISSING_VALUE = 'missingToken';

    /**
     * Error messages
     * @var array
     */
    protected $_messageTemplates = array(
        self::SAME          => 'The Recipient and Witness cannot be the same User',
        self::MISSING_VALUE => 'Two values must be provided to match against',
    );

    protected $_matchValue = null;

    /**
     * Sets validator options
     *
     * @param  string $matchValue
     *
     * @throws InvalidArgumentException
     *
     * @return self
     */
    public function __construct($matchValue)
    {
       $this->_matchValue = $matchValue;
    }

    /**
     * Defined by Zend_Validate_Interface
     *
     * Returns true if and only if a match value has been set, and the validation
     * value does not match the match value.
     *
     * @param  mixed $value
     * @param  array $context
     * @return boolean
     */
    public function isValid($value, $context = null)
    {
        $this->_setValue((string) $value);

        if ($value === null) {
            $this->_error(self::MISSING_VALUE);
            return false;
        }

        if ($value == $this->_matchValue) {
            $this->_error(self::SAME);
            return false;
        }

        return true;
    }
}
