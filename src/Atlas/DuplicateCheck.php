<?php declare(strict_types=1);

namespace Circli\Database\Atlas;

final class DuplicateCheck
{
	public static function checkPDOException(\PDOException $e): bool
	{
		return strpos($e->getMessage(), '1062 Duplicate entry') ||
			strpos($e->getMessage(), '19 UNIQUE constraint failed');
	}
}
