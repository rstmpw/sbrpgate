# SBRF Payment Gate

## New order

```php
$sbergate = new \rstmpw\sbrpgate\SBRClient($login, $passwd);
$orderData = $sbergate->newOrder($_POST['num'], $_POST['amount'], 'http://192.168.222.201/?op=status', $_POST['description'], 'http://192.168.222.201/?op=payfail');
```

Return exception or array:

```php
array (size=2)
  'orderId' => string '8f366bcb-5bd3-7a39-8f36-6bcb000be0e8' (length=36)
  'formUrl' => string 'https://3dsec.sberbank.ru/payment/merchants/test-iac-cdep-ru/payment_ru.html?mdOrder=8f366bcb-5bd3-7a39-8f36-6bcb000be0e8' (length=121)
```

## Order status
```php
$sbergate = new \rstmpw\sbrpgate\SBRClient($login, $passwd);
$orderStatus = $sbergate->orderStatus($orderId);
```
Return exception or array:
```php
array (size=13)
  'expiration' => string '201912' (length=6)
  'cardholderName' => string 'TEST TEST' (length=9)
  'depositAmount' => int 10000
  'currency' => string '643' (length=3)
  'approvalCode' => string '123456' (length=6)
  'authCode' => int 2
  'ErrorCode' => string '0' (length=1)
  'ErrorMessage' => string 'Успешно' (length=14)
  'OrderStatus' => int 2
  'OrderNumber' => string '108' (length=3)
  'Pan' => string '639002**0003' (length=12)
  'Amount' => int 10000
  'Ip' => string '195.208.50.246' (length=14)
```  