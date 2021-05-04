<?php declare(strict_types=1);

namespace Circli\Database\Atlas\Transaction;

use Atlas\Mapper\Mapper;
use Atlas\Mapper\Record;

/**
 * Auto-begins a transaction on write, but does not commit or roll back.
 */
class BeginOnWrite extends NestedTransaction
{
	/**
	 * @param array<mixed> $params
	 */
	public function read(Mapper $mapper, string $method, array $params): mixed
	{
		return $mapper->$method(...$params);
	}

	public function write(Mapper $mapper, string $method, Record $record): void
	{
		if ($this->transactionDepth === 0) {
			$this->beginTransaction();
		}
		$this->connectionLocator->lockToWrite();
		$mapper->$method($record);
	}
}
