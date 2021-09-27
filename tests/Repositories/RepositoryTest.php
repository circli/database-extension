<?php declare(strict_types=1);

namespace Circli\Database\Tests\Repositories;

use Circli\Database\Repositories\BuilderInterface;
use Circli\Database\Values\GenericId;
use PHPUnit\Framework\TestCase;

class RepositoryTest extends TestCase
{
	public function test(): void
	{
		$repo = new PeopleRepository();
		$id = PeopleId::new();
		$name = $repo->findById($id)->name;

		/** @var PeopleQueryBuilder $builder */
		$builder = null;

		$collection = $repo->findByQueryCollection($builder);

		$people = $collection->offsetGet(0);
		if ($people) {
			$name = $people->name;
		}

		foreach ($collection as $p) {
			echo $p->name;
		}



	}
}
