<?php

namespace Zixsihub\ZipContent\Validator;

use Zixsihub\ZipContent\Content;

interface ValidatorInterface
{
	/**
	 * @param Content[] $items
	 * @return bool
	 */
	public function validate(array $items): bool;
	
	/**
	 * @return string[]
	 */
	public function getErrors(): array;
}
