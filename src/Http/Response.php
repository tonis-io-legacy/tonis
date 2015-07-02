<?php
namespace Tonis\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Tonis\App;

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
        $this->decorated = $decorated;;
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

    public function getProtocolVersion()
    {
        return $this->decorated->getProtocolVersion();
    }

    public function withProtocolVersion($version)
    {
        return new self($this->app, $this->decorated->withProtocolVersion($version));
    }

    public function getHeaders()
    {
        return $this->decorated->getHeaders();
    }

    public function hasHeader($name)
    {
        return $this->hasHeader($name);
    }

    public function getHeader($name)
    {
        return $this->decorated->getHeader($name);
    }

    public function getHeaderLine($name)
    {
        return $this->decorated->getHeader($name);
    }

    public function withHeader($name, $value)
    {
        return new self($this->app, $this->decorated->withHeader($name, $value));
    }

    public function withAddedHeader($name, $value)
    {
        return new self($this->app, $this->decorated->withAddedHeader($name, $value));
    }

    public function withoutHeader($name)
    {
        return new self($this->app, $this->decorated->withoutHeader($name));
    }

    public function getBody()
    {
        return $this->decorated->getBody();
    }

    public function withBody(StreamInterface $body)
    {
        return new self($this->app, $this->decorated->withBody($body));
    }

    public function getStatusCode()
    {
        return $this->decorated->getStatusCode();
    }

    public function withStatus($code, $reasonPhrase = '')
    {
        return new self($this->app, $this->decorated->withStatus($code, $reasonPhrase));
    }

    public function getReasonPhrase()
    {
        return $this->decorated->getReasonPhrase();
    }

    public function write($data)
    {
        $this->getBody()->write($data);
        return $this;
    }
}
