<?php

namespace GoogleChatConnector\Tests\Widgets;

use GoogleChatConnector\Components\Button\TextButton;
use GoogleChatConnector\Enums\Icon;
use GoogleChatConnector\Tests\TestCase;
use GoogleChatConnector\Widgets\KeyValue;

class KeyValueTest extends TestCase
{
    public function test_it_can_create_with_simple_content()
    {
        $widget = KeyValue::create('Top', 'Content', 'Bottom');

        $this->assertEquals(
            [
                'keyValue' => [
                    'topLabel' => 'Top',
                    'content' => 'Content',
                    'bottomLabel' => 'Bottom',
                ],
            ],
            $widget->toArray()
        );
    }

    public function test_it_can_set_content_multiline()
    {
        $widget = KeyValue::create()->setContentMultiline(true);

        $this->assertEquals(
            [
                'keyValue' => [
                    'contentMultiline' => true,
                ],
            ],
            $widget->toArray()
        );
    }

    public function test_it_can_set_on_click_link()
    {
        $widget = KeyValue::create()->onClick('https://example.org');

        $this->assertEquals(
            [
                'keyValue' => [
                    'onClick' => [
                        'openLink' => [
                            'url' => 'https://example.org',
                        ],
                    ],
                ],
            ],
            $widget->toArray()
        );
    }

    public function test_it_can_set_icon()
    {
        $widget = KeyValue::create()->icon(Icon::TRAIN);

        $this->assertEquals(
            [
                'keyValue' => [
                    'icon' => 'TRAIN',
                ],
            ],
            $widget->toArray()
        );
    }

    public function test_it_can_add_button()
    {
        $widget = KeyValue::create()->button(
            $button = TextButton::create('https://example.org', 'Example')
        );

        $this->assertEquals(
            [
                'keyValue' => [
                    'button' => $button,
                ],
            ],
            $widget->toArray()
        );
    }
}
