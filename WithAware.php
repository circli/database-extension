<?php declare(strict_types=1);

namespace Circli\Database\Repositories;

interface WithAware
{
	public function with(): array;
}
