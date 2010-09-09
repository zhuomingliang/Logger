<?php
/**
 * Logger_Appender
 *
 * @package Logger
 * @author Matt Erkkila <matt@matterkkila.com>
 */

/**
 * Logger_Appender
 *
 * The base appender
 *
 * @package Logger
 * @author Matt Erkkila <matt@matterkkila.com>
 */
abstract class Logger_Appender
{
    /**
     * The name of the appender
     *
     * @var string
     */
    protected $name = null;

    /**
     * The config for the appender
     *
     * @var array
     */
    protected $cfg = null;

    /**
     * The layout
     *
     * @var Logger_ILayout
     */
    protected $layout = null;

    /**
     * Constructor
     *
     * @param string $name The name of the appender
     * @param array  $cfg  The config for the appender
     *
     * @return void
     */
    public function __construct($name, array $cfg)
    {
        $this->name = $name;
        $this->cfg  = $cfg;
    }

    /**
     * Get the name of the appender
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the layout for this appender, build if it we haven't already
     *
     * @return Logger_ILayout
     */
    public function getLayout()
    {
        if ($this->layout === null) {
            if (isset($this->cfg['logging.appender.' . $this->name . '.layout'])) {
                $layoutClass = $this->cfg['logging.appender.' . $this->name . '.layout'];
            } else {
                $layoutClass = 'Logger_Layout_Pattern';
            }

            loadClass($layoutClass);
            $this->layout = new $layoutClass($this->name, $this->cfg);
        }

        return $this->layout;
    }

    /**
     * Flush an content from buffer
     *
     * @return void
     */
    public function flush()
    {
    }

    /**
     * Do any cleanup work here
     *
     * @return void
     */
    public function close()
    {
    }
}

?>