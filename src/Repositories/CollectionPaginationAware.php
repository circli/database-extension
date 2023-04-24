<?php declare(strict_types=1);

namespace Circli\Database\Repositories;

use Circli\Database\Values\PageInterface;

interface CollectionPaginationAware
{
	public function paginate(PageInterface $page): void;

	/**
	 * @phpstan-pure
	 */
	public function getPage(): ?PageInterface;
}
