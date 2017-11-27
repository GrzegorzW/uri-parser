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
    private const DEFAULT_PORTS = [
        'http' => 80,
        'https' => 443,
        'ftp' => 21,
        'ftps' => 990
    ];

    private const HTTP_DEFAULT_HOST = 'localhost';

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

    /**
     * Uri constructor.
     *
     * @param array $components
     *
     * Allowed keys: scheme, host, port, user, pass, path, query, fragment
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(array $components)
    {
        $this->processComponents($components);
    }

    /**
     * @param array $components
     *
     * @throws \InvalidArgumentException
     */
    private function processComponents(array $components)
    {
        if (array_key_exists('scheme', $components)) {
            $this->scheme = $this->normalizeScheme($components['scheme']);
        }

        if (array_key_exists('host', $components)) {
            $this->host = $this->normalizeHost($components['host']);
        }

        if (array_key_exists('port', $components)) {
            $this->port = $this->validatePort($components['port']);
        }

        if (array_key_exists('user', $components)) {
            $this->userInfo = $components['user'];
        }

        if (array_key_exists('pass', $components)) {
            if (!$this->userInfo) {
                throw new InvalidArgumentException('User cannot be empty if password is specified.');
            }

            $this->userInfo .= ':' . $components['pass'];
        }

        if (array_key_exists('path', $components)) {
            $this->path = $this->normalizePath($components['path']);
        }

        if (array_key_exists('query', $components)) {
            $this->query = $this->normalizeQuery($components['query']);
        }

        if (array_key_exists('fragment', $components)) {
            $this->fragment = $this->normalizeChars($components['fragment']);
        }

        $this->addDefaultHost();
        $this->removeDefaultPort();
        $this->validateAuthority();
    }

    /**
     * @param string $scheme
     *
     * @return string
     */
    private function normalizeScheme(string $scheme): string
    {
        return strtolower($this->normalizeChars($scheme));
    }

    /**
     * @param string $component
     *
     * @return mixed
     */
    private function normalizeChars(string $component): string
    {
        return filter_var($component, FILTER_SANITIZE_URL);
    }

    /**
     * @param string $host
     *
     * @return string
     */
    private function normalizeHost(string $host): string
    {
        return strtolower($this->normalizeChars($host));
    }

    /**
     * @link https://www.ietf.org/rfc/rfc1700.txt
     *
     * @param $port
     *
     * @return int
     *
     * @throws \InvalidArgumentException
     */
    private function validatePort($port): int
    {
        if (1 > $port || $port > 65535) {
            throw new InvalidArgumentException('Port must be integer between 1 - 65535');
        }

        return (int)$port;
    }

    /**
     * @param string $path
     *
     * @return string
     */
    private function normalizePath(string $path): string
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
     *
     * @return string
     */
    private function normalizeQuery(string $query): string
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
            $parts[] = array_key_exists(1, $keyValuePair) ?
                rawurlencode(urldecode($keyValuePair[0])) . '=' . rawurlencode(urldecode($keyValuePair[1])) :
                $keyValuePair[0];
        }

        return implode('&', $parts);
    }

    /**
     * @link https://tools.ietf.org/html/rfc3986#section-3.2.2
     */
    private function addDefaultHost()
    {
        if (!$this->host && ($this->scheme === 'http' || $this->scheme === 'https')) {
            $this->host = self::HTTP_DEFAULT_HOST;
        }
    }

    private function removeDefaultPort()
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
    private function validateAuthority()
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
    public function getPort(): ?int
    {
        return $this->port ? (int)$this->port : null;
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
            $newPort = $this->validatePort($port);
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

        if ($scheme = $this->getScheme()) {
            $result .= $scheme . ':';
        }

        if ($authority = $this->getAuthority()) {
            $result .= '//' . $authority;
        }

        $result .= $this->getPath();

        if ($query = $this->getQuery()) {
            $result .= '?' . $query;
        }

        if ($fragment = $this->getFragment()) {
            $result .= '#' . $fragment;
        }

        return $result;
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
}
