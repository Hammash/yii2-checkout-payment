yii2 checkout payment extension
===============================
yii2 checkout payment extension

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist hammash/yii2-checkout-payment "*"
```

or add

```
"hammash/yii2-checkout-payment": "*"
```

to the require section of your `composer.json` file.


Usage
-----

1- Set (test/CheckoutController) in api\versions\v1\controllers\CheckoutController.php

2- Create Helper in common/component/Helpers to get data from your DB


3- in main-local.php 
  
  
'checkout'=>[
   
            'class' => 'hammash\checkout\Checkout',
            'secretKey' => 'sk_test_7904bf0b-9ae2-47c1-aa78-0cb962294320',
            'publicKey' => 'pk_test_d1392567-896b-4063-96f7-84e0a0b7976c',
            'debugMode' => true,
            'mode' => 'sandbox',
        ],
        
        
4- EX: 

API  : http://api.altibbiv2.local/v1/checkout/charge

HEADER : 

Authorization: ODIxNjc5MjAxNS0xMS0wOCAxNToxOTo0MDAuOTAwODczNDY4MTY1MTY4

PARAMETER: 

token:card_tok_B011B366-8B32-48F1-97ED-8CFE1C145D99