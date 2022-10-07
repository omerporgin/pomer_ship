# Order Servisleri

Siparişleri apiler aracılığı ile siteye indirir.

## İsimlendirme
DB::entegration tablosundaki id fieldından alır.

```
Items/Entegration1.php -> OpenCart
Items/Entegration2.php -> CsCart
```
### CS CART SERVİSİ
Standart servisin çalışmadığı durumda aşağıdaki kod ile servis oluşturulabilir.

```php

//https://test.exporgin.com/index.php?dispatch=eee&date_start=14.04.2022&date_end=13.04.2022

if(!isset($_GET['date_start']) or !isset($_GET['date_end'])){
    //$yesterday = date("Y-m-d",strtotime("-3 days") );
    exit;
}
$orderList = [];
foreach(db_get_array("SELECT * FROM ?:orders WHERE timestamp < ?s AND timestamp > ?s", strtotime($_GET['date_start']), strtotime($_GET['date_end'])) as $order) {
    $orderList[] = fn_get_order_info($order['order_id']);
}
fn_exporgin_payments_json(  $orderList);
```
