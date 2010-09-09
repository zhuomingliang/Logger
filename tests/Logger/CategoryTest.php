<?php

require_once 'PHPUnit/Framework.php';
require_once $GLOBALS['LOGGER_ROOT'] . 'Logger/Category.php';
require_once $GLOBALS['LOGGER_ROOT'] . 'Logger/Appender/Memory.php';

class Logger_CategoryTest extends PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $ap = new Logger_Appender_Memory('A1', array());
        $lc = new Logger_Category('root', Logger_Category::TRACE);
        $lc->addAppender($ap);

        $lc->crit('crit this!');
        $lc->error('error this!');
        $lc->warn('warn this!');
        $lc->info('info this!');
        $lc->debug('debug this!');
        $lc->trace('trace this!');

        $this->assertSame(array('A1' => $ap), $lc->getAppenders());

        $msgs = $ap->getMessages();
        $this->assertSame(6, count($msgs));

        $this->assertSame('crit this!', $msgs[0]->message);
        $this->assertSame('error this!', $msgs[1]->message);
        $this->assertSame('warn this!', $msgs[2]->message);

        $this->assertSame(__FILE__, $msgs[0]->file);
        $this->assertSame(__FILE__, $msgs[1]->file);

        $this->assertSame('Logger_CategoryTest::testConstructor', $msgs[0]->function);
        $this->assertSame('Logger_CategoryTest::testConstructor', $msgs[1]->function);

        $this->assertSame(Logger_Category::CRIT, $msgs[0]->level);
        $this->assertSame(Logger_Category::ERROR, $msgs[1]->level);
        $this->assertSame(Logger_Category::WARN, $msgs[2]->level);

        $this->assertSame('root', $msgs[0]->name);
        $this->assertSame('root', $msgs[1]->name);
        $this->assertSame('root', $msgs[2]->name);

        $this->assertNull($lc->getParent());
        $lc2 = clone $lc;
        $lc->setParent($lc2);
        $this->assertSame($lc2, $lc->getParent());

        $lc->crit('crit parent');

        $ap2 = reset($lc2->getAppenders());
        $this->assertSame(8, count($ap2->getMessages()));
    }

    public function testEffectiveLevel()
    {
        $ap = new Logger_Appender_Memory('A1', array());
        $lc = new Logger_Category('root', null);
        $lc->addAppender($ap);

        $lc2 = new Logger_Category('test', null);
        $lc2->warn('notset');

        $this->assertSame(0, count($ap->getMessages()));
    }

    public function testPropagateFalse()
    {
        $ap = new Logger_Appender_Memory('A1', array());
        $lc = new Logger_Category('root');
        $lc->addAppender($ap);

        $lc2 = new Logger_Category('child');
        $lc2->addAppender($ap);
        $lc2->setParent($lc);

        $lc2->crit('crit this!');

        $this->assertSame(true, $lc2->getPropagate());
        $this->assertSame(2, count($ap->getMessages()));

        $lc2->setPropagate(false);
        $lc2->crit('do not propagate');

        $this->assertSame(false, $lc2->getPropagate());
        $this->assertSame(3, count($ap->getMessages()));
    }
}
?>