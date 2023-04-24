<?php declare(strict_types=1);

namespace Circli\Database;

use Circli\Database\Values\Page;
use Circli\Database\Values\PageInterface;

final class PaginateUtil
{
	private const DELIMITER = '|';

	/** @var null|callable(PageInterface): string */
	private static $encoder = null;
	/** @var null|callable(string): PageInterface */
	private static $decoder = null;

	public static function setEncoder(callable $encoder): void
	{
		self::$encoder = $encoder;
	}

	public static function setDecoder(callable $decoder): void
	{
		self::$decoder = $decoder;
	}

	public static function encode(PageInterface $page): string
	{
		if (isset(self::$encoder)) {
			return (self::$encoder)($page);
		}
		$data = $page->toArray();
		$encodedData = [];
		foreach ($data as $key => $value) {
			$encodedData[] = $key[0] . ':' . $value;
		}
		return trim(base64_encode(implode(self::DELIMITER, $encodedData)), '=');
	}

	public static function decode(string $token): PageInterface
	{
		if (isset(self::$decoder)) {
			return (self::$decoder)($token);
		}
		$data = explode(self::DELIMITER, base64_decode($token));
		$buildData = [];
		foreach ($data as $raw) {
			[$key, $value] = explode(':', $raw, 2);
			$buildData[$key] = $value;
		}
		return Page::fromArray($buildData);
	}
}
