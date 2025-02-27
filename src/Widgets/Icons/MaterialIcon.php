<?php

namespace GoogleChatConnector\Widgets\Icons;

/**
 * Class MaterialIcon.
 *
 * Visit https://fonts.google.com/icons for complete icons list available
 *
 * @author Miguel Graciá <miguel.gracia@homedoctor.es>
 */
class MaterialIcon extends AbstractIcon
{
    protected $payload = [
        'name' => null,
    ];

    /**
     * @param string $value
     * @return AbstractIcon
     */
    public function value(string $value): AbstractIcon
    {
        $this->payload['name'] = $value;

        return $this;
    }
}
