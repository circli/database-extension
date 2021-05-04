<?php declare(strict_types=1);

namespace Circli\Database\Values;

interface GenericId
{
	public const NO_INT_ID = -1;

	public function toInt(): int;
	public function toString(): string;
	public function toBytes(): string;

	public function isUuid(): bool;
}
