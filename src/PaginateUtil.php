<?php declare(strict_types=1);

namespace Circli\Database;

use Circli\Database\Values\Page;

final class PaginateUtil
{
	private const DELIMITER = '|';

	public static function encode(Page $page): string
	{
		$data = $page->toArray();
		$encodedData = [];
		foreach ($data as $key => $value) {
			$encodedData[] = $key[0] . ':' . $value;
		}
		return trim(base64_encode(implode(self::DELIMITER, $encodedData)), '=');
	}

	public static function decode(string $token): Page
	{
		$data = explode(self::DELIMITER, base64_decode($token));
		$buildData = [];
		foreach ($data as $raw) {
			[$key, $value] = explode(':', $raw, 2);
			$buildData[$key] = $value;
		}
		return Page::fromArray($buildData);
	}
}
