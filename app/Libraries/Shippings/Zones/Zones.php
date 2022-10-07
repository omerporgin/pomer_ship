<?php

namespace App\Libraries\Shippings\Zones;

/**
 * Item/sone
 */
class Zones
{

    /**
     * Updates county lists cargo zones
     *
     * @param $zoneName
     * @return object
     */
    public static function updateCargoZones($zoneName = null): object
    {
        $return = [];
        $dir = '../app/Libraries/Shippings/Zones/Items/*';
        foreach (glob($dir) as $shipping) {
            $fileName = str_replace("Zones.php", "", \basename($shipping));
            $return[] = self::updateZone($fileName);
        }

        return (object)$return;
    }

    /**
     * Updates one processor
     *
     * @param string $processorName
     * @return array
     */
    public static function updateZone(string $processorName): array
    {
        $className = "\App\Libraries\Shippings\Zones\Items\\" . $processorName."Zones";
        $zoneItem = new $className;
        $notfoundItems = $zoneItem->updateZones();
        return [
            'zoneName' => $zoneItem->name,
            'notfoundItems' => $notfoundItems,
        ];
    }
}
