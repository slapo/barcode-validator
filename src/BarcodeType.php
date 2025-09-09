<?php

namespace Ced\Validator;

/**
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 * @link           http://www.phpclasses.org/package/8560-PHP-Detect-type-and-check-EAN-and-UPC-barcodes.html
 * @author         Ferry Bouwhuis
 */

/**
 * @package Ced\Validator
 */
class BarcodeType
{
    public const TYPE_ASIN = 'ASIN';
    public const TYPE_EAN = 'EAN'; // 13 digits
    public const TYPE_EAN_8 = 'EAN-8';
    public const TYPE_GTIN = 'GTIN'; // 14 digits
    public const TYPE_ISBN_10 = 'ISBN'; // 10 digits excluding dashes
    public const TYPE_ISBN_13 = 'ISBN'; // 13 digits excluding dashes
    public const TYPE_UPC = 'UPC'; // 12 digits
    public const TYPE_UPC_COUPON_CODE = 'UPC Coupon Code';
}
