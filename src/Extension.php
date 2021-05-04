<?php declare(strict_types=1);

namespace Circli\Database;

use Atlas\Orm\Atlas;
use Atlas\Pdo\Connection;
use Circli\Contracts\ExtensionInterface;
use Circli\Contracts\PathContainer;
use Circli\Core\Config;
use Psr\Container\ContainerInterface;
use Circli\Database\Atlas\Transaction\BeginOnWrite;

final class Extension implements ExtensionInterface
{
	/**
	 * @return \Closure[]
	 */
	public function configure(PathContainer $pathContainer = null): array
	{
		return [
			Connection::class => function (ContainerInterface $container) {
				$config = $container->get(Config::class);
				$connection = Connection::new(
					$config->get('db.dsn'),
					$config->get('db.username'),
					$config->get('db.password')
				);
				WhereUuid::preCalculateDriver($connection);
				return $connection;
			},
			\PDO::class => static function (ContainerInterface $container) {
				return $container->get(Connection::class)->getPdo();
			},
			Atlas::class => static function (ContainerInterface $container) {
				return Atlas::new($container->get(Connection::class), BeginOnWrite::class);
			},
		];
	}
}
