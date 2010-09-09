<?php
/**
 * Logger_ILayout
 *
 * @package Logger
 * @author Matt Erkkila <matt@matterkkila.com>
 */

require_once $GLOBALS['LOGGER_ROOT'] . 'Logger/Message.php';

/**
 * Logger_ILayout
 *
 * Interface for all layouts to implement
 *
 * @package Logger
 * @author Matt Erkkila <matt@matterkkila.com>
 */
interface Logger_ILayout
{
    /**
     * Format a given message based on the layout
     *
     * @param Logger_Message $message The message to log
     *
     * @return string
     */
    public function format(Logger_Message $message);
}

?>