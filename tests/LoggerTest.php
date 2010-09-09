<?php

require_once 'PHPUnit/Framework.php';
require_once $GLOBALS['LOGGER_ROOT'] . 'Logger.php';
require_once $GLOBALS['LOGGER_ROOT'] . 'Logger/Builder/Simple.php';
require_once $GLOBALS['LOGGER_ROOT'] . 'Logger/Exception.php';

class LoggerTest extends PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        Logger::setBuilder(new Logger_Builder_Simple);
        $logger = Logger::getLogger('root');
        $logger2 = Logger::getLogger('app.media');

        Logger::shutdown();

        $this->assertType('Logger_Category', $logger);
        $this->assertSame('root', $logger->getName());
        $this->assertType('Logger_Category', $logger2);
        $this->assertSame('app.media', $logger2->getName());
    }

    /**
     * @expectedException Logger_Exception
     */
    public function testNoBuilder()
    {
        Logger::setBuilder(null);
        Logger::setLoggers(array());
        Logger::getLogger('root');
    }

}
?>