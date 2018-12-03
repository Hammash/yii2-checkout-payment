<?php
/**
 * Created by PhpStorm.
 * User: muntaser
 * Date: 11/28/18
 * Time: 4:33 PM
 */

namespace api\versions\v1\controllers;


use api\models\Member;

use common\behaviors\ValidateRequestBehavior;
use hammash\checkout\Helper;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\helpers\ArrayHelper;

class CheckoutController extends AltibbiController
{
    public $modelClass = Member::class;

    //public $token = 'card_tok_48113265-CDA8-4008-BB9C-D3AE66DF21F7';

    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'validateParams' => [
                'class' => ValidateRequestBehavior::className(),
                'payloadFieldName' => 'payload',
                'rules' => [
                    //actionName => fields to be required
                    'charge' => [
                        'required' => ['token'],
                    ],

                ],

            ],
            'authenticator' => [
                'class' => CompositeAuth::className(),
                'authMethods' => [
                    HttpBasicAuth::className(),
                    HttpBearerAuth::className(),
                    QueryParamAuth::className(),
                ],

            ],
        ]);
    }

    /**
     * @return string
     */
    public function actionGetPublicKey()
    {

        try {
            $publicKey = \Yii::$app->checkout->publicKey;
            return $this->sendSuccessResponse($publicKey);

        } catch (\Exception $e) {
            return $this->sendFailedResponse($e->getMessage());
        }
    }


    public function actionCharge()
    {

        try {

            $cardToken = $this->requestParams['token'];

            $cardTokenModel = Helper::getCardTokenChargeModel();
            $cardTokenModel->setCardToken($cardToken);

            $verifyPayment = \Yii::$app->checkout->charges()->chargeWithCardToken($cardTokenModel);

            $capture = \Yii::$app->checkout->charges()->captureCharge($verifyPayment->getId());

            return $this->sendSuccessResponse($capture);

        } catch (\Exception $e) {
            return $this->sendFailedResponse($e->getMessage());
        }
    }

    public function actionCancelPayment()
    {

        $chargeId = 'charge_221AEADDE74J76BD2F18';
        $trackId = '';
        try {
            $cancelPayment = \Yii::$app->checkout->charges()->voidCharge($chargeId, $trackId);
            return $cancelPayment;

        } catch (\Exception $e) {
            return $this->sendFailedResponse($e->getMessage());
        }
    }

    public function actionRefundPayment()
    {
        $chargeId = 'charge_221AEADDE74J76BD2F18';
        $value = null;
        try {
            $refundPayment = \Yii::$app->checkout->charges()->refundCharge($chargeId, $value);
            return $refundPayment;
        } catch (\Exception $e) {
            return $this->sendFailedResponse($e->getMessage());
        }

    }

}