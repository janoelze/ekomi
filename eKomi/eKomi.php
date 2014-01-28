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

include('Order.php');
include('Product.php');

use eKomi\Product;
use eKomi\Order;


class eKomi
{
    /**
     * @var resource 
     */
    protected $productFeedbackResource;

    /**
     * @var resource 
     */
    protected $shopFeedbackResource;
    
    /**
     * @var resource
     */
    protected $curl;
    
    /**
     * @var integer 
     */
    protected $interfaceId;
    
    /**
     * @var string 
     */
    protected $interfacePassword;
    
    /**
     * @var string 
     */
    protected $version;
    
    /**
     * @var boolean 
     */
    protected $forceHttps;
    
    
    /**
     * Construct a new eKomi object
     * 
     * @param string  $interfaceId
     * @param string  $interfacePassword
     * @param boolean $forceHttps
     * @param string  $version
     */
    public function __construct(
            $interfaceId = null,
            $interfacePassword = null,
            $forceHttps = true,
            $version = 'cust-1.0.0') {
        
        $this->interfaceId       = $interfaceId;
        $this->interfacePassword = $interfacePassword;
        $this->forceHttps        = $forcehttps;
        $this->version           = $version;
        
        $this->curl = curl_init();
        
        $options = array(
            CURLOPT_HEADER         => false,
            CURLOPT_FORBID_REUSE   => false,
            CURLOPT_NOPROGRESS     => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_USERAGENT      => 'eKomi PHP library/0.1 (+flowl.info)',
        );
        curl_setopt_array($this->curl, $options);
    }
    
    
    /**
     * Destruct the eKomi instance
     */
    public function __destruct() {
        if (is_resource($this->productFeedbackResource)) {
            fclose($this->productFeedbackResource);
        }
        
        if (is_resource($this->shopFeedbackResource)) {
            fclose($this->productFeedbackResource);
        }
    }
    
    
    /**
     * Register a product with the eKomi service
     * 
     * @param \eKomi\Product $product
     * @return boolean
     */
    public function putProduct(Product $product) {
        $response = unserialize(file_get_contents(
            (($this->forceHttps === true) ? 'https' : 'http')
            . '://api.ekomi.de/v2/putProduct'
            . '?auth='
            . $this->interfaceId . '|' . $this->interfacePassword
            . '&version='
            . $this->version
            . '&product_id='
            . rawurlencode($product->getProductId())
            . '&product_name='
            . rawurlencode($product->getProductName())
            . '&product_other='
            . rawurlencode(serialize($product->getProductOther()))
        ));

        return ($response['done'] == 1)
            ? true
            : false;
    }
    
    
    /**
     * Register an order with the eKomi service
     * 
     * @param \eKomi\Order $order
     * @return boolean
     */
    public function putOrder(Order &$order) {
        $response = unserialize(file_get_contents(
            (($this->forceHttps === true) ? 'https' : 'http')
            . '://api.ekomi.de/v2/putOrder'
            . '?auth='
            . $this->interfaceId . '|' . $this->interfacePassword
            . '&version='
            . $this->version
            . '&order_id='
            . $order->getOrderId()
            . '&product_ids='
            . rawurlencode(implode(',', $order->getProductIds()))
        ));

        if ($response['done'] == 1 && isset($response['link'])) {
            $order->setReviewLink($response['link']);
            return true;
        } else {
            return false;
        }
    }
    
    
    /**
     * Fetch product reviews from the eKomi service
     * @return array|boolean
     */
    public function getProductFeedback() {
        if (!is_resource($this->productFeedbackResource)) {
            $this->productFeedbackResource = fopen('php://memory', 'r+');
            fputs($this->productFeedbackResource,
                file_get_contents(
                    (($this->forceHttps === true) ? 'https' : 'http')
                    . '://api.ekomi.de/get_productfeedback.php?'
                    . 'interface_id='
                    . $this->interfaceId
                    . '&interface_pw='
                    . $this->interfacePassword
                    . '&version='
                    . $this->version
                    . '&type=csv&filter=all'
                )
            );
            rewind($this->productFeedbackResource);
        }
        
        $csv = fgetcsv($this->productFeedbackResource);
        return (is_array($csv)) ? $csv : false;
    }
    
    
    public function getResearchQuestions() {
        //Options are "results", "questions", "answers", "campaigns" or "campaign_questions". Default is "results".
        //http://api.ekomi.de/get_research.php?type=csv&interface_id=&interface_pw=&content=questions
    }

    
    public function getProductResearchResults() {
        //Options are "results", "questions" and "answers". Default is "results".
        //http://api.ekomi.de/get_productresearch.php?interface_id=&interface_pw=&type=csv&content=results
    }
    
    public function getProductResearchQuestions() {
        //http://api.ekomi.de/get_productresearch.php?interface_id=&interface_pw=&type=csv&content=questions
    }
    
    public function getProductResearchAnswers() {
        //http://api.ekomi.de/get_productresearch.php?interface_id=&interface_pw=&type=csv&content=answers
    }
    
    /**
     * Fluent getter / setter for the members following
     */
    
    public function getInterfaceId() {
        return $this->interfaceId;
    }
    

    public function setInterfaceId($interfaceId) {
        $this->interfaceId = $interfaceId;
        return $this;
    }

    
    public function getInterfacePassword() {
        return $this->interfacePassword;
    }

    
    public function setInterfacePassword($interfacePassword) {
        $this->interfacePassword = $interfacePassword;
        return $this;
    }

    
    public function getVersion() {
        return $this->version;
    }
    

    public function setVersion($version) {
        $this->version = $version;
        return $this;
    }
    
    
    public function getForceHttps() {
        return $this->forceHttps;
    }
    

    public function setForceHttps($forceHttps) {
        $this->forceHttps = $forceHttps;
        return $this;
    }
}
