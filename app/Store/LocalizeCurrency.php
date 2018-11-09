<?php

namespace App\Store;

/**
 * @package Resume Builder
 * @author  Abhishek Prakash <prakashabhishek6262@gmail.com>
 */
class LocalizeCurrency
{
    /**
     * The base currency to convert amount from.
     *
     * @var string
     */
    private $base_currency = '';

    /**
     * The configuration for the openexchange apis.
     *
     * @var array
     */
    private $config = [];

    /**
     * Creates a new instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->base_currency = env('BASE_CURRENCY');
        
        $config = [
            'https' => false,
            'base_currency' => $this->base_currency
        ];

        $this->config = $config;
    }

    /**
     * Converts the supplied amount equivalent to the local currency of
     * the user.
     *
     * @param  float $amount
     *
     * @return float|null
     */
    public function convertToLocalCurrency($amount)
    {
        $currency = $this->getLocalCurrency();

        if (empty($currency)) {
            return null;
        }

        return $amount * $currency['exchange_rate'];;
    }

    /**
     * Returns the currency code for the local currency of the user.
     *
     * @return string|null
     */
    public function getLocalCurrencyCode()
    {
        $currency = $this->getLocalCurrency();

        if (empty($currency)) {
            return null;
        }

        return $currency['currency'];
    }

    /**
     * Returns the details of the local currency.
     *
     * @return array
     */
    public function getLocalCurrency()
    {
        return session()->get('local_currency');
    }

    /**
     * Sets the data of the local currency in the application.
     *
     * @param  array $data
     *
     * @return Object
     */
    private function setLocalCurrency(array $data)
    {
        session()->put('local_currency', $data);

        return $this;
    }

    /**
     * Determines whether the currency details has been set already.
     *
     * @param  string $currency
     *
     * @return bool
     */
    public function hasCurrency(string $currency): bool
    {
        $local = $this->getLocalCurrency();

        if (empty($local)) {
            return false;
        }

        return $local['currency'] === $currency;
    }

    /**
     * Sets the details of the currency supplied into the application.
     *
     * @param  string $currency
     *
     * @return Object
     */
    public function setCurrency(string $currency)
    {
        $local = [
            'currency' => $currency,
            'exchange_rate' => $this->getExchangeRate($this->base_currency, $currency)
        ];

        $this->setLocalCurrency($local);

        return $this;
    }

    /**
     * Returns the exchange rate for the currencies to make the amount
     * equivalent.
     *
     * @param $from
     * @param $to
     *
     * @return float
     */
    private function getExchangeRate($from, $to)
    {
        $url = file_get_contents('https://free.currencyconverterapi.com/api/v5/convert?q=' . $from . '_' . $to . '&compact=ultra');
        $json = json_decode($url, true);
        $rate = implode(" ", $json);
        
        return (float) $rate;
    }
}
