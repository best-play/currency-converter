<?php

namespace BestPlay;

class CurrencyConverter
{

    /**
     * @var string
     */
    private $currency_from;

    /**
     * @var string
     */
    private $currency_to;

    /**
     * @var int
     */
    private $amount;

    /**
     * @var string
     */
    private $time_period;

    /**
     * @var array
     */
    private $result;

    /**
     * API URL
     */
    const BASE_URL = "https://api.exchangeratesapi.io/";


    /**
     * Function for parsing request.
     *
     * @param string $url API url.
     *
     * @return int|string.
     */
    private function parseRequest($url)
    {
        $content = $this->callJSONUrl($url);
        if($content['error'])
            return $content;

        $rate = $content['rates'][$this->currency_to];
        $result = $rate * $this->amount;

        return $result;
    }

    /**
     * Function to get content via CURL.
     *
     * @param string $url API url.
     *
     * @return mixed
     */
    private function callJSONUrl($url)
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_FAILONERROR, false);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type' => 'application/json'));
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($curl);

        return json_decode($result, true);
    }

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->currency_from = "USD";
        $this->currency_to = "EUR";
        $this->amount = 1;
        $this->time_period = 'latest';
    }

    /**
     * Main function.
     *
     * @return CurrencyConverter.
     */
    public function convertCurrency()
    {
        if ($this->time_period === 'history') {
            $url = self::BASE_URL.$this->time_period."?base=".strtoupper($this->currency_from)."&symbols".strtoupper($this->currency_to);

        } elseif ($this->time_period === 'latest') {
            $url = self::BASE_URL.$this->time_period."?base=".strtoupper($this->currency_from)."&symbols=".strtoupper($this->currency_to);

        } else {
            $url = self::BASE_URL."latest";
        }

        $result = $this->parseRequest($url);

        $this->setResult([
            "from" => $this->currency_from,
            "to" => $this->currency_to,
            "amount" =>$this->amount,
            "result" => is_numeric($result) ? $result : 0,
            "error" => $result['error']
        ]);

        return $this;
    }

    /**
     * @return string
     */
    public function getCurrencyFrom()
    {
        return $this->currency_from;
    }

    /**
     * @param string $currency_from
     */
    public function setCurrencyFrom($currency_from)
    {
        $this->currency_from = $currency_from;
    }

    /**
     * @return string
     */
    public function getCurrencyTo()
    {
        return $this->currency_to;
    }

    /**
     * @param string $currency_to
     */
    public function setCurrencyTo($currency_to)
    {
        $this->currency_to = $currency_to;
    }

    /**
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param int $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return string
     */
    public function getTimePeriod()
    {
        return $this->time_period;
    }

    /**
     * @param string $time_period
     */
    public function setTimePeriod($time_period)
    {
        $this->time_period = $time_period;
    }

    /**
     * @return array
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param array $result
     */
    public function setResult($result)
    {
        $this->result = $result;
    }

    /**
     * @return string
     */
    public function getJsonResult()
    {
        return json_encode($this->result);
    }
}
