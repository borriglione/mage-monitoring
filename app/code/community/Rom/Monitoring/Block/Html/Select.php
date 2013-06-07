<?php
/**
 * @category    Rom
 * @package     Rom_Monitoring
 * @copyright   Copyright (c) 2013 ROM - Agence de communication (http://www.rom.fr/)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author      André Herrn <info@andre-herrn.de>
 */
class Rom_Monitoring_Block_Html_Select extends Mage_Core_Block_Html_Select
{
    /**
     * Return output in one line
     *
     * @return string
     */
    public function _toHtml()
    {
        return trim(preg_replace('/\s+/', ' ',parent::_toHtml()));
    }
}
