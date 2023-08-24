<?php

namespace Zixsihub\ZipContent\Rule;

use Zixsihub\ZipContent\Content;
use Zixsihub\ZipContent\Exception\ValidationException;

class NameRule implements RuleInterface
{
	
	/** @var string */
	private $pattern;
	
	public function __construct(string $pattern)
	{
		$this->pattern = $pattern;
	}
	
	/**
	 * @param Content $content
	 * @return void
	 * @throws ValidationException
	 */
	public function check(Content $content): void
	{
		if ((bool) preg_match($this->pattern, $content->getPath()) === false) {
			throw new ValidationException(
				sprintf(
					'File path `zip://%s` contains invalid characters. Available  pattern `%s`', 
					$content->getPath(),
					$this->pattern
				)
			);
		}
	}
	
}
