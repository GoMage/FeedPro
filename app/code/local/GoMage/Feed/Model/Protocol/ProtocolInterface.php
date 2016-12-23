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
interface GoMage_Feed_Model_Protocol_ProtocolInterface
{
    const FTP = 0;
    const SSH = 1;

    /**
     * @param  string $source
     * @param  string $dest
     * @return bool
     */
    public function execute($source, $dest);

}