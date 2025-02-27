<?php

namespace GoogleChatConnector\Widgets;

use GoogleChatConnector\Widgets\Icons\AbstractIcon;

/**
 * Class DecoratedText
 *
 * @author Miguel Graciá <miguel.gracia@homedoctor.es>
 */
class DecoratedText extends AbstractWidget
{
    /**
     * Append text content to the widget.
     *
     * @param string $text
     * @return self
     */
    public function text(string $text): DecoratedText
    {
        $this->payload['text'] = ($this->payload['text'] ?? '').$text;

        return $this;
    }

    public function startIcon(AbstractIcon $icon): DecoratedText
    {
        $this->payload['startIcon'] = $icon;

        return $this;
    }

    /**
     * Return a new Decorated Text widget instance.
     *
     * @param string $text
     * @return self
     */
    public static function create(string $text): DecoratedText
    {
        $widget = new static;

        if ($text) {
            $widget->text($text);
        }

        return $widget;
    }
}
