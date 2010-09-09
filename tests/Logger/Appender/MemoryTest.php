<?php

require_once 'PHPUnit/Framework.php';

require_once $GLOBALS['LOGGER_ROOT'] . 'Logger.php';
require_once $GLOBALS['LOGGER_ROOT'] . 'Logger/Appender/Memory.php';
require_once $GLOBALS['LOGGER_ROOT'] . 'Logger/Message.php';

class Logger_Appender_MemoryTest extends PHPUnit_Framework_TestCase
{
    public function testMemory()
    {
        $cfg   = array();
        $class = get_class($this);

        $appender = new Logger_Appender_Memory($class, $cfg);
        $msg      = new Logger_Message($class, 'Test Message', Logger_Category::CRIT, null, null, null, array());
        $appender->log($msg);

        $this->assertType('Logger_Appender_Memory', $appender);
        $this->assertSame($class, $appender->getName());

        $messages = $appender->getMessages();
        $this->assertArrayHasKey(0, $messages);

        $this->assertSame($class, $messages[0]->name);
        $this->assertSame('Test Message', $messages[0]->message);
        $this->assertSame(Logger_Category::CRIT, $messages[0]->level);
    }
}
?>