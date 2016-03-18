<?php

/**
 * @copyright Copyright &copy; Richard Jung, 2016
 * @package surfer
 * @version 1.0.0
 */

namespace codingtoys\Surfer;

/**
 * An exception class to handle errors
 *
 * @author Richard Jung <richard@coding.toys>
 * @since 1.0
 */
class SurferException extends \Exception
{
    const SELECTOR_NOT_FOUND = 1;
    const COOKIE_NOT_FOUND = 2;
    const FORM_NOT_FILLED_OUT = 3;
    const COULD_NOT_PARSE = 4;
}
