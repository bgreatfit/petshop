# Currency Exchange Package
A Laravel package for converting Euro amounts into different currencies.


## How to Install locally
Edit composer.json to look like this
```json 
  {
  "repositories": [
    {
      "type": "path",
      "url": "./packages/bgreatfit/currencyexchange"
    }
  ],
   "require": {
       "bgreatfit/currencyexchange": "dev-main"
   }
}
```
then run:
```bash 
    composer update 
```

## Publish config
publish the config file with:
```bash 
    php artisan vendor:publish --provider="BgreatFit\CurrencyExchange\CurrencyExchangeServiceProvider"
```

## Usage
Default Route (GET) [/exchange-rate?amount=100&to=GBP]

swagger docs is https://localhost:8080/api/v1/documentation 

### Testing
```bash 
docker-compose  exec app php artisan test   ./packages/bgreatfit/currencyexchange/tests
```
