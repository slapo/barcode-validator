# PHP Barcode
A PHP class for checking EAN8, EAN13, UPC and GTIN barcodes are valid (based on check digit).

[![Travis CI](https://img.shields.io/travis/milindsingh/php-barcodes.svg?maxAge=3600)](https://travis-ci.org/milindsingh/php-barcodes)
[![Packagist](https://img.shields.io/packagist/v/milindsingh/php-barcodes.svg?maxAge=3600)](https://github.com/milindsingh/php-barcodes)
[![Packagist](https://img.shields.io/packagist/dt/milindsingh/php-barcodes.svg?maxAge=3600)](https://github.com/milindsingh/php-barcodes)
[![Packagist](https://img.shields.io/packagist/dm/milindsingh/php-barcodes.svg?maxAge=3600)](https://github.com/milindsingh/php-barcodes)
[![Packagist](https://img.shields.io/packagist/l/milindsingh/php-barcodes.svg?maxAge=3600)](https://github.com/milindsingh/php-barcodes)
[![PHP 7 ready](http://php7ready.timesplinter.ch/milindsingh/php-barcodes/master/badge.svg)](https://travis-ci.org/milindsingh/php-barcodes)


https://packagist.org/packages/milindsingh/php-barcodes

_Note: This project currently does nothing other than have some validation functions. I expect to add additional functionality in the future._

## Installation (with composer)
```
composer require milindsingh/php-barcodes
```

## Usage
```php
// Class instantation
$barcode = '5060411950139';
$bc_validator = new \Ced\Validator\Barcode($barcode);


// Check barcode is in valid format
if ($bc_validator->isValid()) {
	echo 'Valid :)';
} else {
	echo 'Invalid :(';
}


// Get the barcode type
echo 'Barcode is in format of ' . $bc_validator->getType();
// Possible formats returned are:
// (string) "GTIN" which equals constant \Ced\Validator\Barcode::TYPE_GTIN
// (string) "EAN-8" which equals constant \Ced\Validator\Barcode::TYPE_EAN_8
// (string) "EAN" which equals constant \Ced\Validator\Barcode::TYPE_EAN
// (string) "EAN Restricted" which equals constant \Ced\Validator\Barcode::TYPE_EAN_RESTRICTED
// (string) "UPC" which equals constant \Ced\Validator\Barcode::TYPE_UPC
// (string) "UPC Coupon Code" which equals constant \Ced\Validator\Barcode::TYPE_UPC_COUPON_CODE


// Returns the barcode in GTIN-14 format
$bc_validator->getGTIN14()


// Returns the barcode as entered
$bc_validator->getBarcode()
```

## TODO:
* Barcode generation
* GS1-128 barcode generation and interpretation

## Credits
* The original package was created by [Luke Cousins](https://github.com/violuke).
* The barcode validation function was based on [work by Ferry Bouwhuis](http://www.phpclasses.org/package/8560-PHP-Detect-type-and-check-EAN-and-UPC-barcodes.html).
* EAN Restricted format added from the [hassel-it/php-barcodes](https://github.com/hassel-it/php-barcodes) fork.
* Initial unit tests based on work in the [hassel-it/php-barcodes](https://github.com/hassel-it/php-barcodes) fork.
