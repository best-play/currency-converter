# PHP Currency Converter
PHP class can convert currencies using ExchangeRates API (https://api.exchangeratesapi.io/).

Usage:

Install via composer:

```"best-play/bestplay-currency-converter": "dev-master"```

Example:

```php
<?php

use BestPlay\CurrencyConverter;

require __DIR__ . "/vendor/autoload.php";

$converter = new CurrencyConverter();
$converter->setCurrencyFrom("USD");
$converter->setCurrencyTo("EUR");
$converter->setAmount(1);

echo $converter->convertCurrency()->getJsonResult();
```