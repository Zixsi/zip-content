<?php

namespace Zixsihub\ZipContent\Validator;

use Zixsihub\ZipContent\Rule\RuleInterface;

abstract class AbstractValidator implements ValidatorInterface
{
	
	/** @var string */
	protected $errors = [];
	
	/** @var RuleInterface */
	protected $rules = [];
	
	/**
	 * @param RuleInterface $rule
	 */
	public function __construct(RuleInterface ...$rule)
	{
		$this->rules = $rule;
	}

	/**
	 * @return array
	 */
	public function getErrors(): array
	{
		return $this->errors;
	}

}
