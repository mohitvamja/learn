<?php        
namespace Sale\Paymenttext\Observer;

use \Magento\Framework\Event\ObserverInterface;
use \Magento\Framework\Event\Observer;

class HandleOrderAttrs implements ObserverInterface
{
    public function execute(Observer $observer)
    {
        $order = $observer->getOrder();
        $quote = $observer->getQuote();
        //Load the values
        $bank_name = $quote->getData("bank_name");
        $order->setData('bank_name', $bank_name)
            ->save();
    }
}