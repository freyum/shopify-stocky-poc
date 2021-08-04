<?php

use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Panther\Client;

require_once __DIR__ . '/vendor/autoload.php';

$parameters = require_once __DIR__ . '/config/parameters.php';

$client = Client::createChromeClient();
// load the page to initialize client
$client->request('GET', 'https://stocky.shopifyapps.com/dashboard');
$client->getCookieJar()->clear();

$cookie = new Cookie('_stockyhq_session', $parameters['session_cookie'], null, '/', 'stocky.shopifyapps.com');
$client->getCookieJar()->set($cookie);

// open modal for stock transfers to create a new stock transfers
$client->request('GET', 'https://stocky.shopifyapps.com/stock_transfers/new');
$client->executeScript('document.querySelector(".new_stock_transfer .btn-primary").click()');

// get stock transfers id
$urlXpl          = explode('/', $client->getCurrentURL());
$stockTransferId = end($urlXpl);

// go to import CSV page
$crawler = $client->request('GET', 'https://stocky.shopifyapps.com/multi_imports/new?field_to_import=transfer&stock_transfer_id=' . $stockTransferId);

// upload file
$form = $crawler->selectButton('Next >')->form();
$form['multi_import[file_url]']->upload(__DIR__ . '/sample.csv');

// wait for upload
$client->waitForElementToContain('.progress .bar', 'Uploading done');

// submit form
$client->submit($form);

// then select options
$client->executeScript('document.querySelector("#indentifier_column").selectedIndex = 1'); // choose Barcode
$client->executeScript('document.querySelector("#indentifier_type_column").selectedIndex = 1');; // choose Barcode
$client->executeScript('document.querySelector("#value_column").selectedIndex = 2');; // choose Quantity

$client->clickLink('Next >');

// send stock transferts
$client->request('GET', 'https://stocky.shopifyapps.com/stock_transfers/' . $stockTransferId);
$client->clickLink('Send');

// done
echo 'Done' . PHP_EOL;
