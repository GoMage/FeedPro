<?php

/**
 * GoMage.com
 *
 * GoMage Feed Pro
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2019 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/license-agreement/  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 4.3.1
 * @since        Class available since Release 4.0.0
 */
interface GoMage_Feed_Model_Operator_OperatorInterface
{
    const EQUAL = 0;
    const NOT_EQUAL = 1;
    const GREATER = 2;
    const LESS = 3;
    const GREATER_EQUAL = 4;
    const LESS_EQUAL = 5;
    const LIKE = 6;
    const NOT_LIKE = 7;

    /**
     * @param  $testable
     * @param  $value
     * @return bool
     */
    public function compare($testable, $value);

}