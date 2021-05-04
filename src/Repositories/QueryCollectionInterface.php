<?php declare(strict_types=1);

namespace Circli\Database\Repositories;

interface QueryCollectionInterface
{
	public function filterBy(string $key, mixed $value): static;
}
