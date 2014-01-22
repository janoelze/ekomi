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


class Product
{
    protected $productId;
    protected $productName;
    protected $productOther;
    
    
    public function __construct() {
        $this->productOther = array();
    }
    
    
    public function addProductResearch($id) {
        $this->productOther['research']['add'][]['research_id'] = $id;
        return $this;
    }
    
    
    public function setProductImage($image) {
        $this->productOther['image_url'] = $image;
        return $this;
    }
    
    
    public function getProductImage() {
        return (isset($this->productOther['image_url']))
            ? $this->productOther['image_url']
            : null;
    }
}