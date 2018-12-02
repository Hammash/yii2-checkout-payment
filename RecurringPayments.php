<?php
/**
 * Created by PhpStorm.
 * User: muntaser
 * Date: 11/28/18
 * Time: 8:46 AM
 */

namespace hammash\checkout;


use com\checkout as baseCheckout;
use com\checkout\ApiServices\RecurringPayments\RequestModels\BaseRecurringPayment;
use com\checkout\ApiServices\RecurringPayments\ResponseModels\PaymentPlan;
use com\checkout\ApiServices\RecurringPayments\ResponseModels\RecurringPayment;
use com\checkout\helpers\ApiHttpClientCustomException;
use Exception;

class RecurringPayments
{
    /** @var  $apiClient baseCheckout\ApiClient */
    public $apiClient;

    /**
     * @var self
     */
    private static $instance = null;

    public static function getInstance($apiClient)
    {
        if (self::$instance === null) {
            self::$instance = new self($apiClient);
        }
        return self::$instance;
    }

    private function __construct($apiClient)
    {
        $this->apiClient = $apiClient;
    }

    /**
     * Creates a new payment plan
     * @param BaseRecurringPayment $requestModel
     * @return RecurringPayment
     */
    public function createSinglePlan(BaseRecurringPayment $requestModel)
    {
        // Create a recurring payment service instance
        $recurringPaymentService = $this->apiClient->recurringPaymentService();

        try {
            $recurringRequestModel = Helper::getRecurringRequestModel();
            $recurringResponse = $recurringPaymentService->createSinglePlan($recurringRequestModel);
            return $recurringResponse;

        } catch (baseCheckout\helpers\ApiHttpClientCustomException $e) {
            echo 'Caught exception: ', $e->getErrorMessage(), "\n";
            echo 'Caught exception Error Code: ', $e->getErrorCode(), "\n";
            echo 'Caught exception Event id: ', $e->getEventId(), "\n";
        }

    }

    /**
     * @param $plansArray
     * @return RecurringPayment
     */

    public function createMultiplePlans($plansArray)
    {

        // Create a recurring payment service instance
        $recurringPaymentService = $this->apiClient->recurringPaymentService();

        try {
            $arrayToSubmit = Helper::getRecurringMultipleRequestModel($plansArray);
            $recurringResponse = $recurringPaymentService->createMultiplePlans($arrayToSubmit);

            return $recurringResponse;

        } catch (baseCheckout\helpers\ApiHttpClientCustomException $e) {
            echo 'Caught exception: ', $e->getErrorMessage(), "\n";
            echo 'Caught exception Error Code: ', $e->getErrorCode(), "\n";
            echo 'Caught exception Event id: ', $e->getEventId(), "\n";
        }
    }

    /**
     * @param baseCheckout\ApiServices\RecurringPayments\RequestModels\PlanUpdate $requestModel
     * @return baseCheckout\ApiServices\SharedModels\OkResponse
     */
    public function updatePaymentPlan(baseCheckout\ApiServices\RecurringPayments\RequestModels\PlanUpdate $requestModel)
    {
        // Create a recurring payment service instance
        $recurringPaymentService = $this->apiClient->recurringPaymentService();

        try {

            $recurringRequestModel = Helper::getUpdatePaymentPlan($requestModel);
            $recurringResponse = $recurringPaymentService->updatePlan($recurringRequestModel);
            return $recurringResponse;

        } catch (baseCheckout\helpers\ApiHttpClientCustomException $e) {
            echo 'Caught exception: ', $e->getErrorMessage(), "\n";
            echo 'Caught exception Error Code: ', $e->getErrorCode(), "\n";
            echo 'Caught exception Event id: ', $e->getEventId(), "\n";
        }

    }

    /**
     * Cancel Plan
     * @param $customerPlanId
     * @return baseCheckout\ApiServices\SharedModels\OkResponse
     */

    public function cancelPaymentPlan($customerPlanId)
    {
        // Create a recurring payment service instance
        $recurringPaymentService = $this->apiClient->recurringPaymentService();

        try {
            /** $recurringResponse returns "ok" when plan is successfully canceled**/

            $recurringResponse = $recurringPaymentService->cancelPlan($customerPlanId);
            return $recurringResponse;

        } catch (baseCheckout\helpers\ApiHttpClientCustomException $e) {
            echo 'Caught exception: ', $e->getErrorMessage(), "\n";
            echo 'Caught exception Error Code: ', $e->getErrorCode(), "\n";
            echo 'Caught exception Event id: ', $e->getEventId(), "\n";
        }


    }

    /**
     * Get Plan
     * @param $customerPlanId
     * @return PaymentPlan
     */

    public function getPaymentPlan($PlanId)
    {


        // Create a recurring payment service instance
        $recurringPaymentService = $this->apiClient->recurringPaymentService();

        try {
            /**  @var PaymentPlan $recurringResponse * */

            $recurringResponse = $recurringPaymentService->getPlan($PlanId);
            return $recurringResponse;

        } catch (baseCheckout\helpers\ApiHttpClientCustomException $e) {
            echo 'Caught exception: ', $e->getErrorMessage(), "\n";
            echo 'Caught exception Error Code: ', $e->getErrorCode(), "\n";
            echo 'Caught exception Event id: ', $e->getEventId(), "\n";
        }

    }

    /**
     * @param baseCheckout\ApiServices\RecurringPayments\RequestModels\PlanWithPaymentTokenCreate $requestModel
     * @return baseCheckout\ApiServices\Charges\ResponseModels\Charge
     */

    public function createCustomerPlanWithPaymentToken(baseCheckout\ApiServices\RecurringPayments\RequestModels\PlanWithPaymentTokenCreate $requestModel)
    {


        // Create a recurring payment service instance
        $recurringPaymentService = $this->apiClient->recurringPaymentService();

        try {

            /**  @var baseCheckout\ApiServices\Charges\ResponseModels\Charge $recurringResponse * */

            $product = Helper::getProduct();
            $shippingDetails = Helper::getAddress();

            $recurringPayment = Helper::getRecurringRequestModel();

            $recurringRequestModel = Helper::getPlanWithCardTokenCreate($product, $shippingDetails, $recurringPayment);
            $recurringResponse = $recurringPaymentService->createPlanWithCardToken($recurringRequestModel);

            return $recurringResponse;

        } catch (baseCheckout\helpers\ApiHttpClientCustomException $e) {
            echo 'Caught exception: ', $e->getErrorMessage(), "\n";
            echo 'Caught exception Error Code: ', $e->getErrorCode(), "\n";
            echo 'Caught exception Event id: ', $e->getEventId(), "\n";
        }

    }


    /**
     * @param baseCheckout\ApiServices\RecurringPayments\RequestModels\CustomerPlanUpdate $requestModel
     * @param $card_id
     * @return baseCheckout\ApiServices\SharedModels\OkResponse
     */
    public function updateCustomerPlan(baseCheckout\ApiServices\RecurringPayments\RequestModels\CustomerPlanUpdate $requestModel, $card_id)
    {
        // Create a recurring payment service instance
        $recurringPaymentService = $this->apiClient->recurringPaymentService();

        try {
            /** $recurringResponse returns "ok" when plan update is successful**/
            $recurringRequestModel = new baseCheckout\ApiServices\RecurringPayments\RequestModels\CustomerPlanUpdate();

            $recurringRequestModel->setCardId($card_id);
            $recurringRequestModel->setStatus(1);
            $recurringResponse = $recurringPaymentService->updateCustomerPlan($recurringRequestModel);
            return $recurringResponse;

        } catch (ApiHttpClientCustomException $e) {
            echo 'Caught exception: ', $e->getErrorMessage(), "\n";
            echo 'Caught exception Error Code: ', $e->getErrorCode(), "\n";
            echo 'Caught exception Event id: ', $e->getEventId(), "\n";
        }

    }


    public function cancelCustomerPlan($customerPlanId)
    {

        // Create a recurring payment service instance
        $recurringPaymentService = $this->apiClient->recurringPaymentService();

        try {
            /** $recurringResponse returns "ok" when plan is successfully canceled**/
            $recurringResponse = $recurringPaymentService->cancelCustomerPlan($customerPlanId);
            return $recurringResponse;

        } catch (ApiHttpClientCustomException $e) {
            echo 'Caught exception: ', $e->getErrorMessage(), "\n";
            echo 'Caught exception Error Code: ', $e->getErrorCode(), "\n";
            echo 'Caught exception Event id: ', $e->getEventId(), "\n";
        }

    }


    public function getCustomerPlan($customerPlanId)
    {

        // Create a recurring payment service instance
        $recurringPaymentService = $this->apiClient->recurringPaymentService();

        try {
            /**  @var PaymentPlan $recurringResponse * */

            $recurringResponse = $recurringPaymentService->getCustomerPlan($customerPlanId);
            return $recurringResponse;

        } catch (ApiHttpClientCustomException $e) {
            echo 'Caught exception: ', $e->getErrorMessage(), "\n";
            echo 'Caught exception Error Code: ', $e->getErrorCode(), "\n";
            echo 'Caught exception Event id: ', $e->getEventId(), "\n";
        }
    }

}

