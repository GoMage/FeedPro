<?php

/**
 * GoMage.com
 *
 * GoMage Feed Pro
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2020 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/license-agreement/  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 4.3.2
 * @since        Class available since Release 4.0.0
 */
class GoMage_Feed_Model_Output_SpecialDecode implements GoMage_Feed_Model_Output_OutputInterface
{
    /**
     * @param  $value
     * @return string
     */
    public function format($value)
    {
        return htmlspecialchars_decode($value);
    }
}