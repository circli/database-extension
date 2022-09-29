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

	public static function fromBytes(string $id): static
	{
		return new static(GenericId::NO_INT_ID, Uuid::fromBytes($id));
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
}
