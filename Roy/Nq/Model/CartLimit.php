<?php
namespace Roy\Nq\Model;

use Roy\Nq\Api\CartLimitInterface;

class CartLimit implements CartLimitInterface
{

    // XML path for Alphonso Store Product Limit
    private const XML_PATH_ALPHONOSO_PRODUCT_LIMIT = 'alphonso_store_setting/general/productlimit';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * CartLimit constructor.
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @return int
     */
    public function getLimit()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_ALPHONOSO_PRODUCT_LIMIT);
    }

    /**
     * @param string $customerId
     * @return bool|int|void
     */
    public function getCustomerOrderedItems($customerId = '')
    {
        if (!$customerId) return false;

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $orderCollection = $objectManager->create('\Magento\Sales\Model\ResourceModel\Order\Collection');

        //Get all completed orders in the last 30 days
        //Sort ASC is default
        //$orderCollection->addAttributeToFilter('customer_id', $customerId)->addAttributeToFilter('status','complete')->addAttributeToFilter('created_at', array('gteq' => $daysToLookBack))->load();
        $daysToLookBack = date('Y-m-d', strtotime('-30 days'));
        $orderCollection->addAttributeToFilter('customer_id', $customerId)->addAttributeToFilter('created_at', array('gteq' => $daysToLookBack))->addAttributeToSort('created_at')->load();

        $productsOrdered = 0;
        foreach ($orderCollection as $order) {
            $productsOrdered += count($order->getAllVisibleItems());
        }
        return $productsOrdered;
    }

}


