<?php
namespace Userengage\Engage\Observer;
use Magento\Catalog\Model\ProductFactory;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\RequestInterface;
class Productadd implements ObserverInterface {
    protected $productFactory;
    protected $scopeConfig;
  	const GET_KEY = 'engage/general/key';
    public function __construct(
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    )
    {
        $this->productFactory = $productFactory;
        $this->scopeConfig = $scopeConfig;
    }
    public function execute(\Magento\Framework\Event\Observer $observer) {
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        $key = $this->scopeConfig->getValue(self::GET_KEY, $storeScope);
        $item = $observer->getEvent()->getData('quote_item');
        $item = ( $item->getParentItem() ? $item->getParentItem() : $item );
        $pId = $item->getId();
        $pSku = $item->getSku();
        $pName = $item->getName();
        $quantity = $item->getQty(); 
        $pPrice = $item->getProduct()->getPrice();
        $_SESSION["addproduct"]  = 1;
        $_SESSION["productData"] = array(
            'name' => $pName,
            'sku' => $pSku,
            'quantity' => $quantity,
            'price' => $pPrice,
        );
    }
}
