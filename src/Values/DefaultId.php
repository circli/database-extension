<?php declare(strict_types=1);

namespace Circli\Database\Values;

use Atlas\Mapper\Record;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class DefaultId implements \JsonSerializable, GenericId
{
	public static function fromGenericRecord(Record $record): static
	{
		if (isset($record->uuid, $record->id)) {
			return new static((int)$record->id, Uuid::fromBytes($record->uuid));
		}
		throw new \InvalidArgumentException('Record don\'t have id and uuid');
	}

	public static function fromInteger(int $id): static
	{
		return new static($id, Uuid::uuid4());
	}

	public static function fromString(string $id): static
	{
		return new static(GenericId::NO_INT_ID, Uuid::fromString($id));
	}

	public static function tryString(string $id): ?static
	{
		if (!$id) {
			return null;
		}
		try {
			$uuid = Uuid::fromString($id);
			return new static(GenericId::NO_INT_ID, $uuid);
		}
		catch (\Throwable) {
			return null;
		}
	}

	public static function fromBytes(string $id): static
	{
		return new static(GenericId::NO_INT_ID, Uuid::fromBytes($id));
	}

	public static function tryBytes(string $id): ?static
	{
		if (!$id) {
			return null;
		}
		try {
			$uuid = Uuid::fromBytes($id);
			return new static(GenericId::NO_INT_ID, $uuid);
		}
		catch (\Throwable) {
			return null;
		}
	}

	public static function new(?UuidInterface $uuid = null): static
	{
		return new static(GenericId::NO_INT_ID, $uuid ?? Uuid::uuid4());
	}

	final protected function __construct(
		private int $id,
		private UuidInterface $uuid,
	) {}

	public function toInt(): int
	{
		return $this->id;
	}

	public function toString(): string
	{
		return $this->uuid->toString();
	}

	public function toBytes(): string
	{
		return $this->uuid->getBytes();
	}

	public function isUuid(): bool
	{
		return $this->id === GenericId::NO_INT_ID;
	}

	public function jsonSerialize(): string
	{
		return $this->uuid->toString();
	}

	public function equals(mixed $id): bool
	{
		if (!$id instanceof static) {
			return false;
		}
		return $id->toString() === $this->uuid->toString();
	}

	public static function cast(UuidInterface|GenericId|string $id): static
	{
		if (is_string($id)) {
			if (Uuid::isValid($id)) {
				return static::fromString($id);
			}
			return static::fromBytes($id);
		}

		if ($id instanceof static) {
			return $id;
		}

		if ($id instanceof DefaultId) {
			return new static(
				$id->id,
				$id->uuid,
			);
		}

		return static::fromString($id->toString());
	}
}
