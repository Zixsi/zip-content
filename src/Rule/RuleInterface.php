<?php

namespace Zixsihub\ZipContent\Rule;

use Zixsihub\ZipContent\Content;
use Zixsihub\ZipContent\ValidationException;

interface RuleInterface
{
	
	/**
	 * @param Content $content
	 * @return void
	 * @throws ValidationException
	 */
	public function check(Content $content): void;
}