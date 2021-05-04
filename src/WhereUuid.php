<?php declare(strict_types=1);

namespace Circli\Database;

use Atlas\Pdo\Connection;
use Ramsey\Uuid\UuidInterface;
use Circli\Database\Values\GenericId;

final class WhereUuid
{
	private static ?string $driverCache = null;

	private static function getDriver(): string
	{
		if (self::$driverCache === null) {
			throw new \BadMethodCallException('Failed to get connection type. Need to run preCalculateDriver()');
		}
		return self::$driverCache;
	}

	public static function preCalculateDriver(Connection $connection): void
	{
		if (!self::$driverCache) {
			self::$driverCache = $connection->getDriverName();
		}
	}

	/**
	 * @return string[]
	 */
	public static function buildFromBytesAndField(string $bytes, string $field): array
	{
		$where = [$field . ' =', $bytes];
		if (self::getDriver() === 'sqlite') {
			$hex = strtoupper(bin2hex($bytes));
			$where = ["hex($field) = '{$hex}'"];
		}
		return $where;
	}

	/**
	 * @param UuidInterface|GenericId $uuid
	 * @return string[]
	 */
	public static function build($uuid): array
	{
		if ($uuid instanceof GenericId) {
			return self::buildFromBytes($uuid->toBytes());
		}
		return self::buildFromBytes($uuid->getBytes());
	}

	/**
	 * @return string[]
	 */
	public static function buildFromBytes(string $bytes): array
	{
		return self::buildFromBytesAndField($bytes, 'uuid');
	}

	public static function buildForPdo(Connection $connection, string $bytes, string $field = 'uuid'): string
	{
		if (self::$driverCache === null) {
			self::preCalculateDriver($connection);
		}
		if (self::getDriver() === 'sqlite') {
			$hex = $connection->quote(strtoupper(bin2hex($bytes)));
			return "hex($field) = {$hex}";
		}
		return $field . ' = ' . $connection->quote($bytes);
	}
}
