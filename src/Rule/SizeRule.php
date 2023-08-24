<?php

namespace Zixsihub\ZipContent\Rule;

use Zixsihub\ZipContent\Content;
use Zixsihub\ZipContent\Exception\ValidationException;
use Zixsihub\ZipContent\Misc\Utils;

final class SizeRule implements RuleInterface
{

	/** @var int */
	private $maxSize = 0;

	/** @var int */
	private $minSize = 0;

	public function __construct(string $maxSize, string $minSize = '0')
	{
		$this->maxSize = Utils::humanReadableToBytes($maxSize);
		$this->minSize = Utils::humanReadableToBytes($minSize);
	}

	/**
	 * @param Content $content
	 * @return void
	 * @throws ValidationException
	 */
	public function check(Content $content): void
	{
		if ($content->getSize() < $this->minSize) {
			throw new ValidationException(
				sprintf(
					'File size is too small for file `zip://%s`',
					$content->getPath()
				)
			);
		}

		if ($content->getSize() > $this->minSize) {
			throw new ValidationException(
				sprintf(
					'File size is too large for file `zip://%s`',
					$content->getPath()
				)
			);
		}
	}

}
