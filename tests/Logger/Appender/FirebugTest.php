<?php

require_once 'PHPUnit/Framework.php';

require_once $GLOBALS['LOGGER_ROOT'] . 'Logger.php';
require_once $GLOBALS['LOGGER_ROOT'] . 'Logger/Appender/Firebug.php';
require_once $GLOBALS['LOGGER_ROOT'] . 'Logger/Message.php';

class Logger_Appender_FirebugTest extends PHPUnit_Framework_TestCase
{
    public function testMemory()
    {
        $cfg   = array();
        $class = get_class($this);

        $appender = $this->getMock('Logger_Appender_Firebug', array('sendOutput'), array($class, $cfg));

        $appender->expects($this->any())
                 ->method('sendOutput')
                 ->will($this->returnArgument(0));

        $msg = new Logger_Message($class, 'Test Message', Logger_Category::CRIT, null, null, null, array());

        $output = $appender->log($msg);

        $this->assertType('Logger_Appender_Firebug', $appender);
        $this->assertSame($class, $appender->getName());

        $this->assertGreaterThan(0, strpos($output, 'CRIT - Logger_Appender_FirebugTest - Test Message'));

    }
}
?>