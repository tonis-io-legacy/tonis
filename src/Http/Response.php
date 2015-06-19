<?php
namespace Tonis\Http;

use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Tonis\App;
use Zend\Stratigility\Http\Response as StratigilityResponse;

class Response extends StratigilityResponse
{
    /** @var App */
    private $app;

    /**
     * @param App $app
     * @param ResponseInterface $response
     */
    public function __construct(App $app, ResponseInterface $response)
    {
        $this->app = $app;
        parent::__construct($response);
    }

    /**
     * @return App
     */
    public function app()
    {
        return $this->app;
    }

    /**
     * Encodes the input as JSON.
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
     * Encodes the input as JSONP.
     *
     * @param mixed $input
     * @param $callback
     * @return self
     */
    public function jsonp($input, $callback)
    {
        return $this
            ->withHeader('Content-Type', 'application/json')
            ->write(sprintf('%s(%s);', $callback, json_encode($input)));
    }

    /**
     * Renders the input using the ViewManager.
     *
     * @param string $template
     * @param array $params
     */
    public function render($template, array $params = [])
    {
        $this->write($this->app->getViewManager()->render($template, $params));
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

    public function withStatus($header, $reasonPhrase = null)
    {
        return new self($this->app, parent::withStatus($header, $reasonPhrase));
    }
}
