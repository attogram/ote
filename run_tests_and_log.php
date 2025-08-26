<?php

echo "Starting test execution...\n";

$options = getopt('', ['format:']);
$format = $options['format'] ?? 'text';

$logFile = __DIR__ . '/test_results';
$pestCommand = __DIR__ . '/vendor/bin/pest';

switch ($format) {
    case 'junit':
        $logFile .= '.xml';
        $pestCommand .= " --log-junit $logFile";
        break;
    case 'testdox-html':
        $logFile .= '.html';
        $pestCommand .= " --testdox-html $logFile";
        break;
    case 'testdox-text':
        $logFile .= '.txt';
        $pestCommand .= " --testdox-text $logFile";
        break;
    case 'teamcity':
        $logFile .= '.teamcity.txt';
        $pestCommand .= " --log-teamcity $logFile";
        break;
    case 'text':
    default:
        $logFile .= '.log';
        $output = shell_exec($pestCommand);
        file_put_contents($logFile, $output);
        echo "Test execution finished. Log file should be at: " . $logFile . "\n";
        exit(0);
}

echo "Running command: $pestCommand\n";
shell_exec($pestCommand);

echo "Test execution finished. Log file should be at: " . $logFile . "\n";
