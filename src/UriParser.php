<?php

declare(strict_types = 1);

namespace App;

use App\Components\Components;
use App\Extractors\FragmentExtractor;
use App\Extractors\HostExtractor;
use App\Extractors\PassExtractor;
use App\Extractors\PathExtractor;
use App\Extractors\PortExtractor;
use App\Extractors\QueryExtractor;
use App\Extractors\SchemeExtractor;
use App\Extractors\UserExtractor;

class UriParser implements UriParserInterface
{
    /**
     * @var FragmentExtractor
     */
    private $fragmentExtractor;

    /**
     * @var QueryExtractor
     */
    private $queryExtractor;

    /**
     * @var SchemeExtractor
     */
    private $schemeExtractor;

    /**
     * @var PathExtractor
     */
    private $pathExtractor;

    /**
     * @var PortExtractor
     */
    private $portExtractor;

    /**
     * @var UserExtractor
     */
    private $userExtractor;

    /**
     * @var PassExtractor
     */
    private $passExtractor;

    /**
     * @var HostExtractor
     */
    private $hostExtractor;

    public function __construct()
    {
        $this->fragmentExtractor = new FragmentExtractor();
        $this->queryExtractor = new QueryExtractor();
        $this->schemeExtractor = new SchemeExtractor();
        $this->pathExtractor = new PathExtractor();
        $this->portExtractor = new PortExtractor();
        $this->userExtractor = new UserExtractor();
        $this->passExtractor = new PassExtractor();
        $this->hostExtractor = new HostExtractor();
    }

    /**
     * @param string $url
     *
     * @return array
     */
    public function parse(string $url): array
    {
        $this->fragmentExtractor->setSuccessor($this->queryExtractor);
        $this->fragmentExtractor->setSuccessor($this->schemeExtractor);
        $this->fragmentExtractor->setSuccessor($this->pathExtractor);
        $this->fragmentExtractor->setSuccessor($this->portExtractor);
        $this->fragmentExtractor->setSuccessor($this->userExtractor);
        $this->fragmentExtractor->setSuccessor($this->passExtractor);
        $this->fragmentExtractor->setSuccessor($this->hostExtractor);

        $components = new Components();
        $this->fragmentExtractor->process($url, $components);

        return $components->getComponents();
    }
}
