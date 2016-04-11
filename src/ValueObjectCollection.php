<?php

namespace Antennaio\VO;

use ArrayIterator;
use Countable;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use IteratorAggregate;

class ValueObjectCollection implements Arrayable, Countable, IteratorAggregate
{
    /**
     * @var Collection
     */
    protected $collection;

    /**
     * @var string
     */
    protected $valueObject;

    /**
     * @var string
     */
    protected $delimiter = ',';

    /**
     * @var bool
     */
    protected $unique = true;

    /**
     * Create a new collection.
     *
     * @param string|array|Collection|Arrayable $items
     */
    public function __construct($items)
    {
        $items = $this->parseItems($items);

        $this->collection = $this->makeCollection($items);
    }

    /**
     * Make collection of value objects.
     *
     * @param array $items
     *
     * @return Collection
     */
    protected function makeCollection(array $items)
    {
        $objects = new Collection();

        foreach ($items as $item) {
            $objects->push(new $this->valueObject($item));
        }

        return ($this->unique) ?
            $objects->unique() :
            $objects;
    }

    /**
     * Parse items.
     *
     * @param mixed $items
     *
     * @return array
     */
    protected function parseItems($items)
    {
        if (is_array($items)) {
            return $items;
        } elseif (is_string($items)) {
            return array_map('trim', explode($this->delimiter, $items));
        } elseif ($items instanceof Collection) {
            return $items->all();
        } elseif ($items instanceof Arrayable) {
            return $items->toArray();
        }

        return [];
    }

    /**
     * Get collection.
     *
     * @return Collection
     */
    public function getCollection()
    {
        return $this->collection;
    }

    /**
     * Returns the number of items.
     *
     * @return int
     */
    public function count()
    {
        return $this->collection->count();
    }

    /**
     * Get an iterator for the items.
     *
     * @return ArrayIterator
     */
    public function getIterator()
    {
        return $this->collection->getIterator();
    }

    /**
     * Returns the array representation of value.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->collection->map(function ($item) {
            return $item->value();
        })->toArray();
    }

    /**
     * Returns the string representation of value.
     *
     * @return string
     */
    public function __toString()
    {
        return implode($this->delimiter.' ', $this->toArray());
    }
}
