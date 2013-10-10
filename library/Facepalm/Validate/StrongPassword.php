<?php
/**
 * Facepalm_Validate_StrongPassword class file
 *
 * @package     Facepalm
 */

/**
 * Facepalm Password Validator
 *
 * @uses        Zend_Validate_Abstract
 * @package     Facepalm
 * @author      Jason Spradlin <js@jaspr.net>
 * @version     $Id$
 */
class Facepalm_Validate_StrongPassword extends Zend_Validate_Abstract
{
    const MINIMUM_LENGTH        = 'passwordMinimumLength';
    const HAS_LOWER_CASE        = 'passwordHasLowerCase';
    const HAS_UPPER_CASE        = 'passwordHasUpperCase';
    const HAS_SPECIAL_OR_NUMBER = 'passwordHasSpecialCharsOrNumbers';
    const NO_WHITESPACE         = 'passwordHasNoWhitespace';

    const SPECIAL_CHARS_AND_NUMBERS = '/[0-9]|[\!|\@|\#|\$|\%|\^|\&|\*|\(|\)|\`|\~|\-|\_|\=|\+|\\|\||\]|\}|\[|\{|\;|\:|\'|\"|\,|\<|\.|\>|\/|\?]/';

    /**
     * Message Templates
     *
     * @var array
     */
    protected $_messageTemplates = array(
        // @codingStandardsIgnoreStart
        self::MINIMUM_LENGTH        => "Password must be at least 5 characters in length.",
        self::HAS_LOWER_CASE        => "Password must contain at least one (1) lowercase letter.",
        self::HAS_UPPER_CASE        => "Password must contain at least one (1) uppercase letter.",
        self::HAS_SPECIAL_OR_NUMBER => "Password must contain at least one (1) number OR one (1) special character.",
        self::NO_WHITESPACE         => "Password must not contain any spaces or tabs.",
        // @codingStandardsIgnoreEnd
    );

    /**
     * Stores the minimum length of the password
     *
     * @var integer
     */
    private $_minimumLength;

    /**
     * Stores which tests to validate against.
     *
     * @var array
     */
    private $_options = array(
        self::MINIMUM_LENGTH        => true,
        self::HAS_LOWER_CASE        => true,
        self::HAS_UPPER_CASE        => true,
        self::HAS_SPECIAL_OR_NUMBER => true,
        self::NO_WHITESPACE         => true,
    );

    /**
     * Class Constructor
     *
     * @param null $options      Constructor options.
     * @param int $minimumLength Minimum allowable password length
     */
    public function __construct($options = null, $minimumLength = 5)
    {
        if (is_array($options)) {
            foreach ($options as $option => $value) {
                $this->_options[$option] = $value;
            }
        }

        if ((int)$minimumLength == $minimumLength) {

            $this->_minimumLength = $min = $minimumLength;

            $this->_messageTemplates[self::MINIMUM_LENGTH]
                = "Password must be at least {$min} characters in length.";
        }
    }

    /**
     * Returns true if all active validation methods pass
     *
     * @param string $value Password being validated.
     *
     * @return boolean
     */
    public function isValid($value)
    {
        $valueString = (string) $value;

        $this->_setValue($valueString);

        if (
            $this->_options[self::MINIMUM_LENGTH]
            && strlen($valueString) < $this->_minimumLength
        ) {
            $this->_error(self::MINIMUM_LENGTH);
        }

        if (
            $this->_options[self::HAS_SPECIAL_OR_NUMBER]
            && !preg_match(self::SPECIAL_CHARS_AND_NUMBERS, $valueString, $matches)
        ) {
            $this->_error(self::HAS_SPECIAL_OR_NUMBER);
        }

        if (
            $this->_options[self::HAS_UPPER_CASE]
            && !preg_match('/[A-Z]/', $valueString, $matches)
        ) {
            $this->_error(self::HAS_UPPER_CASE);
        }

        if (
            $this->_options[self::HAS_LOWER_CASE]
            && !preg_match('/[a-z]/', $valueString, $matches)
        ) {
            $this->_error(self::HAS_LOWER_CASE);
        }

        if (
            $this->_options[self::NO_WHITESPACE]
            && preg_match('/[\s\t\r\n]/', $valueString, $matches)
        ) {
            $this->_error(self::NO_WHITESPACE);
        }

        if ($this->getMessages()) {
            return false;
        } else {
            return true;
        }
    }

}
