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
 * @since        Class available since Release 4.0.0
 */
class GoMage_Feed_Model_Output_Factory
{
    /**
     * @var array
     */
    protected $_outputs = array(
        GoMage_Feed_Model_Output_OutputInterface::NONE              => 'gomage_feed/output_none',
        GoMage_Feed_Model_Output_OutputInterface::INTEGER           => 'gomage_feed/output_integer',
        GoMage_Feed_Model_Output_OutputInterface::FLOATS            => 'gomage_feed/output_floats',
        GoMage_Feed_Model_Output_OutputInterface::STRIP_TAGS        => 'gomage_feed/output_stripTags',
        GoMage_Feed_Model_Output_OutputInterface::SPECIAL_ENCODE    => 'gomage_feed/output_specialEncode',
        GoMage_Feed_Model_Output_OutputInterface::SPECIAL_DECODE    => 'gomage_feed/output_specialDecode',
        GoMage_Feed_Model_Output_OutputInterface::DELETE_SPACE      => 'gomage_feed/output_deleteSpace',
        GoMage_Feed_Model_Output_OutputInterface::BIG_TO_SMALL      => 'gomage_feed/output_bigToSmall',
        GoMage_Feed_Model_Output_OutputInterface::REMOVE_LINE_BREAK => 'gomage_feed/output_removeLineBreak',
        GoMage_Feed_Model_Output_OutputInterface::DATE_TIME         => 'gomage_feed/output_dateTime',
    );

    /**
     * @param  string $output
     * @return GoMage_Feed_Model_Output_OutputInterface
     */
    public function get($output)
    {
        return Mage::getSingleton($this->_outputs[$output]);
    }

}
