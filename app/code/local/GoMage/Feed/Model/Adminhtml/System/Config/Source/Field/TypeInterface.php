<?php

/**
 * GoMage.com
 *
 * GoMage Feed Pro
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2017 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/licensing  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 4.2.0
 * @since        Class available since Release 4.0.0
 */
interface GoMage_Feed_Model_Adminhtml_System_Config_Source_Field_TypeInterface
{
    const ATTRIBUTE = 'attribute';
    const PARENT_ATTRIBUTE = 'parent_attribute';
    const STATIC_VALUE = 'static';
    const EMPTY_PARENT_ATTRIBUTE = 'if_empty_parent_attribute';
    const EMPTY_CHILD_ATTRIBUTE = 'if_empty_child_attribute';
    const ATTRIBUTE_SET = 'attribute_set';
    const PERCENT = 'percent';
    const CONFIGURABLE_VALUES = 'conf_values';
    const REVIEW = 'review';
}