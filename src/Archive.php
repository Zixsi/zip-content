<?php

namespace Zixsihub\ZipContent;

use ZipArchive;
use Zixsihub\ZipContent\Exception\ValidationException;
use Zixsihub\ZipContent\Exception\RuntimeException;
use Zixsihub\ZipContent\Validator\ValidatorInterface;

final class Archive
{

	/** @var string */
	private $file;

	/** @var ZipArchive */
	private $archive;

	/** @var Content[] */
	private $content = [];
	
	/** @var string[] */
	private $errors = [];

	public function __construct(string $file)
	{
		$this->open($file);
		$this->load();
	}
	
	/**
	 * @return Content[]
	 */
	public final function getContentList(): array
	{
		return $this->content;
	}
	
	/**
	 * @param ValidatorInterface $validator
	 * @return bool
	 */
	public final function validate(ValidatorInterface ...$validator): bool
	{
		$this->errors = [];
		
		try {
			foreach ($validator as $validatorInstance) {
				if ($validatorInstance->validate($this->getContentList()) === false) {
					$this->errors = array_merge($this->errors, $validatorInstance->getErrors());
					
					return false;
				}
			}
		} catch (ValidationException $ex) {
			$this->errors[] = $ex->getMessage();
			
			return false;
		}
		
		return true;
	}
	
	/**
	 * @return array
	 */
	public final function getErrors(): array
	{
		return $this->errors;
	}
	
	/**
	 * @param string $path
	 * @return array
	 */
	public final function extractValidTo(string $path): array
	{
		$files = [];
		
		foreach ($this->content as $content) {
			if ($content->isValid()) {
				$files[] = $content->getPath();
			}
		}
		
		return $this->extractFilesTo($path, $files);
	}
	
	/**
	 * @param string $path
	 * @return array
	 */
	public final function extractAllTo(string $path): array
	{
		return $this->extractFilesTo(
				$path, 
				array_map(
					function (Content $file) {
						return $file->getPath();
					},
					$this->content
				)
			);
	}
	
	/**
	 * @param string $path
	 * @param array $files
	 * @return array
	 * @throws RuntimeException
	 */
	public final function extractFilesTo(string $path, array $files): array
	{
		if (file_exists($path) === false) {
			@mkdir($path, 0777, true);
		}

		if (count($files) > 0) {
			if ($this->archive->extractTo($path, $files) === false) {
				throw new RuntimeException(sprintf('Failed extract zip to %s', $path));
			}
		}
		
		return $files;
	}

	/**
	 * @param string $file
	 * @return void
	 * @throws RuntimeException
	 */
	private function open(string $file): void
	{
		if (file_exists($file) === false) {
			throw new RuntimeException('Not found file ' . $file);
		}

		$archive = new ZipArchive();
		$status = $archive->open($file);

		if ($status !== true) {
			throw new RuntimeException('Error: code ' . $status);
		}

		$this->file = $file;
		$this->archive = $archive;
	}

	/**
	 * @return void
	 */
	private function load(): void
	{
		for ($i = 0; $i < $this->archive->count(); $i++) {
			$content = new Content($this->archive->statIndex($i), $this->file);

			$this->content[] = $content;
		}
	}

}
