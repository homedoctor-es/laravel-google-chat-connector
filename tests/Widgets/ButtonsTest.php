<?php

namespace GoogleChatConnector\Tests\Widgets;

use GoogleChatConnector\Components\Button\ImageButton;
use GoogleChatConnector\Components\Button\TextButton;
use GoogleChatConnector\Enums\Icon;
use GoogleChatConnector\Tests\TestCase;
use GoogleChatConnector\Widgets\Buttons;

class ButtonsTest extends TestCase
{
    public function test_it_can_create_with_buttons()
    {
        $widget = Buttons::create([
            $textButton = TextButton::create('https://example.org', 'Example'),
            $imageButton = ImageButton::create('https://example.com', Icon::TRAIN),
        ]);

        $this->assertEquals(
            [
                'buttons' => [
                    $textButton,
                    $imageButton,
                ],
            ],
            $widget->toArray()
        );
    }
}
