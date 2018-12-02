<?php
/**
 * Created by PhpStorm.
 * User: muntaser
 * Date: 11/27/18
 * Time: 4:33 PM
 */

namespace hammash\checkout;

use com\checkout\ApiClient;
use com\checkout\ApiServices\Charges\RequestModels\CardTokenChargeCreate;
use com\checkout\ApiServices\RecurringPayments\RequestModels\BaseRecurringPayment;
use com\checkout\ApiServices\RecurringPayments\RequestModels\PlanUpdate;
use com\checkout\ApiServices\RecurringPayments\RequestModels\PlanWithCardTokenCreate;
use com\checkout\ApiServices\RecurringPayments\ResponseModels\PaymentPlan;
use com\checkout\ApiServices\Reporting\RequestModels\TransactionFilter;
use com\checkout\ApiServices\SharedModels\Address;
use com\checkout\ApiServices\SharedModels\Phone;
use com\checkout\ApiServices\SharedModels\Product;
use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use Httpful\Request;
use com\checkout as baseCheckout;


final class Helper
{

    /** @var  $secretKey string */
    public $secretKey;
    public $env = 'sandbox';
    public $debugMode = false;
    public $connectTimeout = 60;
    public $readTimeout = 60;

    protected $api;

    /** @var  $apiClient ApiClient */
    protected $apiClient;

    public function init()
    {

        if (empty($this->secretKey)) {
            throw new \Exception('Required secretKey');
        }

        $this->apiClient = new ApiClient($this->secretKey, $this->env, $this->debugMode, $this->connectTimeout, $this->readTimeout);
    }

    public static function  getAddress()
    {
        $billingDetails = new Address();

        $billingDetails->setAddressLine1('1 Glading Fields');
        $billingDetails->setAddressLine2('Second line');
        $billingDetails->setPostcode('N16 2BR');
        $billingDetails->setCountry('GB');
        $billingDetails->setCity('London');
        $billingDetails->setState('Uk');
        $billingDetails->setPhone(Helper::getPhone());

        return $billingDetails;
    }

    public static function  getPhone()
    {
        $phone  = new Phone();
        $phone->setNumber("203 583 44 55");
        $phone->setCountryCode("44");
        return $phone;
    }

    //plan
    public static function  getProduct()
    {

        $product = new Product();
        $product->setName('Product-'.Helper::getRandName());
        $product->setDescription('Description-'.Helper::getRandName());
        $product->setQuantity(rand(0,5));
        $product->setShippingCost(rand(0,10));
        $product->setSku('Sku-'.Helper::getRandName().'-'.rand(0,100));
        $product->setTrackingUrl('http://www.'.Helper::getRandName().'.com');

        return $product;
    }

    /**
     * @return TransactionFilter
     */
    public static function getTransactionFilterRequestModel() {
        $requestModel = new TransactionFilter();

        $requestModel->setFromDate('2015-07-06T13:57:34.450Z');
        $requestModel->setToDate('2015-07-10T13:57:34.450Z');
        $requestModel->setPageSize(10);
        $requestModel->setPageNumber(5);
        $requestModel->setSortColumn('ID');
        $requestModel->setSortOrder('ASC');
        $requestModel->setSearch('Authorised');
        $requestModel->setFilters(array(
            array(
                'action'    => 'include',
                'field'     => 'status',
                'operator'  => 'CONTAINS',
                'value'     => 'Authorised'
            ),
            array(
                'action'    => 'include',
                'field'     => 'email',
                'operator'  => 'CONTAINS',
                'value'     => '@'
            ),
        ));

        return $requestModel;
    }

    public static function getCardToken()
    {

        $cardTokenConfig['authorization'] = "pk_test_88a9f52e-17e3-4a3f-a11e-669757454288" ;

        $Api = \CheckoutApi_Api::getApi();
        $cardTokenConfig['postedParam'] = array (


            'number' => '4543474002249996',
            'expiryMonth' => 06,
            'expiryYear' => 2017,
            'cvv' => 956,

        );
        $respondCardToken = $Api->getCardToken( $cardTokenConfig );

        if($respondCardToken->isValid()) {
            return  $respondCardToken->getId();
        }

        return null;

    }

    public static function getCardTokenChargeModel()
    {
        $cardTokenChargePayload = new CardTokenChargeCreate();
        /** @var TYPE_NAME $this */
        $cardTokenChargePayload->setEmail(Helper::getRandName().'@'.Helper::getRandName().'.com');
        $cardTokenChargePayload->setAutoCapture('N');
        $cardTokenChargePayload->setAutoCaptime('0');
        $cardTokenChargePayload->setValue('100');
        $cardTokenChargePayload->setCurrency('usd');
        $cardTokenChargePayload->setTrackId('TrackId-'.rand(0,1000));
        $cardTokenChargePayload->setShippingDetails(Helper::getAddress());
        $cardTokenChargePayload->setProducts(Helper::getProduct());
        $cardTokenChargePayload->setTransactionIndicator(1);
        return $cardTokenChargePayload;
    }

    public static function getRecurringRequestModel (){
        /**  @var PaymentPlan  $recurringResponse **/
        $recurringRequestModel = new BaseRecurringPayment();

        $recurringRequestModel->setName('testPlan14');
        $recurringRequestModel->setPlanTrackId('planTrackId3');
        $recurringRequestModel->setAutoCapTime(0.5);
        $recurringRequestModel->setCurrency('USD');
        $recurringRequestModel->setValue(1650);
        $recurringRequestModel->setCycle('3w');
        $recurringRequestModel->setRecurringCount(25);
        return $recurringRequestModel;

    }

    public static function getRecurringMultipleRequestModel ($multipleRequestModel){

        /**  @var baseCheckout\ApiServices\RecurringPayments\ResponseModels\PaymentPlan $recurringResponse **/
        $arrayToSubmit=[];
       foreach ($multipleRequestModel as $requestModel ){

           $recurringRequestModel = new BaseRecurringPayment();

           $recurringRequestModel->setName('testPlan14');
           $recurringRequestModel->setPlanTrackId('planTrackId3');
           $recurringRequestModel->setAutoCapTime(0.5);
           $recurringRequestModel->setCurrency('USD');
           $recurringRequestModel->setValue(1650);
           $recurringRequestModel->setCycle('3w');
           $recurringRequestModel->setRecurringCount(25);
           $arrayToSubmit[] = $recurringRequestModel;

       }

        return $arrayToSubmit;
    }

    public static function getUpdatePaymentPlan($requestModel){
        /** $recurringResponse returns "ok" when plan update is successful**/
        $recurringRequestModel = new PlanUpdate();

        $recurringRequestModel->setPlanId('rp_XXXXXXXXX');
        $recurringRequestModel->setName('testPlan14');
        $recurringRequestModel->setPlanTrackId('planTrackId3');
        $recurringRequestModel->setAutoCapTime(0.5);
        $recurringRequestModel->setValue(1650);
        $recurringRequestModel->setStatus(4);
        return $recurringRequestModel;

    }

    public static function getPlanWithCardTokenCreate($product,$shippingDetails,$recurringPayment){
        $recurringRequestModel = new PlanWithCardTokenCreate();

        $recurringRequestModel->setCurrency("GBP");
        $recurringRequestModel->setChargeMode(3);
        $recurringRequestModel->setValue(100);
        $recurringRequestModel->setProducts($product);
        $recurringRequestModel->setShippingDetails($shippingDetails);
        $recurringRequestModel->setUdf1("Test UDF");
        $recurringRequestModel->setPaymentPlans($recurringPayment);
        $recurringRequestModel->setCardToken('sample_card_token');
        $recurringRequestModel->setEmail('test.email@checkout.com');
        $recurringRequestModel->setTransactionIndicator("testTransactionIndicator");
        return $recurringRequestModel ;


    }

    public static function getRandName()
    {
        $charset = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
        $base = strlen($charset);
        $result = '';

        $nowR = explode(' ', microtime());
        $now = $nowR[1];
        while ($now >= $base){
            $i = $now % $base;
            $result = $charset[$i] . $result;
            $now /= $base;
        }
        return substr($result, -5);
    }

}
