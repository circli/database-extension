<?php declare(strict_types=1);

namespace Circli\Database\Atlas;

use Atlas\Mapper\Identity\IdentityMap;
use Atlas\Mapper\Mapper;
use Atlas\Mapper\MapperLocator;
use Atlas\Mapper\MapperQueryFactory;
use Atlas\Orm\Atlas;
use Atlas\Orm\Transaction\AutoCommit;
use Atlas\Orm\Transaction\Transaction;
use Atlas\Pdo\ConnectionLocator;
use Atlas\Table\TableLocator;

class TestingAtlas extends Atlas
{

	public static function new(...$args) : TestingAtlas
	{
		$transactionClass = AutoCommit::CLASS;

		$end = end($args);
		if (is_string($end) && is_subclass_of($end, Transaction::CLASS)) {
			$transactionClass = array_pop($args);
		}

		$connectionLocator = ConnectionLocator::new(...$args);

		$tableLocator = new TableLocator(
			$connectionLocator,
			new MapperQueryFactory()
		);

		return new TestingAtlas(
			new MapperLocator($tableLocator),
			new $transactionClass($connectionLocator)
		);
	}

	public function resetIdentityMap(string|Mapper $mapper): void
	{
		if (is_string($mapper)) {
			$mapper = $this->mapperLocator->get($mapper);
		}
		$mapperReflection = new \ReflectionClass($mapper);
		$identityMapVar = $mapperReflection->getProperty('identityMap');
		$identityMapVar->setAccessible(true);
		/** @var IdentityMap $identityMap */
		$identityMap = $identityMapVar->getValue($mapper);
		$identityMapReflection = new \ReflectionClass($identityMap);
		$serialToRowVar = $identityMapReflection->getProperty('serialToRow');
		$rowToSerialVar = $identityMapReflection->getProperty('rowToSerial');

		$serialToRowVar->setAccessible(true);
		$serialToRowVar->setValue($identityMap, []);

		$rowToSerialVar->setAccessible(true);
		$rowToSerialVar->setValue($identityMap, new \SplObjectStorage());
	}

	public function resetAllIdentityMaps(): void
	{
		$mapperLocationReflection = new \ReflectionClass($this->mapperLocator);
		$mappersVar = $mapperLocationReflection->getProperty('mappers');
		$mappersVar->setAccessible(true);
		$mappers = $mappersVar->getValue($this->mapperLocator);
		foreach ($mappers as $mapper) {
			$this->resetIdentityMap($mapper);
		}
	}
}
