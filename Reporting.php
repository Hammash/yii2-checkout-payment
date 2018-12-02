<?php
/**
 * Created by PhpStorm.
 * User: muntaser
 * Date: 11/28/18
 * Time: 8:47 AM
 */


namespace hammash\checkout;

use com\checkout\ApiClient;
use com\checkout\ApiServices\Reporting\RequestModels\TransactionFilter;
use com\checkout\ApiServices\Reporting\ResponseModels\ChargebackList;
use com\checkout\ApiServices\Reporting\ResponseModels\TransactionList;
use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use Httpful\Request;
use com\checkout as baseCheckout;

class Reporting
{
    /** @var  $apiClient baseCheckout\ApiClient */
    public $apiClient;

    /**
     * @var self
     */
    private static $instance = null;

    public static function getInstance($apiClient){
        if(self::$instance === null){
            self::$instance = new self($apiClient);
        }
        return self::$instance;
    }
    private function __construct($apiClient)
    {
        $this->apiClient = $apiClient;
    }


    public function transactionQuery(){
        // Create a reporting service instance
        $reportingService = $this->apiClient->reportingService();

        try {
            /**  @var TransactionList  $reportingResponse **/
            $reportingModel = new TransactionFilter();
            $reportingModel->setFromDate('2016-01-01T20:00:00.000Z');
            $reportingModel->setToDate('2017-01-01T20:00:00.000Z');
            $reportingModel->setPageSize('10');
            $reportingModel->setSortColumn('Email');
            $filter = [
                "action" => "include",
                "field" => "TrackID",
                "operator" => "equals",
                "value" => "someTrackId"
            ];
            $reportingModel->setFilters($filter);

            $reportingResponse = $reportingService->queryTransaction($reportingModel);
        } catch (baseCheckout\helpers\ApiHttpClientCustomException $e) {
            echo 'Caught exception: ',  $e->getErrorMessage(), "\n";
            echo 'Caught exception Error Code: ',  $e->getErrorCode(), "\n";
            echo 'Caught exception Event id: ',  $e->getEventId(), "\n";
        }
    }

    public function chargebackQuery(){

        // Create a reporting service instance
        $reportingService = $this->apiClient->reportingService();

        try {
            /**  @var ChargebackList  $reportingResponse **/
            $reportingModel = new TransactionFilter();
            $reportingModel->setFromDate('2016-01-01T20:00:00.000Z');
            $reportingModel->setToDate('2017-01-01T20:00:00.000Z');
            $reportingModel->setPageSize('10');
            $reportingModel->setSortColumn('Email');

            $reportingResponse = $reportingService->queryChargeback($reportingModel);
        } catch (baseCheckout\helpers\ApiHttpClientCustomException $e) {
            echo 'Caught exception: ',  $e->getErrorMessage(), "\n";
            echo 'Caught exception Error Code: ',  $e->getErrorCode(), "\n";
            echo 'Caught exception Event id: ',  $e->getEventId(), "\n";
        }
    }

}
