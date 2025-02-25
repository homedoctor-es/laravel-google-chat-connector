<?php

namespace GoogleChatConnector\Tests\Fixtures;

use Illuminate\Notifications\Notification;
use GoogleChatConnector\Card;
use GoogleChatConnector\Components\Button\ImageButton;
use GoogleChatConnector\Components\Button\TextButton;
use GoogleChatConnector\GoogleChatChannel;
use GoogleChatConnector\GoogleChatMessage;
use GoogleChatConnector\Section;
use GoogleChatConnector\Widgets\Buttons;
use GoogleChatConnector\Widgets\Image;
use GoogleChatConnector\Widgets\KeyValue;
use GoogleChatConnector\Widgets\TextParagraph;

class TestEndToEndNotification extends Notification
{
    public function via($notifiable)
    {
        return [GoogleChatChannel::class];
    }

    public function toGoogleChat($notifiable)
    {
        $message = GoogleChatMessage::create()
            ->text('This is a test end-to-end notification.')
            ->card([
                Card::create([
                    Section::create(
                        TextParagraph::create('Simple paragraph text')
                    ),
                    Section::create()
                        ->widget(
                            KeyValue::create(
                                'Top Label',
                                'Content',
                                'Bottom Label'
                            )
                            ->icon('TRAIN')
                            ->onClick('https://example.com/key-value-click')
                            ->setContentMultiline(true)
                            ->button(
                                ImageButton::create('https://example.com/key-value-button-click', 'https://picsum.photos/64/64')
                            )
                        )
                        ->widget(
                            Image::create('https://picsum.photos/300/150', 'https://example.com/img-clickthrough')
                        ),
                ])->header(
                    'First Card',
                    'First Card - Subtitle',
                    'https://picsum.photos/65/65',
                    'IMAGE'
                ),
                Card::create(
                    Section::create(
                        Buttons::create(
                            TextButton::create('https://example.com/card-2-cta', 'Go There')
                        )
                    )
                )->header(
                    'Second Card',
                    'Second Card - Subtitle',
                    'https://picsum.photos/66/66',
                    'AVATAR'
                ),
            ]);

        return $message;
    }
}
