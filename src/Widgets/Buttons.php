<?php

namespace GoogleChatConnector\Widgets;

use Illuminate\Support\Arr;
use GoogleChatConnector\Components\Button\AbstractButton;
use GoogleChatConnector\Concerns\ValidatesCardComponents;

class Buttons extends AbstractWidget
{
    use ValidatesCardComponents;

    /**
     * Add one or more buttons.
     *
     * @param \GoogleChatConnector\Components\Button\AbstractButton|\GoogleChatConnector\Components\Button\AbstractButton[] $button
     * @return self
     */
    public function button($button): Buttons
    {
        $buttons = Arr::wrap($button);

        $this->guardOnlyInstancesOf(AbstractButton::class, $buttons);

        $this->payload = array_merge($this->payload, $buttons);

        return $this;
    }

    /**
     * Return a new Buttons widget instance.
     *
     * @param \GoogleChatConnector\Components\Button\AbstractButton|\GoogleChatConnector\Components\Button\AbstractButton[]|null $buttons
     * @return self
     */
    public static function create($buttons = null): Buttons
    {
        $widget = new static;

        if ($buttons) {
            $widget->button($buttons);
        }

        return $widget;
    }
}
