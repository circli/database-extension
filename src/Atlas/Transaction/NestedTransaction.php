<?php declare(strict_types=1);

namespace Circli\Database\Atlas\Transaction;

use Atlas\Orm\Transaction\Transaction;
use Atlas\Pdo\Connection;

abstract class NestedTransaction extends Transaction
{
	private const SUPPORTED_DRIVERS = ['pgsql', 'mysql'];

	/** @var int the current transaction depth */
	protected int $transactionDepth = 0;
	/** @var array<string, bool> */
	private array $connectionSupportSavePoint = [];

	/**
	 * Test if database driver support savepoints
	 */
	protected function hasSavepoint(Connection $connection): bool
	{
		$driver = $connection->getDriverName();
		if (!isset($this->connectionSupportSavePoint[$driver])) {
			$this->connectionSupportSavePoint[$driver] = \in_array($driver, self::SUPPORTED_DRIVERS, true);
		}

		return $this->connectionSupportSavePoint[$driver];
	}

	public function beginTransaction() : void
	{
		/** @var Connection $connection */
		foreach ($this->getConnections() as $connection) {
			if ($this->transactionDepth === 0 || !$this->hasSavepoint($connection)) {
				if (!$connection->inTransaction()) {
					$connection->beginTransaction();
				}
			}
			else {
				$connection->exec("SAVEPOINT LEVEL{$this->transactionDepth}");
			}
		}

		$this->transactionDepth++;
	}

	public function commit() : void
	{
		$this->transactionDepth--;

		/** @var Connection $connection */
		foreach ($this->getConnections() as $connection) {
			if ($connection->inTransaction()) {
				if ($this->transactionDepth === 0 || !$this->hasSavepoint($connection)) {
					$connection->commit();
				}
				else {
					try {
						$connection->exec("RELEASE SAVEPOINT LEVEL{$this->transactionDepth}");
					}
					catch (\PDOException $e) {
						// Ignore error if savepoint does not exist
					}
				}
			}
		}
	}

	public function rollBack() : void
	{
		$this->transactionDepth--;

		/** @var Connection $connection */
		foreach ($this->getConnections() as $connection) {
			if ($connection->inTransaction()) {
				if ($this->transactionDepth === 0 || !$this->hasSavepoint($connection)) {
					$connection->rollBack();
				}
				else {
					$connection->exec("ROLLBACK TO SAVEPOINT LEVEL{$this->transactionDepth}");
				}
			}
		}
	}
}
