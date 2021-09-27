<?php declare(strict_types=1);

$pdoConnection = new \PDO('sqlite::memory:');
$pdoConnection->exec(file_get_contents(__DIR__ . '/atlas.sql'));

return [
	'pdo' => [
		$pdoConnection,
	],
	'namespace' => 'Circli\\Database\\Tests\\Atlas\\DataSource',
	'directory' => './tests/Atlas/DataSource',
	'transform' => static function (string $table): ?string {
		return [
			'people' => 'People',
		][$table] ?? null;
	},
];
