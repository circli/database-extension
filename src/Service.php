<?php declare(strict_types=1);

namespace Circli\Database;

use Atlas\Mapper\Mapper;
use Atlas\Orm\Atlas;
use Atlas\Pdo\Connection;

final class Service
{
	public function __construct(
		private Atlas $atlas,
		private Connection $connection,
	) {}

	public function setConnection(Connection $connection): void
	{
		$this->connection = $connection;
	}

	public function setAtlas(Atlas $atlas): void
	{
		$this->atlas = $atlas;
	}

	public function getConnection(): Connection
	{
		return $this->connection;
	}

	public function getAtlas(): Atlas
	{
		return $this->atlas;
	}

	public function getMapper(string $mapper): Mapper
	{
		return $this->atlas->mapper($mapper);
	}
}
