<?php

namespace Hongyukeji\LaravelSupport\Illuminate\Support;

use Illuminate\Support\Composer as BaseComposer;

class Composer extends BaseComposer
{
    public function install($package_name = '')
    {
        // \Illuminate\Foundation\Providers\ComposerServiceProvider

        $command = array_merge($this->findComposer(), ['install'], $package_name);

        $this->getProcess($command)->run();
    }
}
