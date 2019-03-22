<?php

namespace Ced\Validator;

/**
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 * @link           http://www.phpclasses.org/package/8560-PHP-Detect-type-and-check-EAN-and-UPC-barcodes.html
 * @author         Ferry Bouwhuis
 * @version        1.0.1
 * @LastChange     2014-04-13
 */

/**
 * Class Barcode
 * @package Ced\Validator
 */
class Barcode
{
    const TYPE_GTIN = 'GTIN'; // 14 digits
    const TYPE_EAN = 'EAN'; // 13 digits
    const TYPE_UPC = 'UPC'; // 12 digits
    const TYPE_ISBN_10 = 'ISBN'; // 10 digits excluding dashes
    const TYPE_ISBN_13 = 'ISBN'; // 13 digits excluding dashes
    const TYPE_EAN_8 = 'EAN-8';
    const TYPE_ASIN = 'ASIN';
    const TYPE_UPC_COUPON_CODE = 'UPC Coupon Code';
    public $barcode;
    public $type;
    public $gtin;
    public $valid;

    public $allowedIdentifiers = array(
        self::TYPE_GTIN,
        self::TYPE_EAN,
        self::TYPE_EAN_8,
        self::TYPE_UPC,
        self::TYPE_ASIN,
        self::TYPE_ISBN_10,
        self::TYPE_ISBN_13,
    );

    public function getBarcode()
    {
        // For Walmart
        /*if (($this->type == self::TYPE_EAN || $this->type == self::TYPE_EAN_8) && strlen($this->barcode) < 14) {
            $zeros = 14 - strlen($this->barcode);
            $prefix = '';
            for ($i = $zeros; $i <= $zeros; $i++) {
                $prefix .= '0';
            }
            return $prefix . $this->barcode;
        }*/

        return $this->barcode;
    }

    public function setBarcode($barcode)
    {
        $this->barcode = (string)$barcode;
        // Trims parsed string to remove unwanted whitespace or characters
        $this->barcode = trim($this->barcode);
        if (preg_match('/[^0-9]/', $this->barcode)) {
            $isbn = $this->isIsbn($this->barcode);
            if ($isbn == false) {
                $asin = $this->isAsin($this->barcode);
            }
        } else {
            $this->gtin = $this->barcode;
            $length = strlen($this->gtin);
            if (($length > 11 && $length <= 14) || $length == 8) {
                $zeros = 18 - $length;
                $length = null;
                $fill = '';
                for ($i = 0; $i < $zeros; $i++) {
                    $fill .= '0';
                }
                $this->gtin = $fill . $this->gtin;
                $fill = null;
                $this->valid = true;
                if (!$this->checkDigitValid()) {
                    $this->valid = false;
                } elseif (substr($this->gtin, 5, 1) > 2) {
                    // EAN / JAN / EAN-13 code
                    $this->type = self::TYPE_EAN;
                } elseif (substr($this->gtin, 6, 1) == 0 && substr($this->gtin, 0, 10) == 0) {
                    // EAN-8 / GTIN-8 code
                    $this->type = self::TYPE_EAN_8;
                } elseif (substr($this->gtin, 5, 1) <= 0) {
                    // UPC / UCC-12 GTIN-12 code
                    if (substr($this->gtin, 6, 1) == 5) {
                        $this->type = self::TYPE_UPC_COUPON_CODE;
                    } else {
                        $this->type = self::TYPE_UPC;
                    }
                } elseif (substr($this->gtin, 0, 6) == 0) {
                    // GTIN-14 code
                    $this->type = self::TYPE_GTIN;
                } else {
                    // EAN code
                    $this->type = self::TYPE_EAN;
                }
            }
        }
        return $this;
    }

    public function isIsbn($barcode)
    {
        $regex = '/\b(?:ISBN(?:: ?| ))?((?:97[89])?\d{9}[\dx])\b/i';
        if (preg_match($regex, str_replace('-', '', $barcode), $matches)) {
            if (strlen($matches[1]) === 10) {
                $this->valid = true;
                $this->type = self::TYPE_ISBN_10;
            } else {
                $this->valid = true;
                $this->type = self::TYPE_ISBN_13;
            }
            return true;
        }
        return false; // No valid ISBN found
    }

    public function isAsin($barcode)
    {
        $ptn = "/B[0-9]{2}[0-9A-Z]{7}|[0-9]{9}(X|0-9])/";
        if (preg_match($ptn, $barcode, $matches)) {
            $this->valid = true;
            $this->type = self::TYPE_ASIN;
            return true;
        }
        return false;
    }

    public function checkDigitValid()
    {
        $calculation = 0;
        for ($i = 0; $i < (strlen($this->gtin) - 1); $i++) {
            $calculation += $i % 2 ? $this->gtin[$i] * 1 : $this->gtin[$i] * 3;
        }
        if (substr(10 - (substr($calculation, -1)), -1) != substr($this->gtin, -1)) {
            return false;
        } else {
            return true;
        }
    }

    public function getType()
    {
        // For Walmart
        /*if ($this->type == self::TYPE_EAN || $this->type == self::TYPE_EAN_8) {
            return self::TYPE_GTIN;
        }*/
        return $this->type;
    }

    public function getGTIN14()
    {
        return (string)substr($this->gtin, -14);
    }

    public function isValid()
    {
        // For Walmart
        if (!in_array($this->type, $this->allowedIdentifiers)) {
            $this->valid = false;
        }
        return $this->valid;
    }
}
