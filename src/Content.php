<?php

namespace Zixsihub\ZipContent;

final class Content
{

	private const DEFAULT_MIME = 'application/octet-stream';

	/** @var bool */
	private $valid = true;
	
	/** @var int */
	private $level = 0;

	/** @var string */
	private $path;

	/** @var string */
	private $pathInArchive;

	/** @var int */
	private $size;

	/** @var string */
	private $extension;

	/** @var string */
	private $mime;

	/** @var bool */
	private $isDir = false;

	public function __construct(array $data, string $archivePath)
	{
		$this->path = (string) ($data['name'] ?? '');
		$this->pathInArchive = sprintf('zip://%s#%s', $archivePath, $this->path);
		$this->size = (int) ($data['size'] ?? 0);
		$this->level = substr_count($this->path, DIRECTORY_SEPARATOR);
		$this->isDir = (substr($this->path, -1, 1) === DIRECTORY_SEPARATOR);
	}

	/**
	 * @return int
	 */
	public function getLevel(): int
	{
		return $this->level;
	}

	/**
	 * @return string
	 */
	public function getPath(): string
	{
		return $this->path;
	}

	/**
	 * @return string
	 */
	public function getPathInArchive(): string
	{
		return $this->pathInArchive;
	}

	/**
	 * @return int
	 */
	public function getSize(): int
	{
		return $this->size;
	}

	/**
	 * @return string
	 */
	public function getExtension(): string
	{
		if (empty($this->extension)) {
			if ($this->isDir()) {
				$this->extension = '';
			} else {
				$this->extension = (string)  strtolower(
					pathinfo($this->pathInArchive, PATHINFO_EXTENSION)
				);
			}
		}

		return $this->extension;
	}

	/**
	 * @return string
	 */
	public function getMime(): string
	{
		if (empty($this->mime)) {
			if ($this->isDir()) {
				$this->mime = self::DEFAULT_MIME;
			} else {
				$this->mime = (string) mime_content_type($this->pathInArchive) ?: self::DEFAULT_MIME;
			}
		}

		return $this->mime;
	}

	/**
	 * @return bool
	 */
	public function isDir(): bool
	{
		return $this->isDir;
	}
	
	/**
	 * @return bool
	 */
	public function isValid(): bool
	{
		return $this->valid;
	}

	/**
	 * @return void
	 */
	public function setAsValid(): void
	{
		$this->valid = true;
	}
	
	/**
	 * @return void
	 */
	public function setAsInvalid(): void
	{
		$this->valid = false;
	}

}
