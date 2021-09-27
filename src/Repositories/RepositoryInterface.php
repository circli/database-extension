<?php declare(strict_types=1);

namespace Circli\Database\Repositories;

use Circli\Database\Exception\NotFound;
use Circli\Database\Values\GenericId;

/**
 * @template I of GenericId
 * @template B of BuilderInterface
 * @template E
 * @template C
 */
interface RepositoryInterface
{
	/**
	 * @throws NotFound
	 * @param I $id
	 * @param array<array-key, string> $include
	 * @return E
	 */
	public function findById(GenericId $id, array $include = null);

	/**
	 * @param B $collectionBuilder
	 * @return C
	 */
	public function findByQueryCollection(BuilderInterface $collectionBuilder);
}
