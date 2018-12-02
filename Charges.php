<?php
/**
 * Created by PhpStorm.
 * User: muntaser
 * Date: 11/26/18
 * Time: 4:06 PM
 */


namespace hammash\checkout;

use com\checkout as baseCheckout;
use com\checkout\ApiServices\Charges\RequestModels\ChargeCapture;
use com\checkout\ApiServices\Charges\RequestModels\ChargeRefund;
use com\checkout\ApiServices\Charges\RequestModels\ChargeUpdate;
use com\checkout\ApiServices\Charges\RequestModels\ChargeVoid;
use com\checkout\ApiServices\Charges\ResponseModels\Charge;
use com\checkout\helpers\ApiHttpClientCustomException;

/**
 * Class Charges
 * @package hammash\checkout
 */
class Charges
{
    /** @var  $apiClient baseCheckout\ApiClient */
    public $apiClient;

    /**
     * @var self
     */
    private static $instance = null;

    /**
     * @param $apiClient
     * @return Charges
     */
    public static function getInstance($apiClient)
    {
        if (self::$instance === null) {
            self::$instance = new self($apiClient);
        }
        return self::$instance;
    }

    /**
     * Charges constructor.
     * @param $apiClient
     */
    private function __construct($apiClient)
    {
        $this->apiClient = $apiClient;
    }



    /**
     * Creates a charge with cardToken.
     * @return Charge
     */

    public function chargeWithCardToken($cardTokenModel)
    {

        $chargeService = $this->apiClient->chargeService();

        try {

            $chargeResponse = $chargeService->chargeWithCardToken($cardTokenModel);
            return $chargeResponse;

        } catch (ApiHttpClientCustomException $e) {
            echo 'Caught exception Message: ', $e->getErrorMessage(), "\n";
            echo 'Caught exception Error Code: ', $e->getErrorCode(), "\n";
            echo 'Caught exception Event id: ', $e->getEventId(), "\n";
        }


    }


    /**
     * Authorize a payment
     * @param $payment_tocken
     * @return Charge
     */


    public function verifyChargeByPaymentToken($payment_token)
    {

        $charge = $this->apiClient->chargeService();
        try {
            /**  @var Charge $ChargeRespons * */
            $ChargeResponse = $charge->verifyCharge($payment_token);

            return $ChargeResponse;

        } catch (ApiHttpClientCustomException $e) {
            echo 'Caught exception Message: ', $e->getErrorMessage(), "\n";
            echo 'Caught exception Error Code: ', $e->getErrorCode(), "\n";
            echo 'Caught exception Event id: ', $e->getEventId(), "\n";
        }

    }


    /**
     * Capture a charge
     * @return Charge
     */

    public function captureCharge($charge_id,$amount = null)
    {
        $charge = $this->apiClient->chargeService();

        $chargeCapturePayload = new ChargeCapture();

        $chargeCapturePayload->setChargeId($charge_id);
        if (!empty($amount)){
        $chargeCapturePayload->setValue('100');
        }
        try {
            $ChargeResponse = $charge->CaptureCardCharge($chargeCapturePayload);
            return $ChargeResponse;

        } catch (ApiHttpClientCustomException $e) {
            echo 'Caught exception Message: ', $e->getErrorMessage(), "\n";
            echo 'Caught exception Error Code: ', $e->getErrorCode(), "\n";
            echo 'Caught exception Event id: ', $e->getEventId(), "\n";
        }
    }

    /**
     * Void a charge
     * @return Charge
     */

    public function voidCharge($chargeId,$trackId)
    {

        $charge = $this->apiClient->chargeService();

        $chargePayload = new ChargeVoid();
        $chargePayload->setTrackId($trackId);

        try {
            $ChargeResponse = $charge->voidCharge($chargeId, $chargePayload);
            return $ChargeResponse;

        } catch (ApiHttpClientCustomException $e) {
            echo 'Caught exception Message: ', $e->getErrorMessage(), "\n";
            echo 'Caught exception Error Code: ', $e->getErrorCode(), "\n";
            echo 'Caught exception Event id: ', $e->getEventId(), "\n";
        }
    }

    /**
     * Refund a charge
     * @return Charge
     */

    public function refundCharge($chargeId,$value=null)
    {

        $charge = $this->apiClient->chargeService();

        $chargeCapturePayload = new ChargeRefund();
        $chargeCapturePayload->setChargeId($chargeId);
        if (!empty($value)){
            $chargeCapturePayload->setValue($value);
        }

        try {
            $ChargeResponse = $charge->refundCardChargeRequest($chargeCapturePayload);
            return $ChargeResponse;

        } catch (ApiHttpClientCustomException $e) {
            echo 'Caught exception Message: ', $e->getErrorMessage(), "\n";
            echo 'Caught exception Error Code: ', $e->getErrorCode(), "\n";
            echo 'Caught exception Event id: ', $e->getEventId(), "\n";
        }
    }

    /**
     * Update a charge
     * @return Charge
     */

    public function updateCharge()
    {

        $charge = $this->apiClient->chargeService();

        $chargeUpdatePayload = new ChargeUpdate();

        $chargeUpdatePayload->setChargeId('charge_221AEADDE74J76BD2F18');
        $chargeUpdatePayload->setDescription('Test charge');
        $chargeUpdatePayload->setMetadata(array('test' => 'value'));

        try {

            $ChargeResponse = $charge->UpdateCardCharge($chargeUpdatePayload);
            return $ChargeResponse;

        } catch (ApiHttpClientCustomException $e) {
            echo 'Caught exception Message: ', $e->getErrorMessage(), "\n";
            echo 'Caught exception Error Code: ', $e->getErrorCode(), "\n";
            echo 'Caught exception Event id: ', $e->getEventId(), "\n";
        }
    }

    /**
     * @param $chargeId
     * @return Charge
     */

    public function getCharge($chargeId)
    {
        // create a charge serive
        $charge = $this->apiClient->chargeService();

        try {
            $chargeResponse = $charge->getCharge($chargeId);
            return $chargeResponse;

        } catch (ApiHttpClientCustomException $e) {
            echo 'Caught exception Message: ', $e->getErrorMessage(), "\n";
            echo 'Caught exception Error Code: ', $e->getErrorCode(), "\n";
            echo 'Caught exception Event id: ', $e->getEventId(), "\n";
        }
    }

}

?>