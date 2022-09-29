<?php declare(strict_types=1);

namespace Circli\Database\Repositories;

use Atlas\Mapper\Record;

/**
 * @template R of Record
 */
interface Entity
{
	/**
	 * @phpstan-param R $record
	 */
	public static function fromRecord(Record $record): static;
}
