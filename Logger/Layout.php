<?php
/**
 * Logger_Layout
 *
 * @package Logger
 * @author Matt Erkkila <matt@matterkkila.com>
 */

/**
 * Logger_Layout
 *
 * Base layout class for Logger
 *
 * @package Logger
 * @author Matt Erkkila <matt@matterkkila.com>
 */
abstract class Logger_Layout
{
    /**
     * The name of the appender this layout is attached to
     *
     * @var string
     */
    protected $name = null;

    /**
     * Config
     *
     * @var array
     */
    protected $cfg = null;

    /**
     * Constructor
     *
     * @param string $name The name of the layout appender
     * @param array  $cfg  The logging config
     *
     * @return vaoid
     */
    public function __construct($name, array $cfg)
    {
        $this->name = $name;
        $this->cfg  = $cfg;
    }
}
?>