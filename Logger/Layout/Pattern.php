<?php
/**
 * Logger_Layout_Pattern
 *
 * @package Logger
 * @author Matt Erkkila <matt@matterkkila.com>
 */

require_once $GLOBALS['LOGGER_ROOT'] . 'Logger/ILayout.php';
require_once $GLOBALS['LOGGER_ROOT'] . 'Logger/Layout.php';
require_once $GLOBALS['LOGGER_ROOT'] . 'Logger/Message.php';

/**
 * Logger_Layout_Pattern
 *
 * A pattern layout.
 *
 * @package Logger
 * @author Matt Erkkila <matt@matterkkila.com>
 */
class Logger_Layout_Pattern extends Logger_Layout implements Logger_ILayout
{
    /**
     * The pattern
     *
     * @var string
     */
    protected $pattern = null;

    /**
     * Set the pattern for this layout
     *
     * @param string $pattern The pattern
     *
     * @return void
     */
    public function setPattern($pattern)
    {
        $this->pattern = $pattern;
    }

    /**
     * Get the pattern for this layout
     *
     * @return string
     */
    public function getPattern()
    {
        if (($this->pattern === null) && isset($this->cfg['logging.appender.' . $this->name . '.layout.pattern'])) {
            $this->pattern = $this->cfg['logging.appender.' . $this->name . '.layout.pattern'];
        } elseif ($this->pattern === null) {
            $this->pattern = '%(date) %(time).%(ms): %(level) - %(name) - %(message)%(new)';
        }

        return $this->pattern;
    }

    /**
     * Get the find/replace array
     *
     * @param Logger_Message $message The message object
     *
     * @return array
     */
    public function getModifiers($message)
    {
        $msgTerms = array();
        if (is_array($message->args)) {
            foreach ($message->args as $key => $value) {
                $msgTerms['%(' . $key . ')'] = $value;
            }
        }

        //This will force turning Exceptions to strings by calling it's __toString() method.
        //Any objects which support __toString() would also work.
        $msg = sprintf('%s', $message->message);

        $terms = array(
            '%(date)'     => date('m/d/Y'),
            '%(time)'     => date('H:i:s', $message->time),
            '%(ms)'       => $message->milliseconds,
            '%(level)'    => Logger_Category::$levelToText[$message->level],
            '%(name)'     => $message->name,
            '%(message)'  => $msg,
            '%(file)'     => $message->file,
            '%(function)' => $message->function,
            '%(lineno)'   => $message->lineno,
            '%(pid)'      => getmypid(),
            '%(new)'      => "\n",
        );

        $terms = array_merge($msgTerms, $terms);

        return $terms;
    }

    /**
     * Format a given message based on the layout
     *
     * @param Logger_Message $message The message to log
     *
     * @return string
     */
    public function format(Logger_Message $message)
    {
        $search = $this->getModifiers($message);
        return str_replace(array_keys($search), $search, $this->getPattern());
    }
}

?>