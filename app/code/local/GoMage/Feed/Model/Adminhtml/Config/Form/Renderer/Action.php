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
 * @version      Release: 4.0.0
 * @since        Class available since Release 3.2
 */
class GoMage_Feed_Model_Adminhtml_Config_Form_Renderer_Action extends Mage_Adminhtml_Block_System_Config_Form_Field
{

    public function toOptionArray()
    {
        return array(
            array('value' => 0, 'label' => $this->__('Rewrite All Fields')),
            array('value' => 1, 'label' => $this->__('Rewrite Empty Fields'))

        );
    }
}