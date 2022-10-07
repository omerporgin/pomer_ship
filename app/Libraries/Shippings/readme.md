# Shippping Services
 - DHL ve UPS ülke-ülke arasına göre ücretlendiriyor. (Şehir, kasaba vs önemsiz.)
 - DHL 2 paket olabilir. UPS sadece 1 paket olabilir.
Request nesnesi ve Api servis nesnesi ayrıdır.

### DHL

1- Request nesnesi ayrı oluşturulabilir.

```php 
if( !is_null($item = shipping(1))){
 
    $request = App\Libraries\Shippings\RequestFactory::create('OnePieceShipment', $item); 
    $data = $request->build([
        'destinationCountryCode' => 'FR',
        'destinationCityName' => 'Paris',
        ...
    ]); 
    if ($item->withData($data)->onePieceShipment()) {
        dd($item->response());
    } else {
        dd($item->getErrorList());
    }   
```

2 - Request dahili olarak class içinde oluşturur.

```php 
if( !is_null($item = shipping(1))){
    if ($item->withRequest([
        'destinationCountryCode' => 'FR',
        'destinationCityName' => 'Paris',
        ...
    ])->onePieceShipment()) {
        dd($item->response());
    } else {
        dd($item->getErrorList());
    }
}   
```

## onePieceShipment
```php
    
if( !is_null($item = shipping(1))){
 
    $request = App\Libraries\Shippings\RequestFactory::create('OnePieceShipment', $item); 
    $data = $request->build([
       'destinationCountryCode' => 'FR',
       'destinationCityName' => 'Paris',
       'plannedShippingDate' => date("Y-m-d"),
       'isCustomsDeclarable' => 'true',
       'length' => 30,
       'weight' => 5,
       'width' => 20,
       'height' => 20,
    ]); 
    if ($item->withOrder($order)->withData($data)->onePieceShipment()) {
        dd($item->response());
    } else {
        dd($item->getErrorList());
    }   
```

## multiPieceShipments
Request nesnesi ayrı oluşturulmalı.

```php
if (!is_null($item = shipping(1))) {
    $order = service('order', 22);
    $r = $item->requestFactory('MultiPieceShipments',$order);
    $data = $r->build([
        "productCode" => "P", // DHL Express Global Product code
        "localProductCode" => "P",
        "plannedShippingDateAndTime" => $r->dateToGmt($r->daysAfter(3)),
        "unitOfMeasurement" => "metric",
        "isCustomsDeclarable" => false,
        "monetaryAmount" => [[
            "typeCode" => "declaredValue",
            "value" => 100,
            "currency" => "TRY"
        ]],
        "productTypeCode" => "all",
    ]);
    if ($item->withOrder($order)->withData($data)->multiPieceShipments()) {
        dd($item->response());
    } else {
        dd($item->getErrorList());
    }
}
```

## landedCost
```php
if (!is_null($item = shipping(1))) {
    $order = service('order', 22);
    if ($item->withRequest([], $order)->landedCost()) {
        dd($item->response());
    } else {
        dd($item->getErrorList());
    }
}
```

## products
```php
if (!is_null($item = shipping(1))) {
    if ($item->withRequest([
        'destinationCountryCode' => 'FR',
        'destinationCityName' => 'Paris',
        'plannedShippingDate' => date("Y-m-d"),
        'isCustomsDeclarable' => 'true',
        'length' => 30,
        'weight' => 5,
        'width' => 20,
        'height' => 20
    ])->products()) {
        dd($item->response());
    } else {
        dd($item->getErrorList());
    }
}
```

## proofOfDelivery
```php
if (!is_null($item = shipping(1))) {
    if ($item->proofOfDelivery('3480222384')) {
        dd($item->response());
    } else {
        dd($item->getErrorList());
    }
}
```

## createShipment
```php
if (!is_null($item = shipping(1))) {
    $order = service('order', 22);
    if ($item->withRequest([], $order)->createShipment()) {
        dd($item->response());
    } else {
        dd($item->getErrorList());
    }
}
```

## tracking
```php
    if (!is_null($item = shipping(1))) { 
        if ($item->withRequest([
            'shipmentTrackingNumber '=>'6776389605',
        ])->tracking(6776389605)) {
            dd($item->response());
        } else {
            dd($item->getErrorList());
        }
    }
```

## trackingList
```php
    if (!is_null($item = shipping(1))) {
        if ($item->withRequest([])->trackingList(['3480222384','3480222384'])) {
            dd($item->response());
        } else {
            dd($item->getErrorList());
        }
    }
```
 
## validateAddress
```php
    if (!is_null($item = shipping(1))) {
        if ($item->withRequest([
            'countryCode' => 'ET',
            'type' => 'delivery',
            'cityName' => 'DE'
        ])->validateAddress()) {
            dd($item->response());
        } else {
            dd($item->getErrorList());
        }
    }
```

### UPS
## onePieceShipment
```php
    if (!is_null($item = shipping(2))) {
        if ($item->withRequest([])->onePieceShipment()) {
            dd($item->response());
        } else {
            dd($item->getErrorList());
        }
    }
```

## ratesOfOrder
```php
    if (!is_null($item = shipping(2))) {
        $order = service('order', 22);

        $r = $item->requestFactory('RatesOfOrder',$order);
        $data = $r->build([], $order);
        if ($item->withData($data)->ratesOfOrder()) {
            dd($item->response());
        } else {
            dd($item->getErrorList());
        }
    }
```
## rates
```php
    if (!is_null($item = shipping(2))) { 

        $r = $item->requestFactory('Rates');
        if ($item->withRequest($data)->rates()) {
            dd($item->response());
        } else {
            dd($item->getErrorList());
        }
    }
```
