<?php

namespace App\Events;

use App\Libraries\Documents\EtgbDocument;
use App\Libraries\Documents\InvoiceDocument;
use App\Libraries\Documents\CustomDocument;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Services\OrderService;

class OrderLabelPrinted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var OrderService
     */
    protected $order;

    /**
     * Create a new event instance.
     *
     * @param OrderService $order
     * @return void
     */
    public function __construct($package, OrderService $order, $response)
    {
        if(!$order->hasItem()){
            // Bu gerçekleşmemeli
        }

        $this->order = $order;

        // 1- Save tracking number
        $packageItem = $package->get();
        $packageItem->tracking_number = $response->shipmentTrackingNumber;
        $packageItem->save();

        // 2- Save Barcode
        foreach ($response->documents as $key => $document) {
            $file = base64_decode($document['content']);
            $fileName = 'barcodes/' . $package->id . '_' . $key . '.pdf';
            \Storage::disk('public')->put($fileName, $file);
        }

        // 3- Change order status to Labelled
        $order->changeStatus(14);

        // 4- Create Documents (Etgb, Invoice)
        new EtgbDocument($order);
        new InvoiceDocument($order);
        new CustomDocument($order);

        // 5- Invoice
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
