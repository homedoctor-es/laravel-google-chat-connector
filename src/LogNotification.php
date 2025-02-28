<?php

namespace GoogleChatConnector;

use GoogleChatConnector\Enums\Icon;
use GoogleChatConnector\Widgets\DecoratedText;
use GoogleChatConnector\Widgets\Icons\KnownIcon;
use Illuminate\Notifications\Notification;
use Monolog\Logger;

class LogNotification extends Notification
{
    public function via($notifiable)
    {
        return [GoogleChatChannel::class];
    }

    public function toGoogleChat($record)
    {
        return $this->getRequestBody($record)->to($this->space);
    }

    /**
     * Get the request body content.
     *
     * @param array $record
     * @return GoogleChatMessage
     * @throws Exception
     */
    protected function getRequestBody(array $record): GoogleChatMessage
    {
        return GoogleChatMessage::create()
            ->text(substr($this->getNotifiableText($record['level'] ?? '') . $record['formatted'], 0, 4096))
            ->cardV2([
                CardV2::create('info-card-id', [
                    Section::create([
                        $this->cardWidget(ucwords(config('app.env') ?: 'NA') . ' [Env]', Icon::BOOKMARK),
                        $this->cardWidget($this->getLevelContent($record), Icon::TICKET),
                        $this->cardWidget($record['datetime'], Icon::CLOCK),
                        $this->cardWidget(request()->url(), Icon::BUS),
                        ...$this->getCustomLogs(),
                    ])->header('Details')
                        ->setCollapsible(true),
                ])->header(
                    "{$record['level_name']}: {$record['message']}",
                    config('app.name')
                ),
            ]);
    }

    protected function getNotifiableText($level): string
    {
        $levelBasedUserIds = [
            Logger::EMERGENCY => config('logging.channels.google-chat.notify_users.emergency'),
            Logger::ALERT => config('logging.channels.google-chat.notify_users.alert'),
            Logger::CRITICAL => config('logging.channels.google-chat.notify_users.critical'),
            Logger::ERROR => config('logging.channels.google-chat.notify_users.error'),
            Logger::WARNING => config('logging.channels.google-chat.notify_users.warning'),
            Logger::NOTICE => config('logging.channels.google-chat.notify_users.notice'),
            Logger::INFO => config('logging.channels.google-chat.notify_users.info'),
            Logger::DEBUG => config('logging.channels.google-chat.notify_users.debug'),
        ][$level] ?? '';

        $levelBasedUserIds = trim($levelBasedUserIds);
        if (($userIds = config('logging.channels.google-chat.notify_users.default')) && $levelBasedUserIds) {
            $levelBasedUserIds = ",$levelBasedUserIds";
        }

        return $this->constructNotifiableText(trim($userIds) . $levelBasedUserIds);
    }


    /**
     * Card widget content.
     *
     * @param string $text
     * @param string $icon
     * @return DecoratedText
     */
    public function cardWidget(string $text, string $icon): DecoratedText
    {
        return DecoratedText::create($text)->startIcon(KnownIcon::create($icon));
    }

    /**
     * Get the notifiable text for the given userIds String.
     *
     * @param $userIds
     * @return string
     */
    protected function constructNotifiableText($userIds): string
    {
        if (!$userIds) {
            return '';
        }

        $allUsers = '';
        $otherIds = implode(array_map(function ($userId) use (&$allUsers) {
            if (strtolower($userId) === 'all') {
                $allUsers = '<users/all> ';
                return '';
            }

            return "<users/$userId> ";
        }, array_unique(
                explode(',', $userIds))
        ));

        return $allUsers . $otherIds;
    }


    /**
     * Get the card content.
     *
     * @param array $record
     * @return string
     */
    protected function getLevelContent(array $record): string
    {
        $color = [
            Logger::EMERGENCY => '#ff1100',
            Logger::ALERT => '#ff1100',
            Logger::CRITICAL => '#ff1100',
            Logger::ERROR => '#ff1100',
            Logger::WARNING => '#ffc400',
            Logger::NOTICE => '#00aeff',
            Logger::INFO => '#48d62f',
            Logger::DEBUG => '#000000',
        ][$record['level']] ?? '#ff1100';

        return "<font color='{$color}'>{$record['level_name']}</font>";
    }

    /**
     * Get the custom logs.
     *
     * @return array
     * @throws Exception
     */
    public function getCustomLogs(): array
    {
        $additionalLogs = GoogleChatLogHandler::$additionalLogs;

        if (!$additionalLogs) {
            return [];
        }

        $additionalLogs = $additionalLogs(request());
        if (!is_array($additionalLogs)) {
            throw new Exception('Data returned from the additional Log must be an array.');
        }

        $logs = [];
        foreach ($additionalLogs as $key => $value) {
            if ($value && !is_string($value)) {
                try {
                    $value = json_encode($value);
                } catch (Throwable $throwable) {
                    throw new Exception("Additional log key-value should be a string for key[{$key}]. For logging objects, json or array, please stringify by doing json encode or serialize on the value.");
                }
            }

            if (!is_numeric($key)) {
                $key = ucwords(str_replace('_', ' ', $key));
                $value = "<b>{$key}:</b> $value";
            }
            $logs[] = $this->cardWidget($value, 'CONFIRMATION_NUMBER_ICON');
        }

        return $logs;
    }

    public function __construct(protected string $space)
    {
    }
}
