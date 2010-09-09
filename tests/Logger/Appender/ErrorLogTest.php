<?php

require_once 'PHPUnit/Framework.php';

require_once $GLOBALS['LOGGER_ROOT'] . 'Logger.php';
require_once $GLOBALS['LOGGER_ROOT'] . 'Logger/Appender/ErrorLog.php';
require_once $GLOBALS['LOGGER_ROOT'] . 'Logger/Category.php';
require_once $GLOBALS['LOGGER_ROOT'] . 'Logger/Message.php';

class Logger_Appender_ErrorLogTest extends PHPUnit_Framework_TestCase
{
    public function testErrorLog()
    {
        $file = tempnam('/tmp', 'php.');
        $dir  = dirname($file);
        $file = basename($file);

        $appender = new Logger_Appender_ErrorLog('A1', array('logging.appender.A1.file' => basename($file)));
        $appender->setDir($dir);
        $appender->setFileName($file);

        if (file_exists($appender->getFilePath($file))) {
            unlink($appender->getFilePath($file));
        }

        $appender->log(new Logger_Message(get_class($this), 'Test Message', Logger_Category::CRIT, null, null, null, array()));

        $this->assertType('Logger_Appender_ErrorLog', $appender);
        $this->assertTrue(file_exists($appender->getFilePath($file)));
    }

    /**
     * @expectedException Logger_Exception
     */
    public function testErrorLogNullDirectory()
    {
        $appender = new Logger_Appender_ErrorLog('A5', array());
        $appender->setDir(null);
    }
}
?>