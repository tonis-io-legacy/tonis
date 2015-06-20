<?php
namespace Tonis;

use Exception;

final class FinalHandler
{
    public function __invoke(Http\Request $req, Http\Response $res, $err = null)
    {
        if ($err) {
            return $this->handleError($err, $req, $res);
        }
        return $this->handle404($req, $res);
    }

    private function handleError($err, Http\Request $req, Http\Response $res)
    {
        $res = $res->withStatus($this->getStatusCode($err, $res));
        $vars = [
            'request' => $req,
            'response' => $res
        ];

        if ($err instanceof Exception) {
            $vars['exception'] = $err;
            $vars['message'] = $err->getMessage();
        } else {
            $vars['message'] = $err;
        }

        $res->render('error/error', $vars);

        return $res;
    }

    private function handle404(Http\Request $req, Http\Response $res)
    {
        $res = $res->withStatus(404);
        $res->render('error/404', ['request' => $req]);

        return $res;
    }

    private function getStatusCode($err, Http\Response $res)
    {
        if ($err instanceof Exception && ($err->getCode() >= 400 && $err->getCode() < 600)) {
            return $err->getCode();
        }

        $status = $res->getStatusCode();
        if (!$status || $status < 400 || $status >= 600) {
            $status = 500;
        }
        return $status;
    }
}
