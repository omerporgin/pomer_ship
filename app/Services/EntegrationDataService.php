<?php

namespace App\Services;

use App\Models\EntegrationData as Item;

class EntegrationDataService extends abstractService
{
    /**
     * Repository constructor.
     */
    public function __construct(int $id = null)
    {
        $this->setItem(new Item);
        parent::__construct($id);
    }

    /**
     * @return bool
     */
    public function deletable(int $id = null): bool
    {
        $this->deletableMsg = 'Item not deletable';
        return false;
    }

    /**
     * @return bool
     */
    public function updatable(int $id = null): bool
    {
        $this->updatableMsg = 'Item not updatable';
        return true;
    }

    /**
     * @param $userID
     * @return array
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public static function getUserData($userID): array
    {
        $entegrationService = app()->make(EntegrationService::class);

        $service = new Static;
        $item = $service->get();
        $list = $item->where('user_id', $userID)->get();

        $return = [];
        foreach ($list as $entegration) {

            $entegrationObj = $entegrationService->getById($entegration->entegration_id);
            $return[$entegration->id] = [
                'name' => $entegrationObj->name,
                'entegration_id' => $entegrationObj->id,
                'entegration_data' => $entegrationObj,
                'entegration' => $entegration,
            ];
        }

        return $return;
    }

}
