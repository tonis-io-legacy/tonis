<?php
namespace Tonis\Http;

use Zend\Stratigility\Http\Request as StratigilityRequest;

final class Request extends StratigilityRequest
{
    /**
     * An array of parameters from route matches. e.g., /user/user_id would have a
     * param of user_id. Params are accessible via ArrayAccess.
     *
     * @var array
     */
    private $params = [];

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
}