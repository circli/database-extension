<?php declare(strict_types=1);

namespace Circli\Database\Repositories;

use Atlas\Mapper\Record;
use Circli\Database\Tests\Atlas\DataSource\People\PeopleRecord;

/**
 * @implements Entity<PeopleRecord>
 */
final class GenericEntity implements Entity
{
	public static function fromRecord(Record $record): static
	{
		return new self($record);
	}

	private function __construct(
		public Record $record,
	) {}
}
