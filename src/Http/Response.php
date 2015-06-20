<?php
namespace Tonis\Http;

use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Tonis\App;
use Zend\Stratigility\Http\Response as StratigilityResponse;

final class Response extends StratigilityResponse
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
     * @return self
     */
    public function render($template, array $params = [])
    {
        return $this->write($this->app->getViewManager()->render($template, $params));
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
    public function withStatus($header, $reasonPhrase = null)
    {
        return new self($this->app, parent::withStatus($header, $reasonPhrase));
    }
}
