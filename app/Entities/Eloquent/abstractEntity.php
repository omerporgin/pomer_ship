<?php

namespace App\Entities\Eloquent;

abstract class abstractEntity
{

    /**
     * @var int
     */
    public $id;

    /**
     * Child class service object
     * 
     * @var object
     */
    protected $service;

    /**
     * Child class object
     * 
     * @var object
     */
    protected $item;

    /**
     * @var int
     */
    public $lang;

    /**
     * 
     */
    public function __construct()
    {
        $this->lang = \Config::get('app.locale_id');
    }

    /**
     * Sets id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * Returns id. Also (new self)->id is possible.
     * 
     * @return ?int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Sets item by id using current service.
     * 
     * @param int $id
     * @return bool
     */
    public function setItemById(int $id): bool
    {
        $item = $this->service->getById($id);
        if (!is_null($item)) {
            $this->saparateItem($item);
            return true;
        } else {
            return false;
        }
    }

    /**
     * 
     */
    public function setItem(object $item): bool
    {
        $this->saparateItem($item);
        return true;
    }

    /**
     * Returns item
     * 
     * @return ?object
     */
    public function getItem(): ?object
    {
        return $this->item;
    }

    /**
     * Arrange $items key value pair to self object
     * 
     * @param object $item
     */
    private function saparateItem(object $item)
    {
        $this->id = $item->id;
        $this->item = $item;
        foreach ($this->item->toArray() as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * @param object $service
     */
    public function setService(object $service): void
    {
        $this->service = $service;
    }

    /**
     * @return ?object
     */
    public function getService(): ?object
    {
        return $this->service;
    }

    /**
     * Some items dont have type
     * 
     * @return int
     */
    public function getType(): ?int
    {
        if (!is_null($this->service) && isset($this->service::$type)) {
            return $this->service::$type;
        }
        return null;
    }

    /**
     * Type of item
     * 
     * @return ?object
     */
    public function type(): ?object
    {
        if (!is_null($type = $this->getType())) {
            $typeEntity = new Type($type);
            return $typeEntity->item;
        }
        return null;
    }

    /**
     * Returns list of active item as object
     * 
     * @return array
     */
    public static function getActiveItems(): array
    {
        $returnList = [];

        $service = (new static)->service;
        $item = $service->get();
      
        $items = $item->where('active', 1);
        if ($service->tableName() != 'categories') {
            $items = $items->orderBy('sort');
        }

        foreach ($items->get() as $item) {
            $entityName = str_replace(['App\\Services\\', 'Service'], '', get_class($service));

            $returnEntity = entity($entityName);
            $returnEntity->setItem($item);
            $returnList[] = $returnEntity;
        }
        return $returnList;
    }

    /**
     * Returns list of new items
     * 
     * @return object
     */
    public static function newItems(): object
    {
        $service = (new static)->service;
        $item = $service->get();
        return $item->orderBy('created_at');
    }

    /**
     * Returns list of new items
     * 
     * @return object
     */
    public static function all(): array
    {
        $service = (new static)->service;
        $return = [];
        foreach ($service->get()->All() as $item) {

            $self = new static;
            $self->setItem($item);
            $return[] = $self;
        }
        return $return;
    }

    public function toArray(){
        $return  =[];
        foreach(get_class_vars(get_class($this)) as $key=>$var){
             
            $return[$key] = $this->{$key};
        }
        return $return;
    }
}
