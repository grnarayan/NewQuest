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

    public function getLimit()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_ALPHONOSO_PRODUCT_LIMIT);
    }
}


