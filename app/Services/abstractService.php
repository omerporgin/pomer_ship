<?php

namespace App\Services;

use App\Services\Traits\TableTrait;
use App\Traits\ErrorTrait;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use App\Models\Types;
use Illuminate\Support\Arr;
use \App\Models\Language;
use Schema;

/**
 * Events
 *
 * beforeDelete()
 * afterDelete()
 */
abstract class abstractService
{
    use TableTrait, ErrorTrait;

    /**
     * @var array
     */
    public array $err = [];

    abstract public function deletable(int $id = null);

    abstract public function updatable(int $id = null);

    /**
     * User permission for this model
     *
     * @var object
     */
    private $permission;

    /**
     * Stores why item is not deletable.
     *
     * @var string
     */
    public $deletableMsg;

    /**
     * Stores why item is not updatable.
     *
     * @var string
     */
    public $updatableMsg;

    /**
     * @var int
     */
    public $id = null;

    /**
     * @var \App\Models\Item
     */
    protected $item = null;

    /**
     * @var int
     */
    protected $lang;

    /**
     * Abstruct service constructor.
     */
    //public function __construct(serviceRepositoryFactory $serviceRepositoryFactory)
    public function __construct(int $id = null)
    {

        $this->lang = \Config::get('app.locale_id');

        // Get lang
        $currentLangCode = \App::getLocale();

        if (Schema::hasTable((new Language)->getTable())) {
            $langRow = Language::where('code', $currentLangCode)->first();
            if (Schema::hasTable((new Language)->getTable()) and !is_null($langRow)) {
                $this->lang = $langRow->id;
            }
        }

        if (!is_null($id)) {
            $this->id = $id;
            $this->getById($id, true);
        }

    }

    /**
     * Returns request filtered by table fields (using rules).
     *
     * @param $request
     * @return array
     */
    public function filteredRequest($request): array
    {
        $rules = (new $request)->rules();
        $request = $request->All();
        $fields = array_keys($rules);
        $data = [];
        foreach ($fields as $field) {
            if (array_key_exists($field, $request)) {
                $data[$field] = $request[$field];
            }
        }

        return $data;
    }

    /**
     * @return void
     */
    public function setLang(): void
    {
        $this->lang = langId();
    }

    /**
     *
     */
    public function getType(): int
    {
        return static::$type;
    }

    /**
     * Type of item
     *
     * @return ?object
     */
    public function type(): ?object
    {
        if (!is_null($type = $this->getType())) {
            $typeEntity = service('type', $type);
            return $typeEntity->item;
        }
        return null;
    }

    /**
     *
     */
    protected function setType(int $newType): void
    {
        self::$type = $newType;
    }

    /**
     * @return null|int
     */
    public function getID(): ?int
    {
        return $this->id;
    }

    /**
     * @param $id null|int
     */
    public function setID(?int $id)
    {
        $this->id = $id;
    }

    /**
     * Set item
     */
    public function setItem(object $item): void
    {
        $this->item = $item;
        if (isset($item->id)) {
            $this->setID($item->id);
        }
    }

    /**
     *
     */
    public function getItem()
    {
        $this->get();
    }

    /**
     * Blank item
     */
    public function get()
    {
        return $this->item;
    }

    /**
     * Unset item by running childs constructor.
     *
     * @param int|null $id
     * @return void
     */
    public function reset(int $id = null): void
    {
        static::__construct($id);
        $this->id = null;
    }

    /**
     * Safe way to check if item is set or not.
     *
     * @return bool
     */
    public function hasItem(): bool
    {
        return !is_null($this->item->id);
    }

    /**
     *
     */
    public function serviceName()
    {
        return (new \ReflectionClass($this))->getShortName();
    }

    /**
     * Get item by id.
     *
     * @param int $id
     */
    public function getById(int $id, ?bool $set = true): ?object
    {

        $this->lang = langId();

        if (!is_null($item = $this->item
            ->where('id', $id)
            ->first())) {
            $this->setID($id);
        }

        if ($set and !is_null($item)) {
            $this->setItem($item);
        }
        return $item;
    }

    /**
     * Get items by ids array.
     *
     * @param array $ids
     */
    public function getByIds(array $ids): array
    {
        $items = $this->item
            ->whereIn('id', $ids)
            ->get()->toArray();
        return $items;
    }

    /**
     * Get all items.
     *
     * @param Array $filters
     * @param Bool $keyAsID
     */
    public function getAll($filters = [], bool $keyAsID = false): array
    {
        $filters = (object)$filters;

        if (!empty($filters)) {
            if (!isset($filters->search['value'])) {
                $filters->search['value'] = '';
            }
            if (!isset($filters->order)) {
                $filters->order = null;
            }
            if (!isset($filters->start)) {
                $filters->start = 0;
            }
            if (!isset($filters->length)) {
                $filters->length = 999;
            }
            if (!isset($filters->withService)) {
                $filters->withService = false;
            }

            list($list, $columns) = $this->getAllFiltered($filters);

            // Count
            $total = 0;
            if (!is_null($list)) {
                $total = $list->count();
            }

            if (!empty($columns)) {
                // Order
                if (!is_null($filters->order)) {

                    $orderBy = $columns[$filters->order[0]['column']];
                    $dir = $filters->order[0]['dir'];

                    $list = $list->orderBy($orderBy, $dir);
                }

                // Pagination
                if ($filters->start > 0) {
                    $list = $list->skip($filters->start);
                }
                if ($filters->length > 0) {
                    $list = $list->take($filters->length);
                }
            } else {
                // This is a sortable table
            }

            $list = $list->get();
        } else {
            $list = $this->item->get();
            $total = $this->item->count();
        }

        if ($keyAsID) {
            // Use key as Item Id
            $newList = [];
            foreach ($list as $item) {
                $newList[$item->id] = $item;
            }
            $list = $newList;
        }

        $return = [
            'list' => $list,
            'total' => $total,
        ];

        // Add services
        if ($filters->withService) {
            $serviceList = [];
            foreach ($list as $item) {
                $serviceList[] = service($this->serviceName(), $item->id);
            }
            $return['serviceList'] = $serviceList;
        }
        return $return;
    }

    /**
     * Returns list of new items
     *
     * @return object
     */
    public static function all(): array
    {
        $return = [];
        foreach ((new static)->get()->All() as $item) {

            $self = new static;
            $self->setItem($item);
            $return[] = $self;
        }
        return $return;
    }

    /**
     * Get active items.
     *
     * @param null|Object $filters
     */
    public function getActiveItems(): ?object
    {
        return $this->item
            ->where('active', 1)
            ->get();
    }

    /**
     * Delete item by id
     *
     * @param int $id
     * @return bool
     */
    public function deleteById(int $id): bool
    {
        $this->getById($id);

        if ($this->deletable()) {

            try {

                if (method_exists($this, 'beforeDelete')) {
                    $this->beforeDelete($id);
                }

                $result = $this->item->where('id', $id)->delete();

                $this->deleteImages($id);

                $this->deleteLangRows($id);

                if (method_exists($this, 'afterDelete')) {
                    $this->afterDelete($id);
                }

                if (method_exists($this, 'onChange')) {
                    $this->onChange($id);
                }
            } catch (\Exception $e) {

                reportException($e);

                throw new InvalidArgumentException($e->getMessage());
            }
            return $result;
        } else {
            throw new \InvalidArgumentException('Item is not deletable : ' . $this->deletableMsg);
        }
    }

    /**
     * @param int $id
     */
    public function deleteImages(int $id)
    {
        if (isset(static::$type)) { // If has self::$type than its possible to have an image.
            $imageService = app()->make(ImageService::class);
            $imageList = $imageService->getAllByType(static::$type, $id);

            foreach ($imageList as $image) {
                $imageService->deleteById($image->id);
            }
        }
    }

    /**
     * @param int $id
     */
    public function deleteLangRows(int $id)
    {
        try {
            \DB::table($this->langTableName())->where('type_id', $id)->delete();
        } catch (\Exception $e) {
            // reportException($e, true);
        }
    }

    /**
     * Validate post data.
     * Store to DB if there is no error.
     *
     * @param array|object $data
     * @return array
     */
    public function save(object|array $data): array
    {

        if (is_object($data)) {
            $data = (array)$data;
        }

        $data = Arr::only($data, $this->tableFieldNames());

        if (isset($data['id']) and !is_null($data['id'])) {
            $newItem = $this->item::find($data['id']);
            if (!is_null($newItem)) {
                $this->item = $newItem;
            } else {
                // Than code will create new row
            }
        }

        $item = $this->item;
        foreach ($data as $key => $value) {
            $item->{$key} = $value;
        }

        $result = false;
        if ($item->save()) {
            $result = true;
        }

        $return = [
            'result' => $result,
            'item' => $item->fresh(),
            'id' => $item->id,
        ];

        if (method_exists($this, 'onChange')) {
            $this->onChange($item->id);
        }

        return $return;
    }

    /**
     * Set permission data for meodel.
     */
    protected function getPermissions(): ?object
    {
        if (is_null($this->permission)) {
            $this->permission = (new \App\Permissions);
        }
        return $this->permission;
    }

    /**
     * Compare 2 filters
     *
     * @param object $filters
     * @return object
     */
    public function validateFilters(object $requestFilters): object
    {

        $defaultFilters = (object)[
            "start" => 0,
            "length" => 10,
            "search" => [
                "value" => "",
                "regex" => ""
            ],
            "order" => [
                [
                    "column" => "0",
                    "dir" => "desc"
                ]
            ]
        ];
        foreach ($defaultFilters as $key => $filter) {
            if (isset($requestFilters->{$key})) {
                $defaultFilters->{$key} = $requestFilters->{$key};
            }
        }

        return $defaultFilters;
    }

    /**
     * Returns current entities properties.
     * (Dynamic properties will be deprecated in php 9.0 )
     *
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        if (isset($this->item->{$name})) {
            return $this->item->{$name};
        } else {
            //throw new Exception("$name don't exists");
        }
        return null;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function has(string $name): bool
    {
        if (isset($this->item->{$name})) {
            return true;
        }
        return false;
    }

     /**
     * @param $withId
     * @return array
     */
    public function asArray($withId = true): array
    {
        if (!$this->hasItem()) {
            return [];
        }
        if ($withId) {
            return [$this->item->id => $this->item->toArray()];
        } else {
            return $this->item->toArray();
        }
    }

    /**
     * @param int|null $id
     * @return int|null
     */
    protected function currentIfNull(?int $id = null): ?int
    {
        if (is_null($id)) {
            return $this->getID();
        }
        return $id;
    }

    /**
     * Mutators original value
     * For Laravel 7.x & 8.x,
     *
     * @param $fieled
     * @return mixed
     */
    public function getOriginal($fieled){
        return $this->item?->getRawOriginal($fieled);
    }
}
