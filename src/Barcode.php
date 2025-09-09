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
 * @version        1.0.1
 * @LastChange     2014-04-13
 */

/**
 * Class Barcode
 *
 * @package Ced\Validator
 */
class Barcode
{
    public const TYPE_ASIN = BarcodeType::TYPE_ASIN;
    public const TYPE_EAN = BarcodeType::TYPE_EAN; // 13 digits
    public const TYPE_EAN_8 = BarcodeType::TYPE_EAN_8;
    public const TYPE_GTIN = BarcodeType::TYPE_GTIN; // 14 digits
    public const TYPE_ISBN_10 = BarcodeType::TYPE_ISBN_10; // 10 digits excluding dashes
    public const TYPE_ISBN_13 = BarcodeType::TYPE_ISBN_13; // 13 digits excluding dashes
    public const TYPE_UPC = BarcodeType::TYPE_UPC; // 12 digits
    public const TYPE_UPC_COUPON_CODE = BarcodeType::TYPE_UPC_COUPON_CODE;

    public string $barcode;
    public ?string $type = null;
    public string $gtin;
    public bool $valid;

    public array $allowedIdentifiers = [
        BarcodeType::TYPE_GTIN,
        BarcodeType::TYPE_EAN,
        BarcodeType::TYPE_EAN_8,
        BarcodeType::TYPE_UPC,
        BarcodeType::TYPE_ASIN,
        BarcodeType::TYPE_ISBN_10,
        BarcodeType::TYPE_ISBN_13,
    ];

    public function getBarcode(): string
    {
        return $this->barcode;
    }

    public function setBarcode(string $barcode): self
    {
        // Trims parsed string to remove unwanted whitespace or characters
        $this->barcode = trim((string)$barcode);

        if (preg_match('/[^0-9]/', $this->barcode)) {
            if (false === $this->isIsbn($this->barcode)) {
                $this->isAsin($this->barcode);
            }

            return $this;
        }

        $this->gtin = $this->barcode;
        $this->detectTypeAndValidate($this->barcode);

        return $this;
    }

    /**
     * Refactor this class later so that validation and detection aren't done as side effects.
     */
    public function detectTypeAndValidate(string $barcode): self
    {
        $length = strlen($this->gtin);
        $hasRequiredLength = ($length > 11 && $length <= 14) || $length == 8;

        if (!$hasRequiredLength) {
            return $this;
        }

        $zeros = 18 - $length;
        $this->gtin = str_repeat('0', $zeros) . $this->gtin;
        $this->valid = true;

        if (!$this->checkDigitValid()) {
            $this->valid = false;
        } elseif (substr($this->gtin, 5, 1) > 2) {
            // EAN / JAN / EAN-13 code
            $this->type = BarcodeType::TYPE_EAN;
        } elseif (substr($this->gtin, 6, 1) == 0 && substr($this->gtin, 0, 10) == 0) {
            // EAN-8 / GTIN-8 code
            $this->type = BarcodeType::TYPE_EAN_8;
        } elseif (substr($this->gtin, 5, 1) <= 0) {
            // UPC / UCC-12 GTIN-12 code
            if (substr($this->gtin, 6, 1) == 5) {
                $this->type = BarcodeType::TYPE_UPC_COUPON_CODE;
            } else {
                $this->type = BarcodeType::TYPE_UPC;
            }
        } elseif (substr($this->gtin, 0, 6) == 0) {
            // GTIN-14 code
            $this->type = BarcodeType::TYPE_GTIN;
        } else {
            // EAN code
            $this->type = BarcodeType::TYPE_EAN;
        }

        return $this;
    }

    public function isIsbn(string $barcode): bool
    {
        $regex = '/\b(?:ISBN(?:: ?| ))?((?:97[89])?\d{9}[\dx])\b/i';
        if (preg_match($regex, str_replace('-', '', $barcode), $matches)) {
            if (strlen($matches[1]) === 10) {
                $this->valid = true;
                $this->type = BarcodeType::TYPE_ISBN_10;
            } else {
                $this->valid = true;
                $this->type = BarcodeType::TYPE_ISBN_13;
            }

            return true;
        }

        return false; // No valid ISBN found
    }

    public function isAsin(string $barcode): bool
    {
        $ptn = "/B[0-9]{2}[0-9A-Z]{7}|[0-9]{9}(X|0-9])/";

        if (preg_match($ptn, $barcode, $matches)) {
            $this->valid = true;
            $this->type = BarcodeType::TYPE_ASIN;

            return true;
        }

        return false;
    }

    public function checkDigitValid(): bool
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function getGTIN14(): string
    {
        return (string)substr($this->gtin, -14);
    }

    public function isValid(): bool
    {
        if (empty($this->type)) {
            return false;
        }

        // TODO: This should be handled by injecting a list of valid types, so everyone can set their own.
        // For Walmart
        if (!in_array($this->type, $this->allowedIdentifiers)) {
            $this->valid = false;
        }

        return $this->valid;
    }
}
