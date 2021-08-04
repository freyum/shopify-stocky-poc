# Shopify Stocky PoC

This sample was created as Proof of Concept to upload CSV file to stock transfers on Shopify's Stocky app automatically.

## Installation

- PHP >= 8
- chromedriver https://github.com/symfony/panther#installing-panther
- `cp config/parameters.php.dist config/parameters.php`
- Put your session cookie on `config/parameters.php`
- Edit sample.csv, replace by your file (need barcode & quantity columns)


Then, just run : `php app.php`
