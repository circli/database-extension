<?php declare(strict_types=1);

namespace Circli\Database\Repositories;

use Atlas\Mapper\MapperSelect;
use Circli\Database\Values\DefaultId;
use Circli\Database\Values\Page;
use Ramsey\Uuid\UuidInterface;


/**
 * @template T
 * @template M of MapperSelect
 */
abstract class AbstractSearchCollection implements QueryCollectionInterface, WithAware
{
	public const FILTER_COLLECTION = 'collection';
	public const FILTER_INCLUDE = 'include';

	/** @var array<string, null|class-string|array{string}> */
	protected static array $filterBy = [];
	/** @var array<string, mixed[]> */
	protected static array $includeMap = [];
	/** @var string[] */
	protected static array $defaultInclude = [];

	/** @var array<string, mixed> */
	protected array $where = [];
	/** @var string[] */
	protected array $include = [];
	protected ?Page $page = null;

	/** @var array<class-string<T>, T> */
	protected array $filterCollections = [];

	protected static string $collectionNamespace = '';

	/**
	 * @param M $select
	 * @return M
	 */
	protected function buildSelect(MapperSelect $select): MapperSelect
	{
		foreach ($this->filterCollections as $collection) {
			$select = $collection->build($select);
		}

		foreach ($this->where as $where) {
			$select->where(...$where);
		}

		if ($this->page) {
			$select->offset($this->page->getOffset());
			$select->limit($this->page->getLimit());
		}

		return $select;
	}

	/**
	 * @return T|null
	 */
	protected function createFilterCollection(string $filter)
	{
		if (!static::$collectionNamespace) {
			$calledClass = static::class;
			$calledClassParts = explode('\\', $calledClass);
			$calledClassName = array_pop($calledClassParts);
			$calledClassParts[] = str_replace('Search', '', $calledClassName);
			static::$collectionNamespace = implode('\\', $calledClassParts) . '\\';
		}
		$className = static::$collectionNamespace . ucfirst($filter) . 'Collection';
		if (class_exists($className)) {
			return new $className();
		}
		return null;
	}

	public function filterBy(string $key, mixed $value): static
	{
		if ($this->handledFilterBy($key, $value)) {
			return $this;
		}
		if ($key === static::FILTER_COLLECTION) {
			$collections = array_map('trim', explode(',', $value));
			foreach ($collections as $collection) {
				$collectionClass = $this->createFilterCollection($collection);
				if ($collectionClass) {
					$this->filterCollections[$collectionClass::class] = $collectionClass;
				}
			}
		}
		elseif ($key === self::FILTER_INCLUDE) {
			$includes = array_map('trim', explode(',', $value));
			foreach ($includes as $include) {
				if (!isset(static::$includeMap[$include])) {
					continue;
				}
				$this->include[] = $include;
			}
		}
		elseif (isset(static::$filterBy[$key])) {
			if (is_string(static::$filterBy[$key])) {
				if (!class_exists(static::$filterBy[$key])) {
					throw new \InvalidArgumentException('Invalid collection');
				}
				/** @var class-string<T> $filterClass */
				$filterClass = static::$filterBy[$key];
				$this->filterCollections[$filterClass] = new $filterClass($value);
			}
			elseif (is_array(static::$filterBy[$key])) {
				if ($value instanceof UuidInterface) {
					$value = $value->getBytes();
				}
				elseif ($value instanceof DefaultId) {
					$value = $value->toBytes();
				}
				$filter = (array)static::$filterBy[$key];
				$this->where[$key] = $filter;
				$this->where[$key][] = (string)$value;
			}
		}

		return $this;
	}

	protected function handledFilterBy(string $key, mixed $value): bool
	{
		return false;
	}

	/**
	 * @return array<array-key, string|string[]>
	 */
	public function with(): array
	{
		$include = array_merge($this->include, static::$defaultInclude);
		return WithBuilder::convertToWith($include, static::$includeMap);
	}
}
