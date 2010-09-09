<?php

require_once 'PHPUnit/Framework.php';

require_once $GLOBALS['LOGGER_ROOT'] . 'Logger.php';
require_once $GLOBALS['LOGGER_ROOT'] . 'Logger/Layout/Pattern.php';
require_once $GLOBALS['LOGGER_ROOT'] . 'Logger/Message.php';

class Logger_Layout_PatternTest extends PHPUnit_Framework_TestCase
{
    public function testPattern()
    {
        $class = 'some.class';
        $text  = 'some message text';
        $pri   = Logger_Category::CRIT;

        $config = array(
            'logging.appender.A1.layout.pattern' => '%(date) %(time).%(ms): %(level) - %(name) - %(message)%(new)'
        );

        $layout = new Logger_Layout_Pattern('A1', $config);
        $msg    = new Logger_Message($class, $text, $pri, null, null, null, array());
        $text   = $layout->format($msg);

        $pattern_regex = '/^\d{2}\/\d{2}\/\d{4}\s\d{2}:\d{2}:\d{2}\.\d{2,10}:\sCRIT\s\-\ssome\.class\s-\ssome\smessage\stext\n$/';
        $matches       = preg_match($pattern_regex, $text);

        $this->assertSame(1, $matches, 'Output: ' . $text);

        $layout->setPattern('%(level) - %(name) - %(message)%(new)');
        $text          = $layout->format($msg);
        $pattern_regex = '/^CRIT\s\-\ssome\.class\s-\ssome\smessage\stext\n$/';
        $matches       = preg_match($pattern_regex, $text);

        $this->assertSame(1, $matches, 'Output: ' . $text);
    }

    public function testArrayMessage()
    {
        $class = 'some.class';
        $text  = array('id' => 'id2', 'name' => 'kevin rose');
        $pri   = Logger_Category::CRIT;

        $msg = new Logger_Message($class, $text, $pri, null, null, null, $text);

        $layout = new Logger_Layout_Pattern('A1', array());
        $layout->setPattern('%(id) - %(name)');

        $text = $layout->format($msg);

        $this->assertSame('id2 - some.class', $text);
    }

    public function testDefaultPattern()
    {
        $class = 'some.class';
        $text  = 'some message text';
        $pri   = Logger_Category::CRIT;

        $msg = new Logger_Message($class, $text, $pri, null, null, null, array());

        $layout = new Logger_Layout_Pattern('A1', array());
        $text   = $layout->format($msg);

        $pattern_regex = '/^\d{2}\/\d{2}\/\d{4}\s\d{2}:\d{2}:\d{2}\.\d{2,10}:\sCRIT\s\-\ssome\.class\s-\ssome\smessage\stext\n$/';

        $matches = preg_match($pattern_regex, $text);

        $this->assertSame(1, $matches, 'Output: ' . $text);
    }
}
?>