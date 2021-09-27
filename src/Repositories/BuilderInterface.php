<?php declare(strict_types=1);

namespace Circli\Database\Repositories;

use Atlas\Mapper\MapperSelect;

/**
 * @template M of MapperSelect
 */
interface BuilderInterface
{
	/**
	 * @param M $select
	 * @return M
	 */
	public function build(MapperSelect $select): MapperSelect;
}
