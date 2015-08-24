<?php
namespace Tonis\Response;

use SplFixedArray;
use Zend\Diactoros\Response\HtmlResponse;

class RenderResponse extends HtmlResponse
{
    /** @var string */
    private $template;
    /** @var \SplFixedArray */
    private $variables;

    /**
     * @param \Psr\Http\Message\StreamInterface|string $template
     * @param array                                    $variables
     * @param int                                      $status
     * @param array                                    $headers
     */
    public function __construct(
        $template,
        array $variables = [],
        $status = 200,
        array $headers = []
    ) {
        $this->template  = $template;
        $this->variables = SplFixedArray::fromArray($variables);

        parent::__construct('', $status, $headers);
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @return SplFixedArray
     */
    public function getVariables()
    {
        return $this->variables;
    }
}