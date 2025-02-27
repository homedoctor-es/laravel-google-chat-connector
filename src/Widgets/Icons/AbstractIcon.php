<?php

namespace GoogleChatConnector\Widgets\Icons;

use GoogleChatConnector\Widgets\AbstractWidget;

abstract class AbstractIcon extends AbstractWidget
{
    protected $payload = null;

    /**
     * Append icon content to the widget.
     *
     * @param string $value
     * @return self
     */
    public function value(string $value): AbstractIcon
    {
        $this->payload = $value;

        return $this;
    }

    /**
     * Return a new Decorated Text widget instance.
     *
     * @param string $value
     * @return self
     */
    public static function create(string $value): AbstractIcon
    {
        $widget = new static;

        if ($value) {
            $widget->value($value);
        }

        return $widget;
    }
}
