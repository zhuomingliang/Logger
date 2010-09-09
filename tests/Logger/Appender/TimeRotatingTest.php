<?php

require_once 'PHPUnit/Framework.php';

require_once $GLOBALS['LOGGER_ROOT'] . 'Logger.php';
require_once $GLOBALS['LOGGER_ROOT'] . 'Logger/Appender/TimeRotating.php';
require_once $GLOBALS['LOGGER_ROOT'] . 'Logger/Category.php';
require_once $GLOBALS['LOGGER_ROOT'] . 'Logger/Message.php';

class Logger_Appender_TimeRotatingTest extends PHPUnit_Framework_TestCase
{
    public function rollover($interval = null, $modifier = null)
    {
        $file = tempnam('/tmp', 'php.');
        $dir  = dirname($file);
        $file = basename($file);

        $config = array(
            'logging.appender.A1.logdir' => $dir,
            'logging.appender.A1.file'   => $file,
        );

        if ($interval !== null) {
            $config['logging.appender.A1.interval'] = $interval;
        }

        if ($modifier !== null) {
            $config['logging.appender.A1.modifier'] = $modifier;
        }

        $appender = new Logger_Appender_TimeRotating('A1', $config);

        if (file_exists($appender->getFilePath())) {
            unlink($appender->getFilePath());
        }

        $appender->log(new Logger_Message(get_class($this), 'Test Message', Logger_Category::CRIT, null, null, null, array()));

        $this->assertType('Logger_Appender_TimeRotating', $appender);
        $this->assertTrue(file_exists($appender->getFilePath()));
    }

    public function testRollover()
    {
        $this->rollover();
        $this->rollover(5, 'd');
        $this->rollover(5, 'h');
    }
}
?>