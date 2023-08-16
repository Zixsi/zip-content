<?php

include __DIR__ . '/../vendor/autoload.php';

$file = __DIR__ . '/example2.zip';

$content = new \Zixsihub\ZipContent\Archive($file);
$status = $content->validate(
	new Zixsihub\ZipContent\Validator\SimpleValidator(
		new Zixsihub\ZipContent\Rule\ExtensionRule(['txt', 'jpg', 'png'])
	)
);

var_dump($status);
var_dump($content->getErrors());