<?php declare(strict_types=1);

namespace Circli\Database\Repositories;

use Atlas\Mapper\Record;

/**
 * @template R of Record
 */
interface Entity
{
	/**
	 * @param R $record
	 */
	public static function fromRecord(Record $record): self;
}
