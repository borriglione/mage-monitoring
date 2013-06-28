<?php
/**
 * @category    Rom
 * @package     Rom_Monitoring
 * @copyright   Copyright (c) 2013 ROM - Agence de communication (http://www.rom.fr/)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author      André Herrn <info@andre-herrn.de>
 */
class Rom_Monitoring_Adminhtml_ConfigController extends Mage_Adminhtml_Controller_Action
{

    /**
     * check if the current user is allowed to execute controller action
     * 
     * @return string
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')
                ->isAllowed('system/config/rommonitoring');
    }

    /**
     * check if the current user is allowed to see this section
     * 
     * @param string
     */
    protected function _checkSectionAllowed($section)
    {
        if (false == Mage::getSingleton('admin/session')
                ->isAllowed('system/config/rommonitoring/' . $section)) {
            $this->forward('denied');
        }
    }

    /**
     * Render the statistic window
     * 
     * @return void
     */
    public function statisticAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }
}
