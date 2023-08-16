<?php

namespace Zixsihub\ZipContent\Rule;

use Zixsihub\ZipContent\Content;
use Zixsihub\ZipContent\Exception\ValidationException;

class ExtensionRule implements RuleInterface
{
	
	/** @var string[] */
	private $extensions = [];
	
	public function __construct(array $extensions)
	{
		$this->extensions = $extensions;
	}
	
	/**
	 * @param Content $content
	 * @return void
	 * @throws ValidationException
	 */
	public function check(Content $content): void
	{
		
		
		if (in_array($content->getExtension(), $this->extensions) === false) {
			throw new ValidationException(
				sprintf(
					'Unsupported extension type `%s` for file `zip://%s`', 
					$content->getExtension(),
					$content->getPath()
				)
			);
		}
	}
	
}
