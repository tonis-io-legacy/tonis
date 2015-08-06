<?php
namespace Tonis\TestAsset;

use Tonis\App;
use Tonis\PackageInterface;

class Package implements PackageInterface
{
    public $ran = false;

    /**
     * {@inheritDoc}
     */
    public function register(App $app)
    {
        $app->add(function ($req, $res) {
            $this->ran = true;
            return $res;
        });
    }
}
