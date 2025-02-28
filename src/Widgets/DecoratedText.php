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

    /**
     * @param AbstractIcon $icon
     * @return $this
     */
    public function startIcon(AbstractIcon $icon): DecoratedText
    {
        $this->payload['startIcon'] = $icon;

        return $this;
    }

    /**
     * @param bool $value
     * @return $this
     */
    public function wrapText(bool $value): DecoratedText
    {
        $this->payload['wrapText'] = $value;
        return $this;
    }

    /**
     * Return a new Decorated Text widget instance.
     *
     * @param string $text
     * @param bool $wrapText
     * @return self
     */
    public static function create(string $text, bool $wrapText = true): DecoratedText
    {
        $widget = new static;

        if ($text) {
            $widget->text($text);
        }

        $widget->wrapText($wrapText);

        return $widget;
    }
}
