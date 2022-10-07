<?php

namespace App\Libraries\Shippings;

interface RequestInterface
{

    /**
     * Sets request data
     *
     * @return mixed
     */
    public function set();

    /**
     * Builds request data
     *
     * @param array|null $newData
     * @return array
     */
    public function build(?array $newData = null): array;

}
