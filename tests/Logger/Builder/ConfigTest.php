<?php

require_once 'PHPUnit/Framework.php';

require_once $GLOBALS['LOGGER_ROOT'] . 'Logger/Builder/Config.php';

class Logger_Builder_ConfigTest extends PHPUnit_Framework_TestCase
{
    protected function validateLoggers($loggers)
    {
        $this->assertArrayHasKey('root', $loggers);
        $this->assertArrayHasKey('app', $loggers);
        $this->assertArrayHasKey('app.media', $loggers);
        $this->assertArrayHasKey('app.index', $loggers);

        foreach ($loggers as $name => $logger) {
            switch ($name) {
                case 'root':
                    $this->assertSame('root', $logger->getName());
                    $this->assertNull($logger->getParent());
                    $appenders = $logger->getAppenders();
                    $this->assertArrayHasKey('A1', $appenders);
                    $this->assertType('Logger_Appender_ErrorLog', $appenders['A1']);
                    $this->assertSame('/tmp', $appenders['A1']->getDir());
                    $this->assertType('Logger_Layout_Pattern', $appenders['A1']->getLayout());
                    break;
                case 'app':
                    $this->assertSame('app', $logger->getName());
                    $this->assertSame($loggers['root'], $logger->getParent());
                    $appenders = $logger->getAppenders();
                    $this->assertArrayHasKey('A1', $appenders);
                    $this->assertType('Logger_Appender_ErrorLog', $appenders['A1']);
                    $this->assertSame('/tmp', $appenders['A1']->getDir());
                    $this->assertType('Logger_Layout_Pattern', $appenders['A1']->getLayout());
                    break;
                case 'app.media':
                    $this->assertSame('app.media', $logger->getName());
                    $this->assertSame($loggers['app'], $logger->getParent());
                    $appenders = $logger->getAppenders();
                    $this->assertArrayHasKey('A1', $appenders);
                    $this->assertType('Logger_Appender_ErrorLog', $appenders['A1']);
                    $this->assertSame('/tmp', $appenders['A1']->getDir());
                    $this->assertType('Logger_Layout_Pattern', $appenders['A1']->getLayout());
                    $this->assertSame(false, $logger->getPropagate());
                    break;
                case 'app.index':
                    $this->assertSame('app.index', $logger->getName());
                    $this->assertSame($loggers['app'], $logger->getParent());
                    $appenders = $logger->getAppenders();
                    $this->assertArrayHasKey('A1', $appenders);
                    $this->assertType('Logger_Appender_ErrorLog', $appenders['A1']);
                    $this->assertSame('/tmp', $appenders['A1']->getDir());
                    $this->assertType('Logger_Layout_Pattern', $appenders['A1']->getLayout());
                    break;
            }
        }
    }

    public function testBuild()
    {
        $config = array(
            'logging.rootLogger'          => 'DEBUG, A1',
            'logging.appender.A1'         => 'Logger_Appender_ErrorLog',
            'logging.appender.A1.logdir'  => '/tmp',
            'logging.appender.A1.layout'  => 'Logger_Layout_Pattern',
            'logging.appender.A2'         => 'Logger_Appender_Memory',
            'logging.appender.A2.layout'  => 'Logger_Layout_Pattern',
            'logging.logger.app'          => 'WARN, A1',
            'logging.logger.app.media'    => 'INFO, A1',
            'logging.logger.app.index'    => 'DEBUG, A1',
            'logging.app.media.propagate' => false,
        );

        $builder = new Logger_Builder_Config($config);
        $loggers = array();
        $builder->build($loggers);
        $builder->shutdown();
        $this->validateLoggers($loggers);
    }

    public function testNoParent()
    {
        $config = array(
            'logging.rootLogger'          => 'DEBUG, A1',
            'logging.appender.A1'         => 'Logger_Appender_ErrorLog',
            'logging.appender.A1.logdir'  => '/tmp',
            'logging.appender.A1.layout'  => 'Logger_Layout_Pattern',
            'logging.appender.A2'         => 'Logger_Appender_Memory',
            'logging.appender.A2.layout'  => 'Logger_Layout_Pattern',
            'logging.logger.app'          => 'WARN, A1',
            'logging.logger.app.media'    => 'INFO, A1',
            'logging.logger.app.index'    => 'DEBUG, A1',
            'logging.app.media.propagate' => false,
        );

        $builder = new Logger_Builder_Config($config);
        $loggers = array();
        $builder->build($loggers);

        $this->assertSame('root', $builder->findParentLogger('config.exception', $loggers));

        $this->validateLoggers($loggers);
    }

    /**
     * @expectedException Logger_Exception
     */
    public function testInvalidAppender()
    {
        $config = array(
            'logging.rootLogger'         => 'DEBUG, A5',
            'logging.appender.A2'        => 'Logger_Appender_Memory',
            'logging.appender.A2.layout' => 'Logger_Layout_Pattern',
        );

        $builder = new Logger_Builder_Config($config);
        $loggers = array();
        $builder->build($loggers);
    }

    /**
     * @expectedException Logger_Exception
     */
    public function testInvalidAppenderNoClass()
    {
        $config = array(
            'logging.rootLogger'         => 'DEBUG, A5',
            'logging.appender.A2'        => 'Logger_Appender_Memory',
            'logging.appender.A5'        => '',
            'logging.appender.A2.layout' => 'Logger_Layout_Pattern',
        );

        $builder = new Logger_Builder_Config($config);
        $loggers = array();
        $builder->build($loggers);
    }
}
?>