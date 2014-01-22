<?php
/**
 * eKomi PHP library by flowl.info
 * 
 * @author  Daniel Wendler
 * @see     http://blog.flowl.info/2014/ekomi-php-library/
 * @license https://github.com/flowl/ekomi/blob/master/LICENSE
 * @package eKomi
 */

namespace eKomi;
use eKomi\Product;


class Order
{
    protected $orderId;
    protected $products;
    
    
    public function __construct() {
        $this->products = array();
    }
    
    
    public function setOrderId($orderId) {
        $this->orderId = $orderId;
        return $this;
    }
    
    
    public function getOrderid() {
        return $this->orderId;
    }
    
    
    public function addProduct(Product $product) {
        $this->products[$product->getProductId()] = $product;
        return $this;
    }
    
    
    public function removeProduct(Product $product) {
        unset($this->products[$product->getProductId()]);
        return $this;
    }
    
    
    public function getProducts() {
        return $this->products;
    }
    
    
    public function getProductIds() {
        $ids = array();
        foreach ($this->products as $product) {
            $ids[] = $product->getProductId();
        }
        return $ids;
    }
}