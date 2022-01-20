<?php declare(strict_types=1);

namespace Circli\Database\Repositories;

use Circli\Database\Values\Page;

/**
 * @property ?Page $currentPage
 */
interface PaginationAware
{
	public function getNextPage(): ?Page;

	public function getPreviousPage(): ?Page;

	public function getCurrentPage(): Page;
}
