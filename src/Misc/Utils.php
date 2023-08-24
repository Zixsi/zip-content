<?php

namespace Zixsihub\ZipContent\Misc;

final class Utils
{

	private const UNITS = [
		'b' => 1,
		'k' => 1024,
		'm' => 1048576,
		'g' => 1073741824
	];

	public static function humanReadableToBytes(string $size): int
	{
		return (int) $size * (self::UNITS[strtolower(substr($size, -1))] ?? 1);
	}

}
