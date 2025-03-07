<?php

namespace Fuel\Tasks;

class Clearcache
{
    public static function run()
    {
        $cache_path = APPPATH . 'cache/';
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($cache_path, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($files as $fileinfo) {
            $todo = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
            $todo($fileinfo->getRealPath());
        }

        echo "Cache cleared.\n";
    }
}