<?php

namespace GoogleChatConnector;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;
use GoogleChatConnector\Concerns\ValidatesCardComponents;

class CardV2 implements Arrayable
{
    use ValidatesCardComponents;

    /**
     * The card payload.
     *
     * @var array
     */
    protected $payload = [
        'cardId' => null,
        'card' => [
            'sections' => [],
        ]
    ];

    public function setCardId($cardId)
    {
        $this->payload['cardId'] = $cardId;

        return $this;
    }

    /**
     * Configure the header content of the card.
     *
     * @param string $title The title of the card, usually the bot or service name
     * @param string|null $subtitle Secondary text displayed below the title
     * @param string|null $imageUrl Display a particular avatar image for the message
     * @param string|null $imageStyle Configure the avatar image style, one of IMAGE or AVATAR
     * @return self
     */
    public function header(string $title, string $subtitle = null, string $imageUrl = null, string $imageStyle = null): CardV2
    {
        $header = [
            'title' => $title,
        ];

        if ($subtitle) {
            $header['subtitle'] = $subtitle;
        }

        if ($imageUrl) {
            $header['imageUrl'] = $imageUrl;
        }

        if ($imageStyle) {
            $header['imageStyle'] = $imageStyle;
        }

        $this->payload['card']['header'] = $header;

        return $this;
    }

    /**
     * Add one or more sections to the card.
     *
     * @param \GoogleChatConnector\Section|\GoogleChatConnector\Section[]
     * @return self
     */
    public function section($section): CardV2
    {
        $sections = Arr::wrap($section);

        $this->guardOnlyInstancesOf(Section::class, $sections);

        $this->payload['card']['sections'] = array_merge($this->payload['card']['sections'] ?? [], $sections);

        return $this;
    }

    /**
     * Serialize the card to an array representation.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->payload;
    }

    /**
     * Return a new Google Chat Card instance.
     *
     * @param \GoogleChatConnector\Section|\GoogleChatConnector\Section[]|null $section
     * @return self
     */
    public static function create($cardId, $section = null): CardV2
    {
        $card = new static;

        $card->setCardId($cardId);

        if ($section) {
            $card->section($section);
        }

        return $card;
    }
}
