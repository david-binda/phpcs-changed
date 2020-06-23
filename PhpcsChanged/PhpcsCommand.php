<?php
declare(strict_types=1);

namespace PhpcsChanged\PhpcsCommand;

function getCommand(string $file, array $options): string {
	$phpcs = getenv('PHPCS') ?: 'phpcs';

	$phpcsStandardOption = isset($options['standard']) ? ' --standard=' . escapeshellarg($options['standard']) : '';
	$phpcsExtensionsOption = isset($options['extensions']) ? ' --extensions=' . escapeshellarg($options['extensions']) : '';

	return "{$phpcs} --report=json -q" . $phpcsStandardOption . $phpcsExtensionsOption . ' --stdin-path=' .  escapeshellarg($file) . ' -';	
}
