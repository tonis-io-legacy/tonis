<?php
namespace Tonis\Router;

use Tonis\Http\Request;
use Tonis\Http\Response;

final class QueryParam extends AbstractParam
{
    /**
     * {@inheritDoc}
     */
    public function shouldInvoke(Request $request, Response $response)
    {
        $query  = $request->getQueryParams();
        $params = $this->params();

        foreach ($params as $param) {
            if (!isset($query[$param])) {
                return false;
            }
        }
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function getValue(Request $request, Response $response)
    {
        $query  = $request->getQueryParams();
        $values = [];
        $params = $this->params();

        foreach ($params as $param) {
            $values[] = $query[$param];
        }

        return count($values) > 1 ? $values : $values[0];
    }

    /**
     * @return array
     */
    private function params()
    {
        return is_array($this->param) ? $this->param : [$this->param];
    }
}
