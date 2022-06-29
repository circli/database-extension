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

	protected ?int $totalCount = 0;

	/**
	 * @param RecordSet<Record>|null $recordSet
	 * @return static<T>
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

	/**
	 * @return class-string<T>
	 */
	public function getType(): string
	{
		return static::$collectionType;
	}

	/**
	 * @return static<T>
	 */
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

	public function offsetExists(mixed $offset): bool
	{
		return isset($this->data[$offset]);
	}

	/**
	 * @return T|null
	 */
	public function offsetGet(mixed $offset): ?Entity
	{
		return $this->data[$offset] ?? null;
	}

	/**
	 * @param T $value
	 */
	public function offsetSet(mixed $offset, $value): void
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

	public function offsetUnset(mixed $offset): void
	{
		unset($this->data[$offset]);
	}

	public function count(): int
	{
		return count($this->data);
	}

	public function getTotalCount(): ?int
	{
		return $this->totalCount;
	}

	public function setTotalCount(int $count): void
	{
		$this->totalCount = $count;
	}

	public function isEmpty(): bool
	{
		return count($this->data) === 0;
	}

	public function jsonSerialize()
	{
		return $this->data;
	}

	/**
	 * @return T|null
	 */
	public function first(): ?Entity
	{
		if ($this->data) {
			return $this->data[array_key_first($this->data)];
		}
		return null;
	}

	/**
	 * @return T|null
	 */
	public function last()
	{
		if ($this->data) {
			return $this->data[array_key_last($this->data)];
		}
		return null;
	}

	/**
	 * @param T $entity
	 */
	public function contains(Entity $entity): bool
	{
		foreach ($this as $localEntity) {
			if ($localEntity === $entity) {
				return true;
			}
		}
		return false;
	}

	/**
	 * @param callable(T): bool $filter
	 * @return static<T>
	 */
	public function filter(callable $filter): static
	{
		$collection = self::empty();
		foreach ($this as $entity) {
			if ($filter($entity)) {
				$collection[] = $entity;
			}
		}
		return $collection;
	}

	/**
	 * @param callable(T): bool $filter
	 * @return T|null
	 */
	public function findOne(callable $filter)
	{
		return $this->filter($filter)->first();
	}

	final public function __construct()
	{
	}
}
