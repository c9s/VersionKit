<?php
use VersionKit\Version;
use VersionKit\VersionCollection;

class VersionCollectionTest extends PHPUnit_Framework_TestCase
{
    public function test()
    {
        $versions = new VersionCollection(['php-5.3.0', 'php-5.3.3', 'php-5.4.0', 'php-5.5.0']);
    }

}
