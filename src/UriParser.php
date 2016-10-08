<?php

declare(strict_types = 1);

namespace App;

use App\Components\AbstractComponent;

/**
 * Class UriParser
 * @package App
 */
class UriParser implements UriParserInterface
{
//    protected $fragmentRegex = '(?P<fragment>(?<=#).*)';
//
//    protected $authorityRegex = '(?P<authority>(?<=//)[^/\?#]*)';
//    protected $userRegex = '(?P<user>[^:]*)';
//    protected $passRegex = '(?P<pass>(?<=:)[^@]*)';
//    protected $portRegex = '(?P<port>(?<=:)[0-9]+)';

    private $components = [];

    /**
     * @param string $url
     * @return array
     */
    public function parse(string $url): array
    {
        $result = [];

        /** @var AbstractComponent $component */
        foreach ($this->components as $component) {
            $value = $component->extract($url);
            if ($value) {
                $result[$component->getName()] = $value;
            }
        }

        return $result;
    }

    public function addComponent(AbstractComponent $component)
    {
        $this->components[] = $component;
    }
}
