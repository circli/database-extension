<?php declare(strict_types=1);

namespace Circli\Database\Repositories;

use Circli\Database\Values\Page;

trait PaginationAwareTrait
{
	private ?Page $currentPage = null;

	public function getNextPage(): ?Page
	{
		return $this->getCurrentPage()->next();
	}

	public function getPreviousPage(): ?Page
	{
		return $this->getCurrentPage()->previous();
	}

	public function getCurrentPage(): Page
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
