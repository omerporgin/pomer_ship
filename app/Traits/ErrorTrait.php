<?php

namespace App\Traits;

/**
 * This tread required parameter
 *
 * required parameter :
 * public array $err = [];
 */
trait ErrorTrait
{
    /**
     * @param string $errMsg
     * @return void
     */
    protected function setError(string $errMsg): void
    {
        $this->err[] = $errMsg;
    }

    /**
     * Insert error list
     *
     * @param array $errList
     * @return void
     */
    protected function setErrorList(array $errList): void
    {

        $this->err = array_merge($this->err, $errList);
    }

    /**
     * @return bool
     */
    public function hasError(): bool
    {
        if (!empty($this->err)) {
            return true;
        }
        return false;
    }

    /**
     * @return string
     */
    public function getErrorList(): array
    {
        return $this->err;
    }

    /**
     * @return string
     */
    protected function mergeErrors(array $errors): array
    {
        return $this->err = array_merge($errors, $this->err);
    }
}
