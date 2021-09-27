<?php declare(strict_types=1);

namespace Circli\Database\Repositories;

final class WithBuilder
{
	/**
	 * @param array<array-key, string> $include
	 * @param  array<array-key, mixed> $includeMap
	 * @return array<array-key, string|string[]>
	 */
	public static function convertToWith(array $include, array $includeMap): array
	{
		$with = [];
		foreach ($include as $key) {
			if (isset($includeMap[$key])) {
				foreach ($includeMap[$key] as $relKey => $rel) {
					if (is_array($rel)) {
						$with[$relKey] = $rel;
					}
					else {
						$with[] = $rel;
					}
				}
			}
		}
		return $with;
	}
}
