<?php

use PHPUnit\Framework\TestCase;

class DeliveryCalculatorTest extends TestCase
{
    public $dlvCalc;
    public $provider;

    protected function setUp()
    {
        $this->dlvCalc = new johnykvsky\Utils\DeliveryCalculator();
        $this->provider = new johnykvsky\Utils\PolishDeliveryProvider();
        $this->dlvCalc->setShippingProvider($this->provider);
        $this->dlvCalc->setDeliveryProvider($this->provider);
    }

    public function testSetShippingProvider()
    {
        $this->dlvCalc->setShippingProvider($this->provider);
        $this->assertEquals($this->provider, $this->dlvCalc->shippingProvider);
    }

    public function testSetDeliveryProvider()
    {
        $this->dlvCalc->setDeliveryProvider($this->provider);
        $this->assertEquals($this->provider, $this->dlvCalc->deliveryProvider);
    }

    public function testSetAdditionalNonWorkingDays()
    {
        $this->dlvCalc->setAdditionalNonWorkingDays(array(1));
        $this->assertEquals(array(1), $this->dlvCalc->additionalNonWorkingDays);
    }

    public function testGetAdditionalNonWorkingDays()
    {
        $this->dlvCalc->setAdditionalNonWorkingDays(array(1));
        $this->assertEquals(array(1), $this->dlvCalc->getAdditionalNonWorkingDays());
    }

    public function testCalculateDeliveryDate()
    {
        $result = $this->dlvCalc->calculateDeliveryDate(14, '2017-10-20')->format('Y-m-d');
        $this->assertEquals('2017-11-10', $result);
    }

    public function testYearChangeWithDifferentTimezoneDelivery()
    {
        $provider = new johnykvsky\Utils\PolishDeliveryProvider();
        $provider->timezone = 'Pacific/Chatham';
        $this->dlvCalc->setShippingProvider($provider);
        $result = $this->dlvCalc->calculateDeliveryDate(5, '2017-12-26')->format('Y-m-d');
        $this->assertEquals('2018-01-02', $result);
    }

    public function testBadTimezone()
    {
        $provider = new johnykvsky\Utils\PolishDeliveryProvider();
        $provider->timezone = 'Pacific/Chatham1';
        $this->expectException(\Exception::class);
        $this->dlvCalc->setShippingProvider($provider);
        $result = $this->dlvCalc->calculateDeliveryDate(4, '2017-12-26');
    }
}
