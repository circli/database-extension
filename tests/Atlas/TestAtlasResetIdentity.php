<?php declare(strict_types=1);

namespace Circli\Database\Tests\Atlas;

use Circli\Database\Atlas\TestingAtlas;
use Circli\Database\Tests\Atlas\DataSource\People\People;
use PHPUnit\Framework\TestCase;

final class TestAtlasResetIdentity extends TestCase
{
	public function testReset()
	{
		$pdoConnection = new \PDO('sqlite::memory:');
		$atlas = TestingAtlas::new($pdoConnection);

		$mapper = $atlas->mapper(People::class);

		$atlas->resetIdentityMap($mapper);

		//todo assert
	}
}
