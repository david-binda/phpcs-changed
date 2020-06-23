<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/index.php';

use PHPUnit\Framework\TestCase;
use function PhpcsChanged\PhpcsCommand\getCommand;

final class PhpcsCommandTest extends TestCase {
	public function testNoOptionsCommand() {
		$file = 'bin/foobar.php';
		$command = getCommand($file, []);
		$this->assertEquals("phpcs --report=json -q --stdin-path='bin/foobar.php' -", $command);
	}

	public function testCommandWithStandard() {
		$file = 'bin/foobar.php';
		$options = [
			'standard' => 'test'
		];
		$command = getCommand($file, $options);
		$this->assertEquals("phpcs --report=json -q --standard='test' --stdin-path='bin/foobar.php' -", $command);
	}

	public function testCommandWithExtensionsOption() {
		$file = 'bin/foobar.lib';
		$options = [
			'standard' => 'test',
			'extensions' => 'lib'
		];
		$command = getCommand($file, $options);
		$this->assertEquals("phpcs --report=json -q --standard='test' --extensions='lib' --stdin-path='bin/foobar.lib' -", $command);
	}
}
