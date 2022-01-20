<?php declare(strict_types=1);

namespace Circli\Database\Repositories;

use Circli\Database\Values\Page;

interface CollectionPaginationAware
{
	public function paginate(Page $page): void;

	/**
	 * @phpstan-pure
	 */
	public function getPage(): ?Page;
}
