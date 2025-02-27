<?php

namespace GoogleChatConnector\Tests\Fixtures;

use GoogleChatConnector\CardV2;
use GoogleChatConnector\GoogleChatChannel;
use GoogleChatConnector\GoogleChatMessage;
use GoogleChatConnector\Section;
use GoogleChatConnector\Widgets\DecoratedText;
use GoogleChatConnector\Widgets\Icons\IconUrl;
use GoogleChatConnector\Widgets\Icons\KnownIcon;
use GoogleChatConnector\Widgets\Icons\MaterialIcon;
use Illuminate\Notifications\Notification;

class TestEndToEndNotificationV2 extends Notification
{
    public function via($notifiable)
    {
        return [GoogleChatChannel::class];
    }

    public function toGoogleChat($notifiable)
    {
        return GoogleChatMessage::create()
            ->text('This is a test end-to-end notification.')
            ->cardV2([
                CardV2::create('info-card-id',[
                    Section::create([
                        DecoratedText::create('Decorated Text')->startIcon(
                            KnownIcon::create(KnownIcon::AIRPLANE)
                        ),
                        DecoratedText::create('Decorated Text')->startIcon(
                            IconUrl::create('https://developers.google.com/workspace/chat/images/quickstart-app-avatar.png')
                        ),
                        DecoratedText::create('Decorated Text')->startIcon(
                            MaterialIcon::create('sentiment_frustrated')
                        ),
                    ])->header('Details')
                        ->setCollapsible(true),
                ])->header('title header', 'subtitle header'),
            ])
        ;
    }
}
