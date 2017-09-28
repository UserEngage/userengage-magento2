<?php
namespace Userengage\Engage\Observer;
use Magento\Framework\Event\ObserverInterface;
class AfterPlaceOrder implements ObserverInterface
{
    /**
     * Order Model
     *
     * @var \Magento\Sales\Model\Order $order
     */
    protected $order;
    protected $_customerRepositoryInterface;
     public function __construct(
        \Magento\Sales\Model\Order $order,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepositoryInterface
    )
    {
        $this->_customerRepositoryInterface = $customerRepositoryInterface;
        $this->order = $order;
    }
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $_SESSION["orderCreated"] = 1;
    }
}


