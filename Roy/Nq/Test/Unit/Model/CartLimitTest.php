<?php

namespace Roy\Nq\Test\Unit\Model;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManagerHelper;

/**
 * Class CartTest
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class CartLimitTest extends \PHPUnit\Framework\TestCase
{
    /** @var \Magento\Checkout\Model\Cart */
    protected $cart;

    /** @var ObjectManagerHelper */
    protected $objectManagerHelper;

    /** @var \Magento\Checkout\Model\Session|\PHPUnit_Framework_MockObject_MockObject */
    protected $checkoutSessionMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $customerSessionMock;

    /** @var \Magento\CatalogInventory\Api\Data\StockItemInterface|\PHPUnit_Framework_MockObject_MockObject */
    protected $stockItemMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $scopeConfigMock;

    /**
     * @var \Magento\Quote\Model\Quote|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $quoteMock;

    protected function setUp()
    {
        $this->checkoutSessionMock = $this->createMock(\Magento\Checkout\Model\Session::class);
        $this->customerSessionMock = $this->createMock(\Magento\Customer\Model\Session::class);
        $this->scopeConfigMock = $this->createMock(\Magento\Framework\App\Config\ScopeConfigInterface::class);
        $this->quoteMock = $this->createMock(\Magento\Quote\Model\Quote::class);
        $this->eventManagerMock = $this->createMock(\Magento\Framework\Event\ManagerInterface::class);
    }

    public function testCartLimit()
    {

    }

}
