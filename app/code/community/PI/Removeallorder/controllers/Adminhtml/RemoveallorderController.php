<?php
class PI_Removeallorder_Adminhtml_RemoveallorderController extends Mage_Adminhtml_Controller_action
{
	public function indexAction()
	{
		$order = Mage::getModel('sales/order')->getCollection();
		foreach($order as $order)
		{
			$deleteorderIds[] = $order->getId();
		}
		$this->massDelete($deleteorderIds);
		
		$this->_redirectReferer('*/*/');
	}
	 protected function _initOrder()
    {
        $id = $this->getRequest()->getParam('order_id');
        $order = Mage::getModel('sales/order')->load($id);

        if (!$order->getId()) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('This order no longer exists.'));
			$this->_redirectReferer('adminhtml/sales_order/index');
        }
        Mage::register('sales_order', $order);
        Mage::register('current_order', $order);
        return $order;
    }
	public function deleteAction() {
		if($order = $this->_initOrder()) {
			try {
     		    $order->delete()->save();
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Order was successfully deleted'));
				$this->_redirectReferer('adminhtml/sales_order/index');
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('order_ids')));
			}
		}
		$this->_redirectReferer('adminhtml/sales_order/index');
	}
    public function massDeleteAction() {
        $deleteorderIds = $this->getRequest()->getParam('order_ids');
		if(!is_array($deleteorderIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($deleteorderIds as $deleteorderId) {
					Mage::getModel('sales/order')->load($deleteorderId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($deleteorderIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
		
		$this->_redirectReferer('adminhtml/sales_order/index');
    }
	
	public function massDelete($deleteorderIds) {
        if(!is_array($deleteorderIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('No Record Found'));
        } else {
            try {
                foreach ($deleteorderIds as $deleteorderId) {
					Mage::getModel('sales/order')->load($deleteorderId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($deleteorderIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
		
		
    }
}
