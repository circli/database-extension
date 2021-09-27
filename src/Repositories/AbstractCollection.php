<?php declare(strict_types=1);

namespace Circli\Database\Repositories;

use Atlas\Mapper\Record;
use Atlas\Mapper\RecordSet;

/**
 * @template T of Entity
 * @implements \IteratorAggregate<array-key, T>
 * @implements \ArrayAccess<array-key, T>
 */
abstract class AbstractCollection implements \IteratorAggregate, \ArrayAccess, \Countable, \JsonSerializable
{
	/** @var T[] */
	protected array $data = [];
	/** @var class-string<T> */
	protected static string $collectionType = GenericEntity::class;

	/**
	 * @param RecordSet<Record>|null $recordSet
	 */
	public static function fromRecordSet(?RecordSet $recordSet): static
	{
		$collection = new static();
		if (!$recordSet) {
			return $collection;
		}
		$type = static::$collectionType;
		if (!method_exists($type, 'fromRecord')) {
			throw new \BadMethodCallException($type . ' missing method "fromRecord"');
		}
		foreach ($recordSet as $record) {
			$collection[] = $type::fromRecord($record);
		}
		return $collection;
	}

	public static function empty(): static
	{
		return new static();
	}

	/**
	 * @return \ArrayIterator<array-key, T>
	 */
	public function getIterator(): \ArrayIterator
	{
		return new \ArrayIterator($this->data);
	}

	public function offsetExists($offset): bool
	{
		return isset($this->data[$offset]);
	}

	/**
	 * @return T|null
	 */
	public function offsetGet($offset)
	{
		return $this->data[$offset] ?? null;
	}

	/**
	 * @param T $value
	 */
	public function offsetSet($offset, $value)
	{
		if (!$value instanceof static::$collectionType) {
			throw new \TypeError('Invalid type for collection. Expected: ' . static::$collectionType);
		}

		if (is_null($offset)) {
			$this->data[] = $value;
		}
		else {
			$this->data[$offset] = $value;
		}
	}

	public function offsetUnset($offset)
	{
		unset($this->data[$offset]);
	}

	public function count(): int
	{
		return count($this->data);
	}

	public function isEmpty(): bool
	{
		return count($this->data) === 0;
	}

	public function jsonSerialize()
	{
		return $this->data;
	}

	final public function __construct()
	{
	}
}
