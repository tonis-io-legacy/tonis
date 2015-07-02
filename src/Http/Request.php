<?php
namespace Tonis\Http;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
use Tonis\App;

final class Request implements \ArrayAccess, ServerRequestInterface
{
    /** @var App */
    private $app;
    /** @var ServerRequestInterface */
    private $psrRequest;
    /**
     * An array of parameters from route matches. e.g., /user/user_id would have a
     * param of user_id. Params are accessible via ArrayAccess.
     *
     * @var array
     */
    private $params = [];

    /**
     * @param App $app
     * @param ServerRequestInterface $psrRequest
     */
    public function __construct(
        App $app,
        ServerRequestInterface $psrRequest
    ) {
        $this->app        = $app;
        $this->psrRequest = $psrRequest;
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

    /**
     * {@inheritDoc}
     */
    public function getProtocolVersion()
    {
        return $this->psrRequest->getProtocolVersion();
    }

    /**
     * {@inheritDoc}
     */
    public function withProtocolVersion($version)
    {
        return new self($this->app, $this->psrRequest->withProtocolVersion($version));
    }

    /**
     * {@inheritDoc}
     */
    public function getHeaders()
    {
        Return $this->psrRequest->getHeaders();
    }

    /**
     * {@inheritDoc}
     */
    public function hasHeader($name)
    {
        return $this->psrRequest->hasHeader($name);
    }

    /**
     * {@inheritDoc}
     */
    public function getHeader($name)
    {
        return $this->psrRequest->getHeader($name);
    }

    /**
     * {@inheritDoc}
     */
    public function getHeaderLine($name)
    {
        return $this->psrRequest->getHeaderLine($name);
    }

    /**
     * {@inheritDoc}
     */
    public function withHeader($name, $value)
    {
        return new self($this->app, $this->psrRequest->withHeader($name, $value));
    }

    /**
     * {@inheritDoc}
     */
    public function withAddedHeader($name, $value)
    {
        return new self($this->app, $this->psrRequest->withAddedHeader($name, $value));
    }

    /**
     * {@inheritDoc}
     */
    public function withoutHeader($name)
    {
        return new self($this->app, $this->psrRequest->withoutHeader($name));
    }

    /**
     * {@inheritDoc}
     */
    public function getBody()
    {
        return $this->psrRequest->getBody();
    }

    /**
     * {@inheritDoc}
     */
    public function withBody(StreamInterface $body)
    {
        return new self($this->app, $this->psrRequest->withBody($body));
    }

    /**
     * {@inheritDoc}
     */
    public function getRequestTarget()
    {
        return $this->psrRequest->getRequestTarget();
    }

    /**
     * {@inheritDoc}
     */
    public function withRequestTarget($requestTarget)
    {
        return new self($this->app, $this->psrRequest->withRequestTarget($requestTarget));
    }

    /**
     * {@inheritDoc}
     */
    public function getMethod()
    {
        return $this->psrRequest->getMethod();
    }

    /**
     * {@inheritDoc}
     */
    public function withMethod($method)
    {
        return new self($this->app, $this->psrRequest->withMethod($method));
    }

    /**
     * {@inheritDoc}
     */
    public function getUri()
    {
        return $this->psrRequest->getUri();
    }

    /**
     * {@inheritDoc}
     */
    public function withUri(UriInterface $uri, $preserveHost = false)
    {
        return new self($this->app, $this->psrRequest->withUri($uri, $preserveHost));
    }

    /**
     * {@inheritDoc}
     */
    public function getServerParams()
    {
        return $this->psrRequest->getServerParams();
    }

    /**
     * {@inheritDoc}
     */
    public function getCookieParams()
    {
        Return $this->psrRequest->getCookieParams();
    }

    /**
     * {@inheritDoc}
     */
    public function withCookieParams(array $cookies)
    {
        return new self($this->app, $this->psrRequest->withCookieParams($cookies));
    }

    /**
     * {@inheritDoc}
     */
    public function getQueryParams()
    {
        return $this->psrRequest->getQueryParams();
    }

    /**
     * {@inheritDoc}
     */
    public function withQueryParams(array $query)
    {
        return new self($this->app, $this->psrRequest->withQueryParams($query));
    }

    /**
     * {@inheritDoc}
     */
    public function getUploadedFiles()
    {
        return $this->psrRequest->getUploadedFiles();
    }

    /**
     * {@inheritDoc}
     */
    public function withUploadedFiles(array $uploadedFiles)
    {
        return new self($this->app, $this->psrRequest->withUploadedFiles($uploadedFiles));
    }

    /**
     * {@inheritDoc}
     */
    public function getParsedBody()
    {
        return $this->psrRequest->getParsedBody();
    }

    /**
     * {@inheritDoc}
     */
    public function withParsedBody($data)
    {
        return new self($this->app, $this->psrRequest->withParsedBody($data));
    }

    /**
     * {@inheritDoc}
     */
    public function getAttributes()
    {
        return $this->psrRequest->getAttributes();
    }

    /**
     * {@inheritDoc}
     */
    public function getAttribute($name, $default = null)
    {
        return $this->psrRequest->getAttribute($name, $default);
    }

    /**
     * {@inheritDoc}
     */
    public function withAttribute($name, $value)
    {
        return new self($this->app, $this->psrRequest->withAttribute($name, $value));
    }

    /**
     * {@inheritDoc}
     */
    public function withoutAttribute($name)
    {
        return new self($this->app, $this->psrRequest->withoutAttribute($name));
    }
}
