<?php
declare(strict_types=1);

namespace rstmpw\sbrpgate;


class SBRClient
{
    protected $login = null;
    protected $password = null;
    protected $initOpts = [
        'endpoints' => [
            'newOrder' => 'https://3dsec.sberbank.ru/payment/rest/register.do',
            //'newOrder' => 'https://3dsec.sberbank.ru/payment/rest/registerPreAuth.do',
            'orderStatus' => 'https://3dsec.sberbank.ru/payment/rest/getOrderStatus.do',
            //'orderStatus' => 'https://3dsec.sberbank.ru/payment/rest/getOrderStatusExtended.do'
        ]
    ];

    public function __construct(string $login, string $password, array $initOpts=null)
    {
        $this->login = $login;
        $this->password = $password;
        if(!is_null($initOpts)) $this->initOpts = $initOpts;
    }

    public function newOrder(string $orderNum, int $amount, string $returnURL, string $description = null, string $failUrl = null): array
    {
        $return = [
            'orderId' => null,
            'redirectUrl' => null
        ];

        $requestData = [
            'userName',
            'password',
            'orderNumber',
            'amount',
            'description',
            'returnUrl',
            'failUrl'
        ];

        return $return;
    }

    public function orderStatus(string $orderId):bool {
        $requestData = [
            'userName',
            'password',
            'orderId'
        ];

        return true;
    }


}