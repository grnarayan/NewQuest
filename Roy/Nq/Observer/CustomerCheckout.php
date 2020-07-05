<?php

namespace Roy\Nq\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class CustomerCheckout implements ObserverInterface
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
     * @var \Magento\Framework\App\ActionFlag
     */
    protected $actionFlag;

    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $url;

    public function __construct(
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\App\ActionFlag $actionFlag,
        \Magento\Framework\UrlInterface $url,
        \Magento\Framework\App\Response\RedirectInterface $redirect
    )
    {
        $this->customerSession = $customerSession;
        $this->messageManager  = $context->getMessageManager();
        $this->scopeConfig = $scopeConfig;
        $this->actionFlag = $actionFlag;
        $this->url = $url;
    }

    public function execute(Observer $observer)
    {

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
            $this->messageManager->addErrorMessage(
                __(sprintf('Too many products in the cart remove (%d) products from the cart. You have ordered (%d) products in the last 30 days',
                    ($customerOrderedItems + $cartItemCount - $storeProductLimit), $customerOrderedItems)));

            $this->actionFlag->set('', \Magento\Framework\App\Action\Action::FLAG_NO_DISPATCH, true);
            $observer->getControllerAction()->getResponse()->setRedirect($this->url->getUrl('/'));
        }
        return $this;

    }
}
