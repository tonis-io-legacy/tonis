<?php
namespace Tonis\Web\Http;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
use Tonis\Web\App;
use Zend\Stratigility\Http\Request as StratigilityRequest;

class Request extends StratigilityRequest
{
    public function __construct(App $app, ServerRequestInterface $request)
    {
        $this->app = $app;
        parent::__construct($request);
    }

    /**
     * @return App
     */
    public function app()
    {
        return $this->app;
    }

    public function withRequestTarget($requestTarget)
    {
        return new self($this->app, parent::withRequestTarget($requestTarget));
    }

    public function withProtocolVersion($version)
    {
        return new self($this->app, parent::withProtocolVersion($version));
    }

    public function withBody(StreamInterface $body)
    {
        return new self($this->app, parent::withBody($body));
    }

    public function withHeader($header, $value)
    {
        return new self($this->app, parent::withHeader($header, $value));
    }

    public function withAddedHeader($header, $value)
    {
        return new self($this->app, parent::withAddedHeader($header, $value));
    }

    public function withoutHeader($header)
    {
        return new self($this->app, parent::withoutHeader($header));
    }

    public function withMethod($method)
    {
        return new self($this->app, parent::withMethod($method));
    }

    public function withUri(UriInterface $uri, $preserveHost = false)
    {
        return new self($this->app, parent::withUri($uri, $preserveHost));
    }

    public function withCookieParams(array $cookies)
    {
        return new self($this->app, parent::withCookieParams($cookies));
    }

    public function withQueryParams(array $query)
    {
        return new self($this->app, parent::withQueryParams($query));
    }

    public function withParsedBody($params)
    {
        return new self($this->app, parent::withParsedBody($params));
    }

    public function withAttribute($attribute, $value)
    {
        return new self($this->app, parent::withAttribute($attribute, $value));
    }

    public function withoutAttribute($attribute)
    {
        return new self($this->app, parent::withoutAttribute($attribute));
    }
}
