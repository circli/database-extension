<?php declare(strict_types=1);

namespace Circli\Database\Values;

interface PageInterface
{
	public function next(): ?self;

	public function previous(): ?self;

	/**
	 * @return array<string, mixed>
	 */
	public function toArray(): array;

	public function getLimit(): int;

	public function getOffset(): mixed;

	public function setEol(): void;
}
