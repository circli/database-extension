<?php declare(strict_types=1);

namespace Circli\Database;

use Atlas\Orm\Atlas;
use Atlas\Pdo\Connection;
use Circli\Contracts\ExtensionInterface;
use Circli\Contracts\PathContainer;
use Circli\Core\Config;
use Circli\Core\Environment;
use Circli\Database\Atlas\TestingAtlas;
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
				$dsn = null;
				if ($config->has('db.dsn')) {
					$dsn = $config->get('db.dsn');
				}
				if (!$dsn) {
					$dsnOpts = [
						'dbname=' . $config->get('db.dbname'),
						'host=' . $config->get('db.host'),
						'charset=' . $config->get('db.charset'),
					];
					$type = $config->get('db.type') ?? 'mysql';
					$dsn = $type . ':' . implode(';', $dsnOpts);
				}

				$connection = Connection::new($dsn, $config->get('db.username'), $config->get('db.password'));
				WhereUuid::preCalculateDriver($connection);
				return $connection;
			},
			\PDO::class => static function (ContainerInterface $container) {
				return $container->get(Connection::class)->getPdo();
			},
			Atlas::class => static function (ContainerInterface $container) {
				if ($container->has(Environment::class) && $container->get(Environment::class)->is(Environment::TESTING())) {
					return TestingAtlas::new($container->get(Connection::class), BeginOnWrite::class);
				}
				return Atlas::new($container->get(Connection::class), BeginOnWrite::class);
			},
		];
	}
}
