<?php

require_once 'PHPUnit/Framework.php';

require_once $GLOBALS['LOGGER_ROOT'] . 'Logger.php';
require_once $GLOBALS['LOGGER_ROOT'] . 'Logger/Appender/Scribe.php';
require_once $GLOBALS['LOGGER_ROOT'] . 'Logger/Message.php';

if (!class_exists('LogEntry')) {
    class LogEntry
    {
        public $category;
        public $message;
    }
}

if (!class_exists('FakeScribe')) {
    class FakeScribe
    {
        public function __construct($host, $port, $send, $recv)
        {

        }

        public function log(array $messages)
        {

        }

        public function close()
        {

        }
    }
}

class Logger_Appender_ScribeTest extends PHPUnit_Framework_TestCase
{
    public function testScribe()
    {
        $cfg = array(
            'logging.appender.root.capacity' => 2,
            'logging.appender.root.host' => 'localhost',
            'logging.appender.root.port' => 3,
            'logging.appender.root.sendtimeout' => 4,
            'logging.appender.root.recvtimeout' => 5,
        );

        $msg = new Logger_Message('name', 'message', Logger_Category::WARN, 'file', 'function', 42, array());

        $ap = new Logger_Appender_Scribe('root', $cfg, 'FakeScribe');
        $ap->log($msg);

        $ap->flush();
        $ap->close();
    }

    public function testCapacity()
    {
        $cfg = array(
            'logging.appender.root.capacity' => 2,
            'logging.appender.root.host' => 'localhost',
            'logging.appender.root.port' => 3,
            'logging.appender.root.sendtimeout' => 4,
            'logging.appender.root.recvtimeout' => 5,
        );

        $msg = new Logger_Message('name', 'message', Logger_Category::WARN, 'file', 'function', 42, array());

        $ap = $this->getMock('Logger_Appender_Scribe', array('flush'), array('root', $cfg, 'FakeScribe'));
        $ap->expects($this->once())
           ->method('flush');

        $ap->log($msg);
        $ap->log($msg);
    }

    public function testNullCapacity()
    {
        $cfg = array(
            'logging.appender.root.host' => 'localhost',
            'logging.appender.root.port' => 3,
            'logging.appender.root.sendtimeout' => 4,
            'logging.appender.root.recvtimeout' => 5,
        );

        $msg = new Logger_Message('name', 'message', Logger_Category::WARN, 'file', 'function', 42, array());

        $ap = $this->getMock('Logger_Appender_Scribe', array('flush'), array('root', $cfg, 'FakeScribe'));
        $ap->expects($this->never())
           ->method('flush');

        $ap->log($msg);
        $ap->log($msg);
    }
}
?>