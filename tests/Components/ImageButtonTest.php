<?php

namespace GoogleChatConnector\Tests\Components;

use GoogleChatConnector\Components\Button\ImageButton;
use GoogleChatConnector\Enums\Icon;
use GoogleChatConnector\Tests\TestCase;

class ImageButtonTest extends TestCase
{
    public function test_it_can_create_button_with_icon_name()
    {
        $button = ImageButton::create('https://example.org', Icon::TRAIN);

        $this->assertEquals(
            [
                'imageButton' => [
                    'icon' => 'TRAIN',
                    'onClick' => [
                        'openLink' => [
                            'url' => 'https://example.org',
                        ],
                    ],
                ],
            ],
            $button->toArray()
        );
    }

    public function test_it_can_create_button_with_icon_url()
    {
        $button = ImageButton::create('https://example.org', 'https://example.org/icon.png');

        $this->assertEquals(
            [
                'imageButton' => [
                    'iconUrl' => 'https://example.org/icon.png',
                    'onClick' => [
                        'openLink' => [
                            'url' => 'https://example.org',
                        ],
                    ],
                ],
            ],
            $button->toArray()
        );
    }
}
