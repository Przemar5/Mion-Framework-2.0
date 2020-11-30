<?php

namespace Libs\Utils;

use Libs\Utils\Collection;

class ArrayCollection extends Collection implements \ArrayAccess
{
	public array $values;


	public function __construct(array $values = [])
	{
		parent::__construct($values);
	}

	public function __get($index)
	{
		return $this->values[$index];
	}

	public function empty(): bool
	{
		return empty($this->values);
	}

	public function count(): int
	{
		return count($this->values);
	}

	public function push($element): void
	{
		array_push($this->values, $element);
	}

	public function pop()
	{
		return array_pop($this->values);
	}

	public function shift()
	{
		return array_shift($this->values);
	}

	public function unshift($element): void
	{
		array_unshift($this->values, $element);
	}

	public function merge(array $values): void
	{
		$this->values = array_merge($this->values, $values);
	}

	public function map(callable $func): array
	{
		return array_map($func, $this->values);
	}

	public function forEach(callable $func)
	{
		$result = [];
		foreach ($this->values as $value) {
			$result[] = $func($value);
		}

		return $result;
	}

	public function offsetSet($offset, $value)
	{
        if (is_null($offset)) {
            $this->values[] = $value;
        } else {
            $this->values[$offset] = $value;
        }
    }

    public function offsetExists($offset)
    {
        return isset($this->values[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->values[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->values[$offset]) ? $this->values[$offset] : null;
    }
}