<?php

namespace Zixsihub\ZipContent\Validator;

use Zixsihub\ZipContent\Content;
use Zixsihub\ZipContent\Exception\ValidationException;

class SimpleValidator extends AbstractValidator
{

	/**
	 * @param Content[] $items
	 * @return bool
	 */
	public function validate(array $items): bool
	{
		foreach ($items as $item) {
			if ($this->checkItemRules($item) === false) {
				$item->setAsInvalid();
			}
		}

		return count($this->getErrors()) === 0;
	}

	/**
	 * @param Content $item
	 * @return bool
	 */
	private function checkItemRules(Content $item): bool
	{
		if ($item->isDir()) {
			return true;
		}
		
		foreach ($this->rules as $rule) {
			try {
				$rule->check($item);
			} catch (ValidationException $ex) {
				$this->errors[] = $ex->getMessage();

				return false;
			}
		}

		return true;
	}

}
