<?php
declare(strict_types=1);

namespace PhpcsChanged\SvnWorkflow;

use PhpcsChanged\NonFatalException;

function validateSvnFileExists(string $svnFile, callable $isReadable): void {
	if (! $isReadable($svnFile)) {
		throw new \Exception("Cannot read file '{$svnFile}'");
	}
}

function getSvnUnifiedDiff(string $svnFile, string $svn, callable $executeCommand, callable $debug): string {
	$unifiedDiffCommand = "{$svn} diff " . escapeshellarg($svnFile);
	$debug('running diff command:', $unifiedDiffCommand);
	$unifiedDiff = $executeCommand($unifiedDiffCommand);
	if (! $unifiedDiff) {
		throw new NonFatalException("Cannot get svn diff for file '{$svnFile}'; skipping");
	}
	$debug('diff command output:', $unifiedDiff);
	return $unifiedDiff;
}

function isNewSvnFile(string $svnFile, string $svn, callable $executeCommand, callable $debug): bool {
	$svnStatusCommand = "${svn} info " . escapeshellarg($svnFile);
	$debug('checking svn status of file with command:', $svnStatusCommand);
	$svnStatusOutput = $executeCommand($svnStatusCommand);
	$debug('svn status output:', $svnStatusOutput);
	if (! $svnStatusOutput || false === strpos($svnStatusOutput, 'Schedule:')) {
		throw new \Exception("Cannot get svn info for file '{$svnFile}'");
	}
	return (false !== strpos($svnStatusOutput, 'Schedule: add'));
}

function getSvnBasePhpcsOutput(string $svnFile, string $svn, string $phpcs, string $phpcsStandardOption, callable $executeCommand, callable $debug): string {
	$oldFilePhpcsOutputCommand = "${svn} cat " . escapeshellarg($svnFile) . " | {$phpcs} --report=json" . $phpcsStandardOption;
	$debug('running orig phpcs command:', $oldFilePhpcsOutputCommand);
	$oldFilePhpcsOutput = $executeCommand($oldFilePhpcsOutputCommand);
	if (! $oldFilePhpcsOutput) {
		throw new \Exception("Cannot get old phpcs output for file '{$svnFile}'");
	}
	$debug('orig phpcs command output:', $oldFilePhpcsOutput);
	return $oldFilePhpcsOutput;
}

function getSvnNewPhpcsOutput(string $svnFile, string $phpcs, string $cat, string $phpcsStandardOption, callable $executeCommand, callable $debug): string {
	$newFilePhpcsOutputCommand = "{$cat} " . escapeshellarg($svnFile) . " | {$phpcs} --report=json" . $phpcsStandardOption;
	$debug('running new phpcs command:', $newFilePhpcsOutputCommand);
	$newFilePhpcsOutput = $executeCommand($newFilePhpcsOutputCommand);
	if (! $newFilePhpcsOutput) {
		throw new \Exception("Cannot get new phpcs output for file '{$svnFile}'");
	}
	$debug('new phpcs command output:', $newFilePhpcsOutput);
	return $newFilePhpcsOutput;
}