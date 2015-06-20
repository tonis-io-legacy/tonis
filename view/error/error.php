<h1><?=$response->getStatusCode()?> <small>An Error Occurred</small></h1>

<p><?=$this->e($message)?></p>

<?php if ($exception) : ?>
<h2>Additional Information:</h2>
<?php endif; ?>

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
