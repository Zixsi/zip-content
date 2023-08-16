<?php

namespace Zixsihub\ZipContent;

use Zixsihub\ZipContent\Exception\ValidationException;
use Zixsihub\ZipContent\Exception\ZipContentException;
use Zixsihub\ZipContent\Rule\RuleInterface;

final class ZipContentValidator
{	
	/** @var RuleInterface[] */
	private $rules = [];
	
	/** @var Content[] */
	private $content = [];
	
	/** @var bool */
	private $stopOnFirstFail = false;
	
	/** @var string[] */
	private $errors = [];
	
	/**
	 * @param array $rules
	 * @return $this
	 */
	public final function setRules(array $rules)
	{
		foreach ($rules as $rule) {
			if (($rule instanceof RuleInterface) === false) {
				throw new ZipContentException($rule . ' must be an instance of a class ' . RuleInterface::class);
			}
			
			$this->rules[] = $rule;
		}
		
		return $this;
	}
	
	/** 
	 * @return $this
	 */
	public final function stopOnFirstFail()
	{
		$this->stopOnFirstFail = true;
		
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getError(string $separator = "\n"): string
	{
		return implode($separator, $this->errors);
	}
	
	/**
	 * @return bool
	 */
	private function validateContent(): bool
	{
		if (count($this->rules) === 0) {
			return true;
		}
		
		foreach ($this->content as $item) {
			if ($this->validateContentItem($item) === false && $this->stopOnFirstFail) {
				return false;
			}
		}
		
		return count($this->errors) ? false : true;
	}

	/**
	 * @param Content $content
	 * @return bool
	 */
	private function validateContentItem(Content $content): bool
	{
		$success = true;
		
		foreach ($this->rules as $rule) {
			try {
				$rule->check($content);
			} catch (ValidationException $ex) {
				$success = false;
				$this->errors[] = $ex->getMessage();
				
				if ($this->stopOnFirstFail) {
					break;
				}
			}
		}
		
		return $success;
	}
	
}
