<?php

namespace Roy\Nq\Observer;


use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class CustomerAddToQuote implements ObserverInterface
{
    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $messageManager;
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * CustomerAddToCart constructor.
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig

    )
    {
        $this->customerSession = $customerSession;
        $this->messageManager  = $context->getMessageManager();
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        if (!$this->customerSession->isLoggedIn()) {
            $this->messageManager->addErrorMessage(__('Login to be able to add to cart'));
            return $this;
        }

        // Remote scenario - but maybe possible
        if (!$customerId = $this->customerSession->getCustomerId()) {
            $this->messageManager->addWarningMessage(__('Customer not found'));
            return $this;
        }

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $cartItemCount = $objectManager->get('\Magento\Checkout\Helper\Cart')->getSummaryCount();

        $customerOrderedItems = $this->getCustomerOrderedItems($customerId);

        $storeProductLimit = $this->scopeConfig->getValue('alphonso_store_setting/general/productlimit', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        if ($customerOrderedItems+$cartItemCount > $storeProductLimit) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __(sprintf('Too many products in the cart remove (%d) products from the cart. You have ordered (%d) products in the last 30 days',
                    $customerOrderedItems+$cartItemCount-$storeProductLimit, $customerOrderedItems)));
        }
        return $this;
    }

    /**
     * @param string $customerId
     * @return bool|int|void
     */
    private function getCustomerOrderedItems($customerId = '')
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
