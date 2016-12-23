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
interface GoMage_Feed_Model_Output_OutputInterface
{
    const NONE = '';
    const INTEGER = 'integer';
    const FLOATS = 'float';
    const STRIP_TAGS = 'striptags';
    const SPECIAL_ENCODE = 'htmlspecialchars';
    const SPECIAL_DECODE = 'htmlspecialchars_decode';
    const DELETE_SPACE = 'delete_space';
    const BIG_TO_SMALL = 'big_to_small';
    const REMOVE_LINE_BREAK = 'remove_lb';
    const DATE_TIME = 'date_time';

    /**
     * @param  $value
     * @return mixed
     */
    public function format($value);

}