# PHP Barcode
A PHP class for checking EAN8, EAN13, UPC and GTIN barcodes are valid (based on check digit).

[![Travis CI](https://travis-ci.org/milindsingh/validator.svg?branch=master)](https://travis-ci.org/milindsingh/validator)
[![Packagist](https://img.shields.io/packagist/v/milindsingh/validator.svg?maxAge=3600)](https://github.com/milindsingh/validator)
[![Packagist](https://img.shields.io/packagist/dt/milindsingh/validator.svg?maxAge=3600)](https://github.com/milindsingh/validator)
[![Packagist](https://img.shields.io/packagist/dm/milindsingh/validator.svg?maxAge=3600)](https://github.com/milindsingh/validator)
[![Packagist](https://img.shields.io/packagist/l/milindsingh/validator.svg?maxAge=3600)](https://github.com/milindsingh/validator)
[![PHP 7 ready](http://php7ready.timesplinter.ch/milindsingh/validator/master/badge.svg)](https://travis-ci.org/milindsingh/validator)


https://packagist.org/packages/milindsingh/validator

_Note: This project currently does nothing other than have some validation functions. I expect to add additional functionality in the future._

## Installation (with composer)
```
composer require milindsingh/validator
```

## Usage
```php
// Class instantation
$barcode = '5060411950139';
$validator = new \Ced\Validator\Barcode();
$validator->setBarcode($barcode);

// Check barcode is in valid format
if ($validator->isValid()) {
	echo 'Valid :)';
} else {
	echo 'Invalid :(';
}


// Get the barcode type
echo 'Barcode is in format of ' . $validator->getType();
// Possible formats returned are:
// (string) "GTIN" which equals constant \Ced\Validator\Barcode::TYPE_GTIN
// (string) "EAN-8" which equals constant \Ced\Validator\Barcode::TYPE_EAN_8
// (string) "EAN" which equals constant \Ced\Validator\Barcode::TYPE_EAN
// (string) "EAN Restricted" which equals constant \Ced\Validator\Barcode::TYPE_EAN_RESTRICTED
// (string) "UPC" which equals constant \Ced\Validator\Barcode::TYPE_UPC
// (string) "UPC Coupon Code" which equals constant \Ced\Validator\Barcode::TYPE_UPC_COUPON_CODE


// Returns the barcode in GTIN-14 format
$validator->getGTIN14()


// Returns the barcode as entered
$validator->getBarcode()
```

## TODO:
* Barcode generation
* GS1-128 barcode generation and interpretation

## Credits
* The original package was created by [Luke Cousins](https://github.com/violuke).
* The barcode validation function was based on [work by Ferry Bouwhuis](http://www.phpclasses.org/package/8560-PHP-Detect-type-and-check-EAN-and-UPC-barcodes.html).
* EAN Restricted format added from the [hassel-it/php-barcodes](https://github.com/hassel-it/php-barcodes) fork.
* Initial unit tests based on work in the [hassel-it/php-barcodes](https://github.com/hassel-it/php-barcodes) fork.
