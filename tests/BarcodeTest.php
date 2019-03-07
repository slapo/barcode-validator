<?php

namespace Ced\Barcodes\Tests;

use Ced\Validator\Barcode;

// Nasty work around for testing over multiple PHPUnit versions
if (!class_exists('\PHPUnit_Framework_TestCase') && class_exists('\PHPUnit\Framework\TestCase')) {
    class_alias('\PHPUnit\Framework\TestCase', '\PHPUnit_Framework_TestCase');
}

class BarcodeTest extends \PHPUnit_Framework_TestCase
{

    public function testInit()
    {
        $validator = new Barcode();
        $validator->setBarcode('12345123');
        $this->assertInstanceOf('\Ced\Validator\Barcode', $validator);
    }

    public function testInvalid()
    {
        $validator = new Barcode();
        $validator->setBarcode('string123');
        $this->assertFalse($validator->isValid());
    }

    public function testInvalidLooksLikeBarcode()
    {
        $validator = new Barcode();
        $validator->setBarcode('5060411950138');
        $this->assertFalse($validator->isValid());
    }

    public function testValid()
    {
        $validator = new Barcode();
        $validator->setBarcode('001303050100');
        $this->assertFalse($validator->isValid());
    }

    public function testEanRestricted()
    {
        $validator = new Barcode();
        $validator->setBarcode('2100000030019');
        $this->assertTrue($validator->isValid());
        $this->assertSame(Barcode::TYPE_EAN, $validator->getType());
    }

    public function testEan()
    {
        $validator = new Barcode();
        $validator->setBarcode('8838108476586');
        $this->assertTrue($validator->isValid());
        $this->assertSame(Barcode::TYPE_EAN, $validator->getType());
    }

    public function testEan2()
    {
        $validator = new Barcode();
        $validator->setBarcode('5060411950139');
        $this->assertTrue($validator->isValid());
        $this->assertSame(Barcode::TYPE_EAN, $validator->getType());
    }

//    public function testEan3()
//    {
//        $validator = new Barcode();
//        $validator->setBarcode('0700867967774');
//        $this->assertTrue($validator->isValid());
//        $this->assertSame(Barcode::TYPE_EAN, $validator->getType());
//    }

    public function testUpc()
    {
        $validator = new Barcode();
        $validator->setBarcode('700867967774');
        $this->assertTrue($validator->isValid());
        $this->assertSame(Barcode::TYPE_UPC, $validator->getType());
    }
}
