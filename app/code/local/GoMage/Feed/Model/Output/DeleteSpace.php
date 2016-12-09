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
 * @version      Release: 3.7.0
 * @since        Class available since Release 1.0
 */
class GoMage_Feed_Model_Output_DeleteSpace implements GoMage_Feed_Model_Output_OutputInterface
{
    /**
     * @param  $value
     * @return string
     */
    public function format($value)
    {
        return str_replace(" ", "", $value);
    }
}