<?php

declare(strict_types = 1);

namespace App;

use InvalidArgumentException;
use Psr\Http\Message\UriInterface;

/**
 * Class Uri
 * @package App
 */
class Uri implements UriInterface
{
    /**
     * TODO add more ports
     */
    const DEFAULT_PORTS = [
        'http' => 80,
        'https' => 443,
        'ftp' => 21,
        'ftps' => 990
    ];

    const HTTP_DEFAULT_HOST = 'localhost';

    /**
     * @var UriParser
     */
    protected $parser;

    /**
     * Scheme component of the URI.
     *
     * @var string
     */
    private $scheme = '';

    /**
     * User information component of the URI.
     *
     * @var string
     */
    private $userInfo = '';

    /**
     * Host component of the URI.
     *
     * @var string
     */
    private $host = '';

    /**
     * @var null|int
     */
    private $port;

    /**
     * Path component of the URI.
     *
     * @var string
     */
    private $path = '';

    /**
     * Query string of the URI.
     *
     * @var string
     */
    private $query = '';

    /**
     * Fragment component of the URI
     *
     * @var string
     */
    private $fragment = '';

    public function __construct(string $uri, UriParser $parser)
    {
        $components = $this->parser->parse($uri);

        if (false === $components) {
            throw new InvalidArgumentException(sprintf('Unable to instantiate URI.'));
        }

        $this->setComponents($components);
    }

    protected function setComponents(array $components)
    {
        if (isset($components['scheme'])) {
            $this->scheme = $this->normalizeScheme($components['scheme']);
        }

        if (isset($components['host'])) {
            $this->host = $this->normalizeHost($components['host']);
        }

        if (isset($components['port'])) {
            $this->port = $this->validatePort($components['port']);
        }

        if (isset($components['user'])) {
            $this->userInfo = $components['user'];
        }

        if (isset($components['pass'])) {
            if (!$this->userInfo) {
                throw new InvalidArgumentException('User cannot be empty if password is specified.');
            }

            $this->userInfo .= ':' . $components['pass'];
        }

        if (isset($components['path'])) {
            $this->path = $this->normalizePath($components['path']);
        }

        if (isset($components['query'])) {
            $this->query = $this->normalizeQuery($components['query']);
        }

        if (isset($components['fragment'])) {
            $this->fragment = $this->normalizeChars($components['fragment']);
        }

        $this->addDefaultHost();
        $this->removeDefaultPort();
        $this->validateAuthority();

    }

    /**
     * @param string $scheme
     * @return string
     */
    protected function normalizeScheme(string $scheme): string
    {
        return strtolower($this->normalizeChars($scheme));
    }

    protected function normalizeChars(string $component)
    {
        return filter_var($component, FILTER_SANITIZE_URL);
    }

    /**
     * @param string $host
     * @return string
     */
    protected function normalizeHost(string $host): string
    {
        return strtolower($this->normalizeChars($host));
    }

    /**
     * @link https://www.ietf.org/rfc/rfc1700.txt
     *
     * @param int $port
     * @return int
     * @throws \InvalidArgumentException
     */
    protected function validatePort(int $port): int
    {
        if (1 > $port || $port > 65535) {
            throw new InvalidArgumentException('Port must be integer between 1 - 65535');
        }

        return $port;
    }

    /**
     * @param string $path
     * @return string
     */
    protected function normalizePath(string $path): string
    {
        if ('' === $path) {
            return '';
        }

        $parts = [];
        $elements = explode('/', $path);

        foreach ($elements as $value) {
            $parts[] = rawurlencode(urldecode($value));
        }

        return implode('/', $parts);
    }

    /**
     * @param string $query
     * @return string
     */
    protected function normalizeQuery(string $query): string
    {
        if ('' === $query) {
            return '';
        }

        $parts = [];
        $params = explode('&', $query);

        foreach ($params as $param) {
            if ('' === $param || '=' === $param[0]) {
                continue;
            }

            $keyValuePair = explode('=', $param, 2);
            $parts[] = isset($keyValuePair[1]) ?
                rawurlencode(urldecode($keyValuePair[0])) . '=' . rawurlencode(urldecode($keyValuePair[1])) :
                $keyValuePair[0];
        }

        return implode('&', $parts);
    }

    /**
     * @link https://tools.ietf.org/html/rfc3986#section-3.2.2
     */
    protected function addDefaultHost()
    {
        if (!$this->host && ($this->scheme === 'http' || $this->scheme === 'https')) {
            $this->host = self::HTTP_DEFAULT_HOST;
        }
    }

    protected function removeDefaultPort()
    {
        if ($this->port && $this->scheme && $this->port === self::DEFAULT_PORTS[$this->scheme]) {
            $this->port = '';
        }
    }

    /**
     * @link https://tools.ietf.org/html/rfc3986#section-3
     * @link https://tools.ietf.org/html/rfc3986#section-3.2.2
     * @link https://tools.ietf.org/html/rfc3986#section-3.3
     *
     * @throws \InvalidArgumentException
     */
    protected function validateAuthority()
    {
        if (!$this->getAuthority()) {
            if (0 === strpos($this->path, '//')) {
                throw new InvalidArgumentException('If a URI does not contain an authority component, then the path cannot begin with two slash characters ("//").');
            }

            if (!$this->scheme && false !== strpos(explode('/', $this->path, 2)[0], ':')) {
                throw new InvalidArgumentException('Relative-path: first path segment cannot contain a colon (":") character');
            }
        } elseif ($this->path && $this->path[0] !== '/') {
            throw new InvalidArgumentException('The path of a URI with an authority must start with a slash ("/") or be empty');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthority(): string
    {
        $authority = '';

        if ($this->userInfo) {
            $authority .= $this->userInfo . '@';
        }

        $authority .= $this->host;

        if ($this->port) {
            $authority .= ':' . $this->port;
        }

        return $authority;
    }

    /**
     * {@inheritdoc}
     */
    public function getScheme(): string
    {
        return $this->scheme;
    }

    /**
     * {@inheritdoc}
     */
    public function getUserInfo(): string
    {
        return $this->userInfo;
    }

    /**
     * {@inheritdoc}
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * {@inheritdoc}
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * {@inheritdoc}
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * {@inheritdoc}
     */
    public function getQuery(): string
    {
        return $this->query;
    }

    /**
     * {@inheritdoc}
     */
    public function getFragment(): string
    {
        return $this->fragment;
    }

    /**
     * {@inheritdoc}
     */
    public function withScheme($scheme): Uri
    {
        $newUri = clone $this;

        $newUri->scheme = $this->normalizeScheme((string)$scheme);
        $newUri->removeDefaultPort();
        $newUri->addDefaultHost();
        $newUri->validateAuthority();

        return $newUri;
    }

    /**
     * {@inheritdoc}
     * @throws \InvalidArgumentException
     */
    public function withUserInfo($user, $password = null): Uri
    {
        $userInfo = (string)$user;
        if ($password) {
            if (!$userInfo) {
                throw new InvalidArgumentException('Cannot set password without user.');
            }
            $userInfo .= ':' . $password;
        }

        $newUri = clone $this;

        $newUri->userInfo = $this->normalizeChars($userInfo);
        $newUri->validateAuthority();

        return $newUri;
    }

    /**
     * {@inheritdoc}
     */
    public function withHost($host): Uri
    {
        $newUri = clone $this;

        $newUri->host = $this->normalizeHost((string)$host);
        $newUri->addDefaultHost();
        $newUri->validateAuthority();

        return $newUri;
    }

    /**
     * {@inheritdoc}
     */
    public function withPort($port): Uri
    {
        $newPort = null;

        if ($port) {
            $newPort = $this->validatePort((int)$port);
        }

        $newUri = clone $this;

        $newUri->port = $newPort;
        $newUri->removeDefaultPort();
        $newUri->validateAuthority();

        return $newUri;
    }

    /**
     * {@inheritdoc}
     */
    public function withPath($path): Uri
    {
        $newUri = clone $this;

        $newUri->path = $this->normalizePath((string)$path);
        $newUri->validateAuthority();

        return $newUri;
    }

    /**
     * {@inheritdoc}
     *
     * @return Uri
     */
    public function withQuery($query): Uri
    {
        $newUri = clone $this;

        $newUri->query = $this->normalizeQuery($query);

        return $newUri;
    }

    /**
     * {@inheritdoc}
     */
    public function withFragment($fragment): Uri
    {
        $newUri = clone $this;

        $newUri->fragment = $this->normalizeChars($fragment);

        return $newUri;
    }

    /**
     * {@inheritdoc}
     *
     * @link https://tools.ietf.org/html/rfc3986#section-5.3
     */
    public function __toString(): string
    {
        $result = '';

        if ($this->scheme) {
            $result .= $this->scheme . ':';
        }

        if ($this->getAuthority()) {
            $result .= '//' . $this->getAuthority();
        }

        $result .= $this->path;

        if ($this->query) {
            $result .= '?' . $this->query;
        }

        if ($this->fragment) {
            $result .= '#' . $this->fragment;
        }

        return $result;
    }
}
