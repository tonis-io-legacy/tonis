<?php
namespace Tonis\Http;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
use Tonis\App;
use Zend\Stratigility\Http\Request as StratigilityRequest;

final class Request extends StratigilityRequest
{
    /**
     * @param App $app
     * @param ServerRequestInterface $request
     */
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

    /**
     * {@inheritDoc}
     */
    public function withRequestTarget($requestTarget)
    {
        return new self($this->app, parent::withRequestTarget($requestTarget));
    }

    /**
     * {@inheritDoc}
     */
    public function withProtocolVersion($version)
    {
        return new self($this->app, parent::withProtocolVersion($version));
    }

    /**
     * {@inheritDoc}
     */
    public function withBody(StreamInterface $body)
    {
        return new self($this->app, parent::withBody($body));
    }

    /**
     * {@inheritDoc}
     */
    public function withHeader($header, $value)
    {
        return new self($this->app, parent::withHeader($header, $value));
    }

    /**
     * {@inheritDoc}
     */
    public function withAddedHeader($header, $value)
    {
        return new self($this->app, parent::withAddedHeader($header, $value));
    }

    /**
     * {@inheritDoc}
     */
    public function withoutHeader($header)
    {
        return new self($this->app, parent::withoutHeader($header));
    }

    /**
     * {@inheritDoc}
     */
    public function withMethod($method)
    {
        return new self($this->app, parent::withMethod($method));
    }

    /**
     * {@inheritDoc}
     */
    public function withUri(UriInterface $uri, $preserveHost = false)
    {
        return new self($this->app, parent::withUri($uri, $preserveHost));
    }

    /**
     * {@inheritDoc}
     */
    public function withCookieParams(array $cookies)
    {
        return new self($this->app, parent::withCookieParams($cookies));
    }

    /**
     * {@inheritDoc}
     */
    public function withQueryParams(array $query)
    {
        return new self($this->app, parent::withQueryParams($query));
    }

    /**
     * {@inheritDoc}
     */
    public function withParsedBody($params)
    {
        return new self($this->app, parent::withParsedBody($params));
    }

    /**
     * {@inheritDoc}
     */
    public function withAttribute($attribute, $value)
    {
        return new self($this->app, parent::withAttribute($attribute, $value));
    }

    /**
     * {@inheritDoc}
     */
    public function withoutAttribute($attribute)
    {
        return new self($this->app, parent::withoutAttribute($attribute));
    }
}
