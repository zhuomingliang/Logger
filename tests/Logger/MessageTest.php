<?php

require_once 'PHPUnit/Framework.php';

require_once $GLOBALS['LOGGER_ROOT'] . 'Logger/Message.php';

class Logger_MessageTest extends PHPUnit_Framework_TestCase
{
    public function testMessage()
    {
        $class    = 'some.class';
        $text     = 'some message text';
        $file     = null;
        $function = null;
        $lineno   = null;
        $args     = array();
        $pri      = Logger_Category::CRIT;
        $time     = time();

        $msg = new Logger_Message($class, $text, $pri, $file, $function, $lineno, $args);

        $this->assertSame($class, $msg->name);
        $this->assertSame($text, $msg->message);
        $this->assertSame($pri, $msg->level);
        $this->assertGreaterThan(0, $msg->time);
        $this->assertGreaterThanOrEqual(0, $msg->milliseconds);

        $msg = new Logger_Message($class, $text, $pri, $file, $function, $lineno, $args);

        $this->assertSame($class, $msg->name);
        $this->assertSame($text, $msg->message);
        $this->assertSame($pri, $msg->level);
        $this->assertType('integer', $msg->time);

    }
}
?>