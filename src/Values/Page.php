<?php declare(strict_types=1);

namespace Circli\Database\Values;

final class Page implements PageInterface
{
	private bool $eol = false;

	public function __construct(
		private int $offset,
		private int $limit,
	) {}

	/**
	 * @param array<string, mixed> $buildData
	 */
	public static function fromArray(array $buildData): self
	{
		$limit = 100;
		$offset = 0;
		foreach ($buildData as $key => $value) {
			if ($key === 'limit' || $key === 'l') {
				$limit = (int)$value;
			}
			elseif ($key === 'offset' || $key === 'o') {
				$offset = (int)$value;
			}
		}

		$limit = $limit <= 0 || $limit > 1000 ? 100 : $limit;
		$offset = $offset < 0 ? 0 : $offset;

		return new self($offset, $limit);
	}

	public function next(): ?self
	{
		if ($this->eol) {
			return null;
		}
		$next = clone $this;
		$next->offset += $next->limit;
		return $next;
	}

	public function previous(): ?self
	{
		$previous = clone $this;
		$previous->offset -= $previous->limit;
		if ($previous->offset < 0) {
			return null;
		}
		return $previous;
	}

	/**
	 * @return array<string, mixed>
	 */
	public function toArray(): array
	{
		return [
			'o' => $this->offset,
			'l' => $this->limit,
		];
	}

	public function getLimit(): int
	{
		return $this->limit;
	}

	public function getOffset(): int
	{
		return $this->offset;
	}

	public function setEol(): void
	{
		$this->eol = true;
	}
}
