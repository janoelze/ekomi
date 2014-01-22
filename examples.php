<?php
/**
 * eKomi PHP library by flowl.info
 * 
 * @author  Daniel Wendler
 * @see     http://blog.flowl.info/2014/ekomi-php-library/
 * @license https://github.com/flowl/ekomi/blob/master/LICENSE
 * @package eKomi
 */


include('eKomi/eKomi.php');

 
// Instance a new eKomi object:
$ekomi = new eKomi\eKomi;

// Set the interfaceID and password credentials
// and wether you'd like to use https or http.
$ekomi->setUsername('xxxxx')
      ->setPassword('xxxxxxxxxxxxxxxxxxxxxxxxxxxxx')
      ->setForceHttps(true);



// Instance a new Product object.
// Keep in mind that eKomi serves the review page
// using ISO encoding, do not put multibyte (UTF-8)
// characters into the product name.
// The picture must be served using https with a maximum
// width and height of 150px x 150px
$product = new eKomi\Product;
$product->setProductId('10099921')
        ->setProductName('Nike Airmax 90s Limited, white, 41')
        ->setProductImage('https://www.yourdomain.com/images/10099921_small.jpg');
        


// Using the eKomi instance,
// you can register the former Product.
// This returns true or false respectively.
$ekomi->putProduct($product);



// Let's have a new Order
// and put in the Product we already registered.
$order = new eKomi\Order;
$order->setOrderId('999')
      ->addProduct($product);

// Since PHP 5.4 you can also do the following
// to add a Product the an Order:
$order->addProduct((new eKomi\Order)->setProductId('1234-5678')->setProductName(/* ... */)->setProductImage(/* ... */));



// Push the order to the eKomi API
$ekomi->putOrder($order);
// If this succeeded (returns true or false),
// the order has a review link:
echo $order->getReviewLink();



// To fetch product feedback:
while ($productFeedback = $ekomi->getProductFeedback()) {
    echo 'Product ID: ' . $productFeedback->getProductId() . ' ',
         'Rating: ' . $productFeedback->getRating() . '<br />', PHP_EOL;
}



