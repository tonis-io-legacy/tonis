<?php
namespace Tonis\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Tonis\App;
use Tonis\Router\RouteMap;

final class Response implements ResponseInterface
{
    /** @var App */
    private $app;
    /** @var ResponseInterface */
    private $decorated;

    /**
     * @param App $app
     * @param ResponseInterface $decorated
     */
    public function __construct(App $app, ResponseInterface $decorated)
    {
        $this->app       = $app;
        $this->decorated = $decorated;
    }

    /**
     * Retrieve the instance of `Tonis\App` bound to the response.
     *
     * @return App
     */
    public function app()
    {
        return $this->app;
    }

    /**
     * Returns a response with a proper status code and Location header. If permanent
     * is true, a 301 status code is used instead of 302.
     *
     * @param string $url
     * @param bool   $permanent
     * @return ResponseInterface
     */
    public function redirect($url, $permanent = false)
    {
        return $this
            ->withHeader('Location', $url)
            ->withStatus($permanent ? 301 : 302);
    }

    /**
     * Redirects to a named route.
     *
     * @see \Tonis\Http\Response::redirect()
     * @param string $route
     * @param array $params
     * @param bool|false $permanent
     * @return ResponseInterface
     */
    public function redirectToRoute($route, array $params = [], $permanent = false)
    {
        $map = $this->app()->getRouteMap();
        $url = $map->assemble($route, $params);

        return $this->redirect($url, $permanent);
    }

    /**
     * Encodes the input as JSON and sets the Content-Type header to application/json.
     *
     * @param mixed $input
     * @return self
     */
    public function json($input)
    {
        return $this
            ->withHeader('Content-Type', 'application/json')
            ->write(json_encode($input));
    }

    /**
     * Encodes the input as JSONP and sets the Content-Type header to application/javascript.
     *
     * @param mixed $input
     * @param string $callback
     * @return self
     */
    public function jsonp($input, $callback)
    {
        return $this
            ->withHeader('Content-Type', 'application/javascript')
            ->write(sprintf('%s(%s);', $callback, json_encode($input)));
    }

    /**
     * Attempts to render the `$template` with `$params` using the ViewManager. If a strategy
     * is not available then the Tonis fallback strategy is used.
     *
     * @param string $template
     * @param array $params
     * @return string
     */
    public function render($template, array $params = [])
    {
        return $this->write($this->app->getView()->render($template, $params));
    }

    /**
     * {@inheritDoc}
     */
    public function getProtocolVersion()
    {
        return $this->decorated->getProtocolVersion();
    }

    /**
     * {@inheritDoc}
     */
    public function withProtocolVersion($version)
    {
        return new self($this->app, $this->decorated->withProtocolVersion($version));
    }

    /**
     * {@inheritDoc}
     */
    public function getHeaders()
    {
        return $this->decorated->getHeaders();
    }

    /**
     * {@inheritDoc}
     */
    public function hasHeader($name)
    {
        return $this->decorated->hasHeader($name);
    }

    /**
     * {@inheritDoc}
     */
    public function getHeader($name)
    {
        return $this->decorated->getHeader($name);
    }

    /**
     * {@inheritDoc}
     */
    public function getHeaderLine($name)
    {
        return $this->decorated->getHeaderLine($name);
    }

    /**
     * {@inheritDoc}
     */
    public function withHeader($name, $value)
    {
        return new self($this->app, $this->decorated->withHeader($name, $value));
    }

    /**
     * {@inheritDoc}
     */
    public function withAddedHeader($name, $value)
    {
        return new self($this->app, $this->decorated->withAddedHeader($name, $value));
    }

    /**
     * {@inheritDoc}
     */
    public function withoutHeader($name)
    {
        return new self($this->app, $this->decorated->withoutHeader($name));
    }

    /**
     * {@inheritDoc}
     */
    public function getBody()
    {
        return $this->decorated->getBody();
    }

    /**
     * {@inheritDoc}
     */
    public function withBody(StreamInterface $body)
    {
        return new self($this->app, $this->decorated->withBody($body));
    }

    /**
     * {@inheritDoc}
     */
    public function getStatusCode()
    {
        return $this->decorated->getStatusCode();
    }

    /**
     * {@inheritDoc}
     */
    public function withStatus($code, $reasonPhrase = '')
    {
        return new self($this->app, $this->decorated->withStatus($code, $reasonPhrase));
    }

    /**
     * {@inheritDoc}
     */
    public function getReasonPhrase()
    {
        return $this->decorated->getReasonPhrase();
    }

    /**
     * {@inheritDoc}
     */
    public function write($data)
    {
        $this->getBody()->write($data);
        return $this;
    }
}
