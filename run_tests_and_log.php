<?php

echo "Starting test execution...\n";

$output = shell_exec(__DIR__ . '/vendor/bin/pest');

$logFile = __DIR__ . '/test_results.log';

file_put_contents($logFile, $output);

echo "Test execution finished. Log file should be at: " . $logFile . "\n";
