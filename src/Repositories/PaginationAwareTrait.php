<?php declare(strict_types=1);

namespace Circli\Database\Repositories;

use Circli\Database\Values\Page;
use Circli\Database\Values\PageInterface;

trait PaginationAwareTrait
{
	private ?PageInterface $currentPage = null;

	public function getNextPage(): ?PageInterface
	{
		return $this->getCurrentPage()->next();
	}

	public function getPreviousPage(): ?PageInterface
	{
		return $this->getCurrentPage()->previous();
	}

	public function getCurrentPage(): PageInterface
	{
		if (!$this->currentPage) {
			$this->currentPage = new Page(0, 100);
		}
		return $this->currentPage;
	}

	private function handlePaginate($collectionBuilder): void
	{
		if ($collectionBuilder instanceof CollectionPaginationAware) {
			$this->currentPage = $collectionBuilder->getPage();
			if (!$this->currentPage) {
				$collectionBuilder->paginate($this->getCurrentPage());
			}
		}
	}
}
