<?php

namespace GoogleChatConnector;

use Exception;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use Monolog\LogRecord;

class GoogleChatLogHandler extends AbstractProcessingHandler
{
    /**
     * Additional logs closure.
     *
     * @var \Closure|null
     */
    public static \Closure|null $additionalLogs = null;

    /**
     * Writes the record down to the log of the implementing handler.
     *
     * @param LogRecord $record
     *
     * @throws \Exception
     */
    protected function write(LogRecord $record): void
    {
        self::$additionalLogs = function () use ($record) {
            return $record['context'] ?? null;
        };

        /** @var GoogleChatChannel $googleChannel */
        $googleChannel = app(GoogleChatChannel::class);

        foreach ($this->getSpaces() as $space) {
            $googleChannel->send($record, new LogNotification($space));
        }
    }

    /**
     * Get the webhook url.
     *
     * @return array
     *
     * @throws \Exception
     */
    protected function getSpaces(): array
    {
        $space = config('google-chat.spaces.' . $this->space);

        if (!$space) {
            $space = config('google-chat.space');
        }

        if (!$space) {
            throw new Exception('Google chat space is not configured.');
        }

        $this->space = $space;

        if (is_array($space)) {
            return $space;
        }

        return array_map(function ($each) {
            return trim($each);
        }, explode(',', $space));
    }

    protected string|array|null $space;

    public function __construct($level = Logger::DEBUG, bool $bubble = true, $space = null, $additionalLogs = null)
    {
        parent::__construct($level, $bubble);

        $this->space = $space;
        self::$additionalLogs = $additionalLogs;
    }
}
