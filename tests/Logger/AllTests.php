<?php

if (!isset($GLOBALS['LOGGER_ROOT'])) {
    $GLOBALS['LOGGER_ROOT'] = dirname(__FILE__) . '/../../';
}

require_once 'PHPUnit/Framework.php';
require_once $GLOBALS['LOGGER_ROOT'] . 'tests/LoggerTest.php';
require_once $GLOBALS['LOGGER_ROOT'] . 'tests/Logger/Appender/ErrorLogTest.php';
require_once $GLOBALS['LOGGER_ROOT'] . 'tests/Logger/Appender/FirebugTest.php';
require_once $GLOBALS['LOGGER_ROOT'] . 'tests/Logger/Appender/MemoryTest.php';
require_once $GLOBALS['LOGGER_ROOT'] . 'tests/Logger/Appender/ScribeTest.php';
require_once $GLOBALS['LOGGER_ROOT'] . 'tests/Logger/Appender/TimeRotatingTest.php';
require_once $GLOBALS['LOGGER_ROOT'] . 'tests/Logger/Builder/ConfigTest.php';
require_once $GLOBALS['LOGGER_ROOT'] . 'tests/Logger/CategoryTest.php';
require_once $GLOBALS['LOGGER_ROOT'] . 'tests/Logger/Layout/PatternTest.php';
require_once $GLOBALS['LOGGER_ROOT'] . 'tests/Logger/MessageTest.php';

if (!function_exists('loadClass')) {
    function loadClass($class) {
        $file = str_replace('_', '/', $class) . '.php';
        if (!class_exists($class)) {
            if (file_exists($GLOBALS['LOGGER_ROOT'] . $file)) {
                require_once $GLOBALS['LOGGER_ROOT'] . $file;
            } elseif (file_exists($GLOBALS['LOGGER_ROOT'] . 'tests/' . $file)) {
                require_once $GLOBALS['LOGGER_ROOT'] . 'tests/' . $file;
            }
        }
    }
}

PHPUnit_Util_Filter::addDirectoryToWhitelist($GLOBALS['LOGGER_ROOT'] . 'Logger/');

class Logger_AllTests
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Logger Test Suite');
        $suite->addTestSuite('LoggerTest');
        $suite->addTestSuite('Logger_Appender_ErrorLogTest');
        $suite->addTestSuite('Logger_Appender_FirebugTest');
        $suite->addTestSuite('Logger_Appender_MemoryTest');
        $suite->addTestSuite('Logger_Appender_ScribeTest');
        $suite->addTestSuite('Logger_Appender_TimeRotatingTest');
        $suite->addTestSuite('Logger_Builder_ConfigTest');
        $suite->addTestSuite('Logger_CategoryTest');
        $suite->addTestSuite('Logger_Layout_PatternTest');
        $suite->addTestSuite('Logger_MessageTest');
        return $suite;
    }
}
?>