<?php

namespace GoogleChatConnector\Tests\Components;

use GoogleChatConnector\Components\Button\TextButton;
use GoogleChatConnector\Tests\TestCase;

class TextButtonTest extends TestCase
{
    public function test_it_can_create_button()
    {
        $button = TextButton::create('https://example.org', 'OPEN');

        $this->assertEquals(
            [
                'textButton' => [
                    'text' => 'OPEN',
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
