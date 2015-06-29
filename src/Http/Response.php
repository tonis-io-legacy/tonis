<?php
namespace Tonis\Http;

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
