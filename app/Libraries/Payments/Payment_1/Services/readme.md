#CancelTransfer

Sipariş kontrol eder.

#CheckBinNumber

Kart no kontrol eder.

#GetInstallments

Taksit oranlarını döndürür.

```php
$this->ifTotalPriceGreaterThan(150)->changeThisInstallements([3])->toCartTotal()->addDefinition()
```

```php
$getInstallments = new GetInstallments;
$getInstallments->execute(); // Taksit oranlarını döndürür. (Tefeci faizine uygun)

$getInstallments->withNewComission(false)->execute(); // Taksit oranları döndürür. (Paytrden gelen değerler)
```

Taksit oranları sipariş toplamına göre değişebilir. Aşağıdaki şekilde çağırıldığında toplamdanbağımsız oranlar gelecektir.
```php
    $getInstallments = new GetInstallments;
    $getInstallments->setCartTotal(0); // Belirtilmezse sessiondaki sepet toplamını alır
    $list = (array)$getInstallments->execute();
```
Oran deişimi.

```php

 execute() metodunda değişiklik yapılmalı.

 $this->ifTotalPriceGreaterThan(150)->changeThisInstallements([3])->toCartTotal()->addDefinition();
 ```
#GetTransaction

Paytr servisi değil. ProcessList servisini çalıştırıp filtreliyor.

#PlatformTransfer

Vendora ödeme gönderir.

#ProcessList

Belli iki tarih aralığındaki siaprişleri raporlar.

#ReturnedTransfers

Geri dönen transfer listesi.

#Transaction

Bir sipariş ile ilgili bilgleir döndürür.