<h1><?=$response->getStatusCode()?> <small><?=$this->e($message ? $message : 'An Error Occurred')?></small></h1>

<?php if (isset($exception)) : ?>
<h2>Additional Information:</h2>

<li>
    <h3><?=get_class($exception)?></h3>
    <dl>
        <dt>File:</dt>
        <dd>
            <pre><?=$exception->getFile()?>:<?=$exception->getLine()?></pre>
        </dd>
        <dt>Message:</dt>
        <dd>
            <pre><?=$exception->getMessage() ? $exception->getMessage() : 'No message available.' ?></pre>
        </dd>
        <dt>Stack trace:</dt>
        <dd>
            <pre class="pre-scrollable"><?=$exception->getTraceAsString()?></pre>
        </dd>
    </dl>
</li>
<?php endif; ?>
