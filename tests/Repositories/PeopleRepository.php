<?php declare(strict_types=1);

namespace Circli\Database\Tests\Repositories;

use Circli\Database\Repositories\RepositoryDefaultTrait;
use Circli\Database\Repositories\RepositoryInterface;
use Circli\Database\Tests\Atlas\DataSource\People\People as PeopleMapper;
use Psr\Log\LoggerInterface;

/**
 * @implements RepositoryInterface<PeopleId, PeopleQueryBuilder, People, PeopleCollection>
 */
final class PeopleRepository implements RepositoryInterface
{
	protected string $entityClass = People::class;
	protected string $collectionClass = PeopleCollection::class;

	use RepositoryDefaultTrait;

	public function __construct(
		private PeopleMapper $mapper,
		private LoggerInterface $logger,
	) {}
}
