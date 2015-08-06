<?php
namespace Tonis;

interface PackageInterface
{
    /**
     * @param App $app
     * @return void
     */
    public function register(App $app);
}
