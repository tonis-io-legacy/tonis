<?php
namespace Tonis\Middleware;

use Tonis\Exception;
use Tonis\Http\Request;
use Tonis\Http\Response;

class Queue extends \SplQueue
{
    /** @var Queue */
    private $processing;

    /**
     * {@inheritDoc}
     */
    public function __invoke(Request $request, Response $response)
    {
        if (null === $this->processing) {
            $this->processing = clone $this;
        }

        if (!$this->processing->count()) {
            return $response;
        }

        $layer = $this->processing->dequeue();

        if (!$layer) {
            return $response;
        }

        if (!is_callable($layer)) {
            throw new Exception\CallableExpected;
        }

        return $layer($request, $response, $this);
    }
}