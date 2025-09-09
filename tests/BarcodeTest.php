<?php

namespace Ced\Barcodes\Tests;

use Ced\Validator\Barcode;
use Ced\Validator\BarcodeType;
use PHPUnit\Framework\TestCase;

class BarcodeTest extends TestCase
{
    private function getTestedInstanceForBarcode(string $barcode): Barcode
    {
        return (new Barcode())->setBarcode($barcode);
    }

    public function testInit(): void
    {
        $this->assertInstanceOf(
            Barcode::class,
            $this->getTestedInstanceForBarcode('12345123'),
        );
    }

    public function testInvalid(): void
    {
        $this->assertFalse(
            $this->getTestedInstanceForBarcode('string123')->isValid()
        );
    }

    public function testInvalidLooksLikeBarcode(): void
    {
        $this->assertFalse(
            $this->getTestedInstanceForBarcode('5060411950138')->isValid()
        );
    }

    public function testValid(): void
    {
        $this->assertFalse(
            $this->getTestedInstanceForBarcode('001303050100')->isValid()
        );
    }

    public function testEanRestricted(): void
    {
        $validator = $this->getTestedInstanceForBarcode('2100000030019');
        $this->assertTrue($validator->isValid());
        $this->assertSame(BarcodeType::TYPE_EAN, $validator->getType());
    }

    public function testEan(): void
    {
        $validator = $this->getTestedInstanceForBarcode('8838108476586');
        $this->assertTrue($validator->isValid());
        $this->assertSame(BarcodeType::TYPE_EAN, $validator->getType());
    }

    public function testEan2(): void
    {
        $validator = $this->getTestedInstanceForBarcode('5060411950139');
        $this->assertTrue($validator->isValid());
        $this->assertSame(BarcodeType::TYPE_EAN, $validator->getType());
    }

//    public function testEan3()
//    {
//        $validator = new Barcode();
//        $validator->setBarcode('0700867967774');
//        $this->assertTrue($validator->isValid());
//        $this->assertSame(Barcode::TYPE_EAN, $validator->getType());
//    }

    public function testUpc(): void
    {
        $validator = $this->getTestedInstanceForBarcode('700867967774');
        $this->assertTrue($validator->isValid());
        $this->assertSame(BarcodeType::TYPE_UPC, $validator->getType());
    }
}
