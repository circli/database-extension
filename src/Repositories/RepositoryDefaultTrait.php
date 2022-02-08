<?php declare(strict_types=1);

namespace Circli\Database\Repositories;

use Atlas\Mapper\Mapper;
use Circli\Database\Exception\NotFound;
use Circli\Database\Values\GenericId;
use Psr\Log\LoggerInterface;

/**
 * @property Mapper $mapper
 * @property LoggerInterface $logger
 * @property class-string $entityClass
 * @property class-string $collectionClass
 */
trait RepositoryDefaultTrait
{
	public function findById(GenericId $id, array $include = null)
	{
		if (!isset($this->entityClass)) {
			throw new \BadMethodCallException('Repository missing $entityClass property');
		}

		$select = $this->mapper->select();
		$select->where('id = ', $id->toBytes());

		if ($include && isset($this->includeMap)) {
			$select->with(WithBuilder::convertToWith($include, $this->includeMap));
		}

		$record = $select->fetchRecord();
		if (!$record) {
			throw NotFound::generic($id);
		}

		$entityClass = $this->entityClass;
		return $entityClass::fromRecord($record);
	}

	public function findByQueryCollection(BuilderInterface $collectionBuilder): AbstractCollection
	{
		if (!isset($this->collectionClass)) {
			throw new \BadMethodCallException('Repository missing $collectionClass property');
		}

		if ($this instanceof PaginationAware && $collectionBuilder instanceof CollectionPaginationAware) {
			$this->currentPage = $collectionBuilder->getPage();
			if (!$this->currentPage) {
				$collectionBuilder->paginate($this->getCurrentPage());
			}
		}

		$select = $this->mapper->select();
		$select = $collectionBuilder->build($select);

		try {
			if ($collectionBuilder instanceof WithAware) {
				$select->with($collectionBuilder->with());
			}

			$records = $select->fetchRecordSet();
			$collectionClass = $this->collectionClass;
			/** @var AbstractCollection $collection */
			$collection = $collectionClass::fromRecordSet($records);
			if ($this instanceof PaginationAware) {
				$collection->setTotalCount($select->fetchCount());
			}
			return $collection;
		}
		catch (\PDOException $e) {
			$this->logger->error('Failed to fetch from db', [
				'mapper' => $this->mapper::class,
				'message' => $e->getMessage(),
				'exception' => $e,
			]);
			throw $e;
		}
	}
}
