<?php declare(strict_types=1);

namespace Circli\Database;

use Atlas\Orm\Atlas;
use Atlas\Pdo\Connection;
use Circli\Database\Atlas\TestingAtlas;
use Circli\Database\Atlas\Transaction\BeginOnWrite;
use Psr\Container\ContainerInterface;
use Starburst\Contracts\Bootloader as BootloaderContract;
use Starburst\Contracts\Extensions\DefinitionProvider;
use Starburst\Core\Context;
use Starburst\Core\Environment;
use Starburst\Core\Stage;
use Stefna\Config\Config;
use Stefna\DependencyInjection\Definition\DefinitionArray;
use Stefna\DependencyInjection\Definition\DefinitionSource;

final class Bootloader implements BootloaderContract, DefinitionProvider
{
	public function createDefinitionSource(): DefinitionSource
	{
		return new DefinitionArray([
			Connection::class => function (ContainerInterface $container) {
				$config = $container->get(Config::class);
				$dsn = $config->getString('db.dsn');
				if (!$dsn) {
					$dsnOpts = [
						'dbname=' . $config->getString('db.dbname'),
						'host=' . $config->getString('db.host'),
						'charset=' . $config->getString('db.charset'),
					];
					$type = $config->getString('db.type', 'mysql');
					$dsn = $type . ':' . implode(';', $dsnOpts);
				}

				return Connection::new($dsn, $config->getString('db.username'), $config->getString('db.password'));
			},
			\PDO::class => static function (ContainerInterface $container) {
				return $container->get(Connection::class)->getPdo();
			},
			Atlas::class => static function (ContainerInterface $container) {
				$context = $container->get(Context::class);
				if ($context->environment === Environment::Ci || $context->stage === Stage::Testing) {
					return TestingAtlas::new($container->get(Connection::class), BeginOnWrite::class);
				}
				return Atlas::new($container->get(Connection::class), BeginOnWrite::class);
			},
		]);
	}
}
