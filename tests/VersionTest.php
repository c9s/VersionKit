<?php
use VersionKit\Version;

class VersionTest extends PHPUnit_Framework_TestCase
{
    public function versionProvider()
    {
        return array(
            array('php-5.3.22'),
            array('php-5.4'),
            array('php-5'),
            array('hhvm-3.2'),
        );
    }

    /**
     * @dataProvider versionProvider
     */
    public function testVersionConstructor($versionStr)
    {
        $version = new Version($versionStr);
    }
}



