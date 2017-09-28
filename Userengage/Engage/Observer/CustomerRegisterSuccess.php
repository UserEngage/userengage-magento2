<?php
namespace Userengage\Engage\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Customer\Api\CustomerRepositoryInterface;
class CustomerRegisterSuccess implements ObserverInterface
{
    /** @var CustomerRepositoryInterface */
    protected $customerRepository;
    protected $scopeConfig;
    /**
     * @param CustomerRepositoryInterface $customerRepository
     */
    public function __construct(
        CustomerRepositoryInterface $customerRepository,\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    )
    {
        $this->customerRepository = $customerRepository;
        $this->scopeConfig = $scopeConfig;
    }
    public function execute(Observer $observer)
    {
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        $om = \Magento\Framework\App\ObjectManager::getInstance();
        $_SESSION["userRegistered"] = 1;
    }
}