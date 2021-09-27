<?php declare(strict_types=1);

namespace Circli\Database\Tests\Repositories;

use Circli\Database\Tests\Atlas\DataSource\People\PeopleRecord;

final class People implements \JsonSerializable
{
	public static function fromRecord(PeopleRecord $record): self
	{

	}

	public function __construct(
		public string $name,
	)
	{
	}

	public function jsonSerialize()
	{
		// TODO: Implement jsonSerialize() method.
	}
}
