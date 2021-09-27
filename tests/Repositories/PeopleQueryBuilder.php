<?php declare(strict_types=1);

namespace Circli\Database\Tests\Repositories;

use Atlas\Mapper\MapperSelect;
use Circli\Database\Repositories\BuilderInterface;
use Circli\Database\Tests\Atlas\DataSource\People\PeopleSelect;

/**
 * @implements BuilderInterface<PeopleSelect>
 */
final class PeopleQueryBuilder implements BuilderInterface
{
	public function build(MapperSelect $select): MapperSelect
	{
		return $select;
	}
}
