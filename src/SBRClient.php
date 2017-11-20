<?php
declare(strict_types=1);

namespace rstmpw\sbrpgate;

use rstmpw\sbrpgate\Exception\TransportException;
use rstmpw\sbrpgate\Exception\ServiceException;

class SBRClient
{
    protected $login = null;
    protected $password = null;
    protected $initOpts = [
        'endpoints' => [
            'newOrder' => 'https://3dsec.sberbank.ru/payment/rest/register.do',
            'orderStatus' => 'https://3dsec.sberbank.ru/payment/rest/getOrderStatus.do'
        ],
        'curlopts' => [
            CURLOPT_FAILONERROR => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 3,
            CURLOPT_TIMEOUT => 30
        ]
    ];

    public function __construct(string $login, string $password, array $initOpts=null)
    {
        $this->login = $login;
        $this->password = $password;
        if(!is_null($initOpts)) $this->initOpts = array_replace_recursive($this->initOpts, $initOpts);
    }

    public function newOrder(string $orderNum, int $amount, string $returnURL, string $description = null, string $failUrl = null): array
    {
        $requestData = [
            'userName' => $this->login,
            'password' => $this->password,
            'orderNumber' => $orderNum,
            'amount' => $amount,
            'description' => $description,
            'returnUrl' => $returnURL,
            'failUrl' => $failUrl
        ];
        $queryUrl = $this->initOpts['endpoints']['newOrder'].'?'.http_build_query($requestData);

        //{"orderId":"6526b22f-7763-781b-6526-b22f000be0e8","formUrl":"https://3dsec.sberbank.ru/payment/merchants/test-iac-cdep-ru/payment_ru.html?mdOrder=6526b22f-7763-781b-6526-b22f000be0e8"}
        //{"errorCode":"1","errorMessage":"Заказ с таким номером уже обработан"}
        $orderData = $this->getData($queryUrl);

        $jsonResponse = json_decode($orderData, true);
        if(!is_array($jsonResponse)) throw new TransportException('Error parsing JSON response from service');
        if(isset($jsonResponse['errorCode']) && $jsonResponse['errorCode'] > 0)
            throw new ServiceException($jsonResponse['errorMessage'], intval($jsonResponse['errorCode']));

        return $jsonResponse;
    }

    public function orderStatus(string $orderId):array {
        $requestData = [
            'userName'=> $this->login,
            'password' => $this->password,
            'orderId' => $orderId
        ];
        $queryUrl = $this->initOpts['endpoints']['orderStatus'].'?'.http_build_query($requestData);

        $orderStatus = $this->getData($queryUrl);

        $jsonResponse = json_decode($orderStatus, true);
        if(!is_array($jsonResponse)) throw new TransportException('Error parsing JSON response from service');
        if(isset($jsonResponse['ErrorCode']) && $jsonResponse['ErrorCode'] > 0)
            throw new ServiceException($jsonResponse['ErrorMessage'], intval($jsonResponse['ErrorCode']));

        return $jsonResponse;
    }


    protected function getData($url) {
        $curl = curl_init($url);
        curl_setopt_array($curl, $this->initOpts['curlopts']);
        $result = curl_exec($curl);
        if($result === false) {
            throw new TransportException(curl_error($curl));
        }
        curl_close($curl);
        return $result;
    }
}