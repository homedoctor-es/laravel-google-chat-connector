<?php

namespace GoogleChatConnector;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Notifications\Notification;
use GoogleChatConnector\Exceptions\CouldNotSendNotification;
use Illuminate\Support\Facades\Log;

class GoogleChatChannel
{
    /**
     * The Http Client.
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * Initialise a new Google Chat Channel instance.
     *
     * @param \GuzzleHttp\Client $client
     * @return void
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     *
     * @throws \GoogleChatConnector\Exceptions\CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        if (! method_exists($notification, 'toGoogleChat')) {
            throw CouldNotSendNotification::undefinedMethod($notification);
        }

        /** @var \GoogleChatConnector\GoogleChatMessage $message */
        if (! ($message = $notification->toGoogleChat($notifiable)) instanceof GoogleChatMessage) {
            throw CouldNotSendNotification::invalidMessage($message);
        }

        $space = $message->getSpace() ?? $notifiable->routeNotificationFor('google-chat');

        if (! $endpoint = config("google-chat.spaces.$space", $space)) {
            return $this;
        }

        try {
            $this->client->request(
                'post',
                $endpoint,
                [
                    'json' => $message->toArray(),
                ]
            );
        } catch (ClientException|Exception $exception) {
            Log::channel('daily')->error($exception);
        }

        return $this;
    }
}
