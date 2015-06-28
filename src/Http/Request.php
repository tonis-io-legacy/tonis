<?php
namespace Tonis\Http;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
use Tonis\App;
use Zend\Stratigility\Http\Request as StratigilityRequest;

final class Request extends StratigilityRequest implements \ArrayAccess
{
    /** @var App */
    private $app;
    /**
     * An array of parameters from route matches. e.g., /user/user_id would have a
     * param of user_id. Params are accessible via ArrayAccess.
     *
     * @var array
     */
    private $params = [];

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
     * Retrieve the instance of `Tonis\App` bound to the request.
     *
     * @return App
     */
    public function app()
    {
        return $this->app;
    }

    /**
     * Get all route params from the matched route.
     *
     * @return array
     */
    public function getParams()
    {
        return $this->params;
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

    /**
     * {@inheritDoc}
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->params);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetGet($offset)
    {
        return $this->offsetExists($offset) ? $this->params[$offset] : null;
    }

    /**
     * {@inheritDoc}
     */
    public function offsetSet($offset, $value)
    {
        $this->params[$offset] = $value;
    }

    /**
     * {@inheritDoc}
     */
    public function offsetUnset($offset)
    {
        if ($this->offsetExists($offset)) {
            unset($this->params[$offset]);
        }
    }
}
