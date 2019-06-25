<?php
// Class to set expected and actual delivery date.
class DeliveryDate_DeliveryDate_Model_Observer
{
	public function CheckoutSubmitallAfterDeliveryDate(Varien_Event_Observer $observer)
	{
		$event = $observer->getEvent();
		$order = $event->getOrder();
		$order->setData('expected_delivery_date', date('Y-m-d H:i:s'));
		$order->setData('actual_delivery_date', date('Y-m-d H:i:s'));
		$order->save();
	}		
}
