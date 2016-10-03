<?php

namespace Academe\SagePay\Psr7\Money;

/**
 * Defines a currency.
 * Only supports currencies that SagePay supports.
 * TODO: create a CurrencyInterface for this.
 */

use UnexpectedValueException;

class Currency
{
    /**
     * @var string ISO 4217 currency code
     */
    protected $code;

    /**
     * Currencies supported by SagePay.
     * 'digits' are the number of digits after the decimal point.
     * Some currencies only allow minor units of a certain size, but
     * none of these yet.
     *
     * @var array
     */
    protected static $currencies = [
        // Original three currencies.
        'GBP' => ['digits' => 2, 'symbol' => '£', 'name' => 'Pound sterling'],
        'EUR' => ['digits' => 2, 'symbol' => '€', 'name' => 'Euro'],
        'USD' => ['digits' => 2, 'symbol' => '€', 'name' => 'US dollar'],
        // Support is expanding for further currencies.
        'CAD' => ['digits' => 2, 'symbol' => '$', 'name' => 'Canadian dollar'],
        'AUD' => ['digits' => 2, 'symbol' => '$', 'name' => 'Australian dollar'],
        'NZD' => ['digits' => 2, 'symbol' => '$', 'name' => 'New Zealand dollar'],
        'ZAR' => ['digits' => 2, 'symbol' => 'R', 'name' => 'South African rand'],
    ];

    /**
     * @param string $code The ISO 4217 three-character currency code
     */
    public function __construct($code)
    {
        if (isset(static::$currencies[$code])) {
            $this->code = $code;
        } else {
            throw new UnexpectedValueException(sprintf('Unsupported currency code "%s"', $code));
        }
    }

    /**
     * @return string The ISO 4217 three-character currency code
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return mixed The number of digits in the decimal subunit
     */
    public function getDigits()
    {
        return static::$currencies[$this->code]['digits'];
    }

    /**
     * The symbols will be one or more UTF-8 characters.
     * getName and getSymbol are handy for display and logging, but not essential,
     * so they are not a part of the interface.
     *
     * @return string The name of the currency
     */
    public function getName()
    {
        return static::$currencies[$this->code]['name'];
    }

    /**
     * @return string The currency symbol, made of one or more UTF-8 characters
     */
    public function getSymbol()
    {
        return static::$currencies[$this->code]['symbol'];
    }

    /**
     * @return array Details of all the supported currencies
     */
    public static function supportedCurrencies()
    {
        return static::$currencies;
    }
}
