<?php
namespace Userengage\Engage\Block;
use \Magento\Framework\App\Bootstrap;
class Track extends \Magento\Framework\View\Element\Template
{
    /**
     * Userengage key
     *
     * @var \Userengage\Engage\Helper\Data
     */
    protected $_userEngageData = null;
    protected $_orderCollectionFactory;
    protected $_subscriber;
    protected $recentlyViewed;
    protected $wishlistProvider;
    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Userengage\Engage\Helper\Data $userEngageData
     * @param array $data
     */
    public function __construct(\Magento\Framework\View\Element\Template\Context $context, \Magento\Wishlist\Controller\WishlistProviderInterface $wishlistProvider, \Userengage\Engage\Helper\Data $userEngageData, \Magento\Newsletter\Model\Subscriber $subscriber, \Magento\Reports\Block\Product\Viewed $recentlyViewed,
        array $data = []
    )
    {
        $this->_userEngageData  = $userEngageData;
        $this->_subscriber      = $subscriber;
        $this->recentlyViewed   = $recentlyViewed;
        $this->wishlistProvider = $wishlistProvider;
        parent::__construct($context, $data);
    }
    /**
     * Get config
     *
     * @param string $path
     * @return mixed
     */
    public function getUserData()
    {
        $om              = \Magento\Framework\App\ObjectManager::getInstance();
        $customerSession = $om->get('Magento\Customer\Model\Session');
        if ($customerSession->isLoggedIn()) {
            include('app/bootstrap.php');
            $bootstrap     = Bootstrap::create(BP, $_SERVER);
            $objectManager = $bootstrap->getObjectManager();
            $dataoutput    = ',customer_id: "' . $customerSession->getCustomer()->getId() . '" ,name: "' . $customerSession->getCustomer()->getName() . '", email: "' . $customerSession->getCustomer()->getEmail() . '"';
            $test          = $customerSession->getCustomer()->getDefaultBilling();
            $url           = \Magento\Framework\App\ObjectManager::getInstance();
            $storeManager  = $url->get('\Magento\Store\Model\StoreManagerInterface');
            $state         = $objectManager->get('\Magento\Framework\App\State');
            $state->setAreaCode('frontend');
            $websiteId       = $storeManager->getWebsite()->getWebsiteId();
            // Get Store ID
            $store           = $storeManager->getStore();
            $storeId         = $store->getStoreId();
            $customerFactory = $objectManager->get('\Magento\Customer\Model\CustomerFactory');
            $customer        = $customerFactory->create();
            $customer->setWebsiteId($websiteId);
            $customer->load($customerSession->getCustomer()->getId()); // load customer by using ID
            $data            = $customer->getData();
            $defShipp        = $data["default_shipping"];
            $groupid         = $data["group_id"];
            $create_at       = $data["created_at"];
            $customerObj     = $om->create('Magento\Customer\Model\Customer')->load($customerSession->getCustomer()->getId());
            $customerAddress = array();
            foreach ($customerObj->getAddresses() as $address) {
                $customerAddress[] = $address->toArray();
            }
            $country_name = '';
            $company      = '';
            $country_id   = '';
            $postcode     = '';
            $street       = '';
            $telephone    = '';
            $city         = '';
            foreach ($customerAddress as $customerAddres) {
                if ($test == $customerAddres["entity_id"]) {
                    
                    $company    = $customerAddres["company"];
                    $country_id = $customerAddres["country_id"];
                    
                    $postcode  = $customerAddres["postcode"];
                    $street    = $customerAddres["street"];
                    $telephone = $customerAddres["telephone"];
                    $city      = $customerAddres["city"];
                    if ($country_id !== '') {
                        $country_name = $objectManager->get('\Magento\Directory\Model\Country')->load($country_id)->getName();
                    }
                }
            }
            $checkSubscriber = $this->_subscriber->loadByCustomerId($customerSession->getCustomer()->getId());
            
            if ($checkSubscriber->isSubscribed()) {
                $subscribe = 1;
            } else {
                $subscribe = 0;
            }
            $orderDatamodel = $objectManager->get('Magento\Sales\Model\Order')->getCollection();
            
            $wishlist            = '';
            $currentUserWishlist = $this->wishlistProvider->getWishlist();
            if ($currentUserWishlist) {
                $wishlistItems = $currentUserWishlist->getItemCollection();
                foreach ($wishlistItems as $_product) {
                    $wishlist .= $_product->getProductId() . ' - ' . $_product->getProductName() . ',';
                }
            }
            $totalOrders    = 0;
            $totalOrdersSum = 0;
            foreach ($orderDatamodel as $orderDatamodel1) {
               $totalOrdersSum += $orderDatamodel1->getData()["total_due"];
               $totalOrders++;
            }
            $dataoutput .= ',cutomer_created_at:  "' . $create_at . '",customer_company: "' . $company . '",customer_newsletter: "' . $subscribe . '", customer_location:  "' . $city . '",customer_wishlist: "' . $wishlist . '" , cutomer_postcode: "' . $postcode . '", cutomer_street:  "' . $street . '", cutomer_phone:  "' . $telephone . '" , customer_country:  "' . $country_name . '",customer_orders:  "' . $totalOrders . '",customer_orders_total_sum:  "' . $totalOrdersSum . '"';
            return $dataoutput;
        } else {
            return '';
        }
    }
    public function getKey()
    {
        return $this->_userEngageData->isKeyAvailable();
    }
    
}