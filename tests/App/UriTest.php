<?php

declare(strict_types = 1);

namespace Tests\App;

use App\Uri;
use InvalidArgumentException;

/**
 * Class UriTest
 * @package Tests\App
 */
class UriTest extends TestCase
{

    public function testToStringScheme()
    {
        $expectedComponent = 'https';
        $input_uri = $expectedComponent . '://user:pass@example.org:443/path/123?search=baz#bar';

        $uri = new Uri($input_uri);

        static::assertEquals($expectedComponent, $uri->getScheme());
    }

    public function testToStringAuthority()
    {
        $component = 'user:pass@example.org:443';
        $expectedComponent = 'user:pass@example.org';
        $input_uri = 'https://' . $component . '/example-path/123?search=baz#bar';

        $uri = new Uri($input_uri);

        static::assertEquals($expectedComponent, $uri->getAuthority());
    }

    public function testToStringPath()
    {
        $expectedComponent = '/example-path/123';
        $input_uri = 'https://user:pass@example.org:443' . $expectedComponent . '?search=baz#bar';

        $uri = new Uri($input_uri);

        static::assertEquals($expectedComponent, $uri->getPath());
    }

    public function testToStringQuery()
    {
        $expectedComponent = 'search=baz';
        $input_uri = 'https://user:pass@example.org:443/path/123?' . $expectedComponent . '#bar';

        $uri = new Uri($input_uri);

        static::assertEquals($expectedComponent, $uri->getQuery());
    }

    public function testToStringFragment()
    {
        $expectedComponent = 'bar';
        $input_uri = 'https://user:pass@example.org:443/path/123?search=baz#' . $expectedComponent;

        $uri = new Uri($input_uri);

        static::assertEquals($expectedComponent, $uri->getFragment());
    }

    public function testToStringHappyPath()
    {
        $input_uri = 'https://user:pass@example.org:443/path/123?search=baz#bar';
        $expected = 'https://user:pass@example.org/path/123?search=baz#bar';

        $uri = new Uri($input_uri);

        static::assertEquals($expected, (string)$uri);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testPasswordWithoutUser()
    {
        $input_uri = 'https://:pass@example.org:443/path/123?search=baz#bar';

        new Uri($input_uri);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testTooLowPortValue()
    {
        $port = 0;
        $input_uri = 'https://user:pass@example.org:' . $port . '/path/123?search=baz#bar';

        new Uri($input_uri);
    }

    public function testMinPortValue()
    {
        $port = 1;
        $input_uri = 'https://user:pass@example.org:' . $port . '/path/123?search=baz#bar';

        $uri = new Uri($input_uri);

        static::assertEquals($port, $uri->getPort());
    }

    public function testMaxPortValue()
    {
        $port = 65535;
        $input_uri = 'https://user:pass@example.org:' . $port . '/path/123?search=baz#bar';

        $uri = new Uri($input_uri);

        static::assertEquals($port, $uri->getPort());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testTooHighPortValue()
    {
        $port = 65536;
        $input_uri = 'https://user:pass@example.org:' . $port . '/path/123?search=baz#bar';

        new Uri($input_uri);
    }

    public function testWithSchemeRemoveScheme()
    {
        $input_uri = 'https://user:pass@example.org:443/path/123?search=baz#bar';

        $old = new Uri($input_uri);
        $new = $old->withScheme('');

        static::assertEquals('https', $old->getScheme());
        static::assertEquals('', $new->getScheme());
    }

    public function testWithSchemeChangeScheme()
    {
        $input_uri = 'https://user:pass@example.org:80/path/123?search=baz#bar';

        $old = new Uri($input_uri);
        $new = $old->withScheme('http');

        static::assertEquals('https', $old->getScheme());
        static::assertEquals('80', $old->getPort());
        static::assertEquals('http', $new->getScheme());
        static::assertEquals('', $new->getPort());
    }

    public function testWithUserInfo()
    {
        $user = 'user';
        $pass = 'pass';
        $input_uri = 'https://example.org:80/path/123?search=baz#bar';

        $old = new Uri($input_uri);
        $new = $old->withUserInfo($user, $pass);

        static::assertEquals('', $old->getUserInfo());
        static::assertEquals('user:pass', $new->getUserInfo());
    }

    public function testWithUserInfoRemoveInfo()
    {
        $input_uri = 'https://user:pass@example.org:80/path/123?search=baz#bar';

        $old = new Uri($input_uri);
        $new = $old->withUserInfo('');

        static::assertEquals('user:pass', $old->getUserInfo());
        static::assertEquals('', $new->getUserInfo());
    }

    public function testWithUserInfoOnlyUser()
    {
        $user = 'user';
        $input_uri = 'https://example.org:80/path/123?search=baz#bar';

        $old = new Uri($input_uri);
        $new = $old->withUserInfo($user);

        static::assertEquals('', $old->getUserInfo());
        static::assertEquals('user', $new->getUserInfo());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testWithUserInfoSetPasswordWithoutUser()
    {
        $user = '';
        $pass = 'pass';
        $input_uri = 'https://example.org:80/path/123?search=baz#bar';

        $old = new Uri($input_uri);
        $old->withUserInfo($user, $pass);
    }

    public function testWithHostChangeHost()
    {
        $newHost = 'example2.com';
        $input_uri = 'https://example.org:80/path/123?search=baz#bar';

        $old = new Uri($input_uri);
        $new = $old->withHost($newHost);

        static::assertEquals('example.org', $old->getHost());
        static::assertEquals($newHost, $new->getHost());
    }

    public function testWithHostRemoveAndCheckDefaultHost()
    {
        $newHost = '';
        $input_uri = 'https://example.org:80/path/123?search=baz#bar';

        $old = new Uri($input_uri);
        $new = $old->withHost($newHost);

        static::assertEquals('example.org', $old->getHost());
        static::assertEquals('localhost', $new->getHost());
    }

    public function testWithPort()
    {
        $newPort = 100;
        $input_uri = 'https://example.org:80/path/123?search=baz#bar';

        $old = new Uri($input_uri);
        $new = $old->withPort($newPort);

        static::assertEquals(80, $old->getPort());
        static::assertEquals(100, $new->getPort());
    }

    public function testWithPortChangedToDefault()
    {
        $newPort = 443;
        $input_uri = 'https://example.org:80/path/123?search=baz#bar';

        $old = new Uri($input_uri);
        $new = $old->withPort($newPort);

        static::assertEquals(80, $old->getPort());
        static::assertEquals('', $new->getPort());
    }

    public function testWithPortRemovePort()
    {
        $newPort = 0;
        $input_uri = 'https://example.org:80/path/123?search=baz#bar';

        $old = new Uri($input_uri);
        $new = $old->withPort($newPort);

        static::assertEquals(80, $old->getPort());
        static::assertNull($new->getPort());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testWithPortNegativePort()
    {
        $newPort = -1;
        $input_uri = 'https://example.org:80/path/123?search=baz#bar';

        $old = new Uri($input_uri);
        $old->withPort($newPort);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testWithPortTooHighPortValue()
    {
        $newPort = 65536;
        $input_uri = 'https://example.org:80/path/123?search=baz#bar';

        $old = new Uri($input_uri);
        $old->withPort($newPort);
    }

    public function testWithPortMinValue()
    {
        $newPort = 1;
        $input_uri = 'https://example.org:80/path/123?search=baz#bar';

        $old = new Uri($input_uri);
        $new = $old->withPort($newPort);

        static::assertEquals(80, $old->getPort());
        static::assertEquals($newPort, $new->getPort());
    }

    public function testWithPortMaxValue()
    {
        $newPort = 65535;
        $input_uri = 'https://example.org:80/path/123?search=baz#bar';

        $old = new Uri($input_uri);
        $new = $old->withPort($newPort);

        static::assertEquals(80, $old->getPort());
        static::assertEquals($newPort, $new->getPort());
    }

    public function testWithPathChangePath()
    {
        $newPath = '/foo/bar';
        $input_uri = 'https://example.org:80/path/123?search=baz#bar';

        $old = new Uri($input_uri);
        $new = $old->withPath($newPath);

        static::assertEquals('/path/123', $old->getPath());
        static::assertEquals($newPath, $new->getPath());
    }

    public function testWithPathRemovePath()
    {
        $newPath = '';
        $input_uri = 'https://example.org:80/path/123?search=baz#bar';

        $old = new Uri($input_uri);
        $new = $old->withPath($newPath);

        static::assertEquals('/path/123', $old->getPath());
        static::assertEquals($newPath, $new->getPath());
    }

    public function testWithPathChangePathCheckEncoding()
    {
        $newPath = '/foo/ba! r';
        $input_uri = 'https://example.org:80/path/123?search=baz#bar';

        $old = new Uri($input_uri);
        $new = $old->withPath($newPath);

        static::assertEquals('/path/123', $old->getPath());
        static::assertEquals('/foo/ba%21%20r', $new->getPath());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testWithPathWrongPath()
    {
        $newPath = 'foo/bar';
        $input_uri = 'https://example.org:80/path/123?search=baz#bar';

        $old = new Uri($input_uri);
        $old->withPath($newPath);
    }

    public function testWithQueryChangeQuery()
    {
        $newQuery = 'search=buzz';
        $input_uri = 'https://example.org:80/path/123?search=baz#bar';

        $old = new Uri($input_uri);
        $new = $old->withQuery($newQuery);

        static::assertEquals('search=baz', $old->getQuery());
        static::assertEquals($newQuery, $new->getQuery());
    }

    public function testWithQueryChangeQueryCheckEncoding()
    {
        $newQuery = 'sear!ch=buz z';
        $input_uri = 'https://example.org:80/path/123?search=baz#bar';

        $old = new Uri($input_uri);
        $new = $old->withQuery($newQuery);

        static::assertEquals('search=baz', $old->getQuery());
        static::assertEquals('sear%21ch=buz%20z', $new->getQuery());
    }

    public function testWithQueryRemoveQuery()
    {
        $newQuery = '';
        $input_uri = 'https://example.org:80/path/123?search=baz#bar';

        $old = new Uri($input_uri);
        $new = $old->withQuery($newQuery);

        static::assertEquals('search=baz', $old->getQuery());
        static::assertEquals($newQuery, $new->getQuery());
    }

    public function testWithFragmentChangeFragment()
    {
        $newFragment = 'bizz';
        $input_uri = 'https://example.org:80/path/123?search=baz#bar';

        $old = new Uri($input_uri);
        $new = $old->withFragment($newFragment);

        static::assertEquals('bar', $old->getFragment());
        static::assertEquals($newFragment, $new->getFragment());
    }

    public function testWithFragmentRemoveFragment()
    {
        $newFragment = '';
        $input_uri = 'https://example.org:80/path/123?search=baz#bar';

        $old = new Uri($input_uri);
        $new = $old->withFragment($newFragment);

        static::assertEquals('bar', $old->getFragment());
        static::assertEquals($newFragment, $new->getFragment());
    }

    public function testValidateAuthority()
    {
        $input_uri = 'https://example.org:80/path/123?search=baz#bar';

        $uri = new Uri($input_uri);

        $method = self::getUriMethod('validateAuthority');

        $method->invoke($uri);
    }

    public function testNormalizeQuery()
    {
        $input_uri = 'https://example.org:80/path/123?=#bar';

        $uri = new Uri($input_uri);

        $method = self::getUriMethod('normalizeQuery');
        $result = $method->invokeArgs($uri, ['']);

        self::assertEquals('', $result);
    }
}
