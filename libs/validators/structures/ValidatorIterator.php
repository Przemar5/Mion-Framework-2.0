<?php

namespace Libs\Validators\Structures;

use Libs\Utils\Collection;

class ValidatorIterator implements \Iterator
{
	private ArrayCollection $collection;
	private int $position = 0;
	private $value;
	private ?string $error = null;


	public function __construct(Collection $collection, $value)
	{
		$this->collection = $collection;
	}

    public function rewind(): void
    {
        $this->position = 0;
    }

    public function current()
    {
        return $this->collection[$this->position];
    }

    public function key(): int
    {
        return $this->position;
    }

    public function next(): void
    {
        $this->position = $this->position + 1;
    }

    public function hasNext(): bool
    {
    	return $this->position < $this->collection->count();
    }

    public function valid(): bool
    {
        return isset($this->collection[$this->position]);
    }

    public function validateCurrent()
    {
    	$validator = $this->collection[$this->position];
    	
    	return $validator->validate($this->value);
    }

    public function runValidators()
    {
    	$this->error = null;

    	try {
    		$this->error = $this->getMessageOfFirstInvalid();
    	}
    	catch (\Exception $e) {
    		die($e->getMessage());
    	}
    }

    private function getMessageOfFirstInvalid(): ?string
    {
    	foreach ($this->collection as $validator) {
			if (!$validator->validate($this->value)) {
				return $validator->message;
			}
		}
    }
}