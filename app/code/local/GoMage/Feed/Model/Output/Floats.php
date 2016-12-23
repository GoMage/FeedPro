<?php

/**
 * GoMage.com
 *
 * GoMage Feed Pro
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2016 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/license-agreement/  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 4.0.0
 * @since        Class available since Release 4.0.0
 */
class GoMage_Feed_Model_Output_Floats implements GoMage_Feed_Model_Output_OutputInterface
{
    /**
     * @param  $value
     * @return float
     */
    public function format($value)
    {
        if (!empty($value)) {
            return number_format((float)$value, 2, '.', '');
        }
        return $value;
    }
}