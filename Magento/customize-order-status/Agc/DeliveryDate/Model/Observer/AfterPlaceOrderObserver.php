<?php 
public function execute(\Magento\Framework\Event\Observer $observer)
{
	/* @var $order \Magento\Sales\Model\Order */
    $order = $observer->getEvent()->getOrder();
    $order_data = $order->getData();

	file_put_contents('order.txt', print_r("\n" . $order_data,true), FILE_APPEND);
}
?>