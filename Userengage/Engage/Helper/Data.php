<?php
namespace Userengage\Engage\Helper;
use Magento\Store\Model\Store;
use Magento\Store\Model\ScopeInterface;
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    const KEY_DATA = 'engage/general/key';
    public function isKeyAvailable($store = null)
    {
        $accountId = $this->scopeConfig->getValue(self::KEY_DATA, ScopeInterface::SCOPE_STORE, $store);
        return $accountId;
    }
}