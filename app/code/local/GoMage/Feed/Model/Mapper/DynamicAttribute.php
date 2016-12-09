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
class DynamicAttribute implements GoMage_Feed_Model_Mapper_MapperInterface
{

    /**
     * @var \GoMage\Feed\Model\Feed\Field
     */
    protected $_default;

    /**
     * @var \GoMage\Feed\Model\Collection
     */
    protected $_rows;

    public function __construct(
        $value,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Magento\Framework\ObjectManagerInterface $objectManager
    ) {
        /** @var \GoMage\Feed\Model\Attribute $attribute */
        $attribute = $objectManager->get('GoMage\Feed\Model\Attribute')->load($value, 'code');

        $this->_default = $objectManager->create('GoMage\Feed\Model\Feed\Field', [
                'type'  => \GoMage\Feed\Model\Config\Source\Field\TypeInterface::ATTRIBUTE,
                'value' => $attribute->getDefaultValue()
            ]
        );

        $this->_rows = $objectManager->create('GoMage\Feed\Model\Collection');

        $content = $jsonHelper->jsonDecode($attribute->getContent());
        foreach ($content as $data) {
            /** @var \GoMage\Feed\Model\Attribute\Row\Data $rowData */
            $rowData = $objectManager->create('GoMage\Feed\Model\Attribute\Row\Data', ['data' => $data]);

            /** @var \GoMage\Feed\Model\Attribute\Row $row */
            $row = $objectManager->create('GoMage\Feed\Model\Attribute\Row', ['rowData' => $rowData]);

            $this->_rows->add($row);
        }
    }

    /**
     * @param  \Magento\Framework\DataObject $object
     * @return mixed
     */
    public function map(\Magento\Framework\DataObject $object)
    {
        /** @var \GoMage\Feed\Model\Attribute\Row $row */
        foreach ($this->_rows as $row) {
            if ($row->verify($object)) {
                return $row->map($object);
            }
        }
        return $this->_default->map($object);
    }

    /**
     * @return array
     */
    public function getUsedAttributes()
    {
        $attributes = [];
        /** @var \GoMage\Feed\Model\Attribute\Row $row */
        foreach ($this->_rows as $row) {
            $attributes = array_merge($attributes, $row->getUsedAttributes());
        }
        return array_merge($attributes, $this->_default->getUsedAttributes());
    }

}