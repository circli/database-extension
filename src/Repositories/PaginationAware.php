<?php declare(strict_types=1);

namespace Circli\Database\Repositories;

use Circli\Database\Values\PageInterface;

/**
 * @property ?PageInterface $currentPage
 */
interface PaginationAware
{
	public function getNextPage(): ?PageInterface;

	public function getPreviousPage(): ?PageInterface;

	public function getCurrentPage(): PageInterface;
}
