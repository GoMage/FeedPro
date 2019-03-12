<?php

/**
 * GoMage.com
 *
 * GoMage Feed Pro
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2019 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/licensing  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 4.3.1
 * @since        Class available since Release 3.2
 */
class GoMage_Feed_Block_Adminhtml_Config_Form_Renderer_Action extends GoMage_Feed_Block_Adminhtml_Config_Form_Renderer_Control
{

    public function getExtraParams()
    {
        $column = $this->getColumn();
        if ($column && isset($column['style'])) {
            return 'onchange="action" style="' . $column['style'] . '" ';
        } else {
            return '';
        }

    }
}
