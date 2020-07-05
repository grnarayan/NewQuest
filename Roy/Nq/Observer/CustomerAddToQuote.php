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

        $cartLimit = $objectManager->get('Roy\Nq\Model\CartLimit');

        $customerOrderedItems = $cartLimit->getCustomerOrderedItems($customerId);

        // Default value of 3 if value not specified
        $storeProductLimit = $this->scopeConfig->getValue('alphonso_store_setting/general/productlimit', \Magento\Store\Model\ScopeInterface::SCOPE_STORE) ?
            $this->scopeConfig->getValue('alphonso_store_setting/general/productlimit', \Magento\Store\Model\ScopeInterface::SCOPE_STORE) : 3;

        if (($customerOrderedItems + $cartItemCount) > $storeProductLimit) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __(sprintf('Too many products in the cart remove (%d) products from the cart. You have ordered (%d) products in the last 30 days',
                    ($customerOrderedItems + $cartItemCount - $storeProductLimit), $customerOrderedItems)));
        }
        return $this;
    }
}
