<?php

namespace App\Libraries\OrderEntegrationServices;

use App\Services\EntegrationDataService;
use App\Traits\ErrorTrait;
use Illuminate\Support\Facades\Auth;

class   EntegrationService
{
    use ErrorTrait;

    public array $err = [];

    /**
     *
     */
    public function __construct()
    {
    }

    /**
     * @return array
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function getAllOrders()
    {
        $count = 0;
        $savedItems = 0;

        // Get users all entegrations
        $entegrationData = EntegrationDataService::getUserData(Auth::id());
        $result = true;

        // Get order list one by one
        foreach ($entegrationData as $data) {

            try {
                $entegrationService = self::factory($data);
                $savedItems += $entegrationService->getOrders();
                $this->setErrorList($entegrationService->getErrorList());
                $count += $entegrationService->sumItems();
            } catch (\Exception $e) {
                $result = false;
                $this->setError($e->getMessage());
                //reportException($e, true);
            }

        }

        $errList = $this->getErrorList();

        if(!empty($errList)){
            $result = false;
        }
        return [
            'result' => $result,
            'err' => $errList,
            'count' => $count,
            'saved_items' => $savedItems,
        ];
    }

    /**
     * Initializes entegration
     *
     * @param array $data
     * @return mixed
     */
    public static function factory(array $data)
    {
        $className = '\\App\\Libraries\\OrderEntegrationServices\\Items\\Entegration' . $data['entegration_id'];
        $orderEntegration = new ($className);
        $orderEntegration->setEntegration($data['entegration']);
        return $orderEntegration;
    }
}
