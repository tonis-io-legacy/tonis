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

    public function getProtocolVersion()
    {
        return $this->psrRequest->getProtocolVersion();
    }

    public function withProtocolVersion($version)
    {
        return new self($this->app, $this->psrRequest->withProtocolVersion($version));
    }

    public function getHeaders()
    {
        Return $this->psrRequest->getHeaders();
    }

    public function hasHeader($name)
    {
        return $this->psrRequest->hasHeader($name);
    }

    public function getHeader($name)
    {
        return $this->psrRequest->getHeader($name);
    }

    public function getHeaderLine($name)
    {
        return $this->psrRequest->getHeaderLine($name);
    }

    public function withHeader($name, $value)
    {
        return new self($this->app, $this->psrRequest->withHeader($name, $value));
    }

    public function withAddedHeader($name, $value)
    {
        return new self($this->app, $this->psrRequest->withAddedHeader($name, $value));
    }

    public function withoutHeader($name)
    {
        return new self($this->app, $this->psrRequest->withoutHeader($name));
    }

    public function getBody()
    {
        return $this->psrRequest->getBody();
    }

    public function withBody(StreamInterface $body)
    {
        return new self($this->app, $this->psrRequest->withBody($body));
    }

    public function getRequestTarget()
    {
        return $this->psrRequest->getRequestTarget();
    }

    public function withRequestTarget($requestTarget)
    {
        return new self($this->app, $this->psrRequest->withRequestTarget($requestTarget));
    }

    public function getMethod()
    {
        return $this->psrRequest->getMethod();
    }

    public function withMethod($method)
    {
        return new self($this->app, $this->psrRequest->withMethod($method));
    }

    public function getUri()
    {
        return $this->psrRequest->getUri();
    }

    public function withUri(UriInterface $uri, $preserveHost = false)
    {
        return new self($this->app, $this->psrRequest->withUri($uri, $preserveHost));
    }

    public function getServerParams()
    {
        return $this->psrRequest->getServerParams();
    }

    public function getCookieParams()
    {
        Return $this->psrRequest->getCookieParams();
    }

    public function withCookieParams(array $cookies)
    {
        return new self($this->app, $this->psrRequest->withCookieParams($cookies));
    }

    public function getQueryParams()
    {
        return $this->psrRequest->getQueryParams();
    }

    public function withQueryParams(array $query)
    {
        return new self($this->app, $this->psrRequest->withQueryParams($query));
    }

    public function getUploadedFiles()
    {
        return $this->psrRequest->getUploadedFiles();
    }

    public function withUploadedFiles(array $uploadedFiles)
    {
        return new self($this->app, $this->psrRequest->withUploadedFiles($uploadedFiles));
    }

    public function getParsedBody()
    {
        return $this->psrRequest->getParsedBody();
    }

    public function withParsedBody($data)
    {
        return new self($this->app, $this->psrRequest->withParsedBody($data));
    }

    public function getAttributes()
    {
        return $this->psrRequest->getAttributes();
    }

    public function getAttribute($name, $default = null)
    {
        return $this->psrRequest->getAttribute($name, $default);
    }

    public function withAttribute($name, $value)
    {
        return new self($this->app, $this->psrRequest->withAttribute($name, $value));
    }

    public function withoutAttribute($name)
    {
        return new self($this->app, $this->psrRequest->withoutAttribute($name));
    }
}
