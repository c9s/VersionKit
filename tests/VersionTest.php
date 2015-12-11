<?php
use VersionKit\Version;

class VersionTest extends PHPUnit_Framework_TestCase
{
    public function versionProvider()
    {
        return array(
            array('php-5.3.1'),
            array('php-5.3.22'),
            array('php-5.4'),
            array('php-5'),
            array('hhvm-3.2'),
            array('php-5.3.0-dev'),
            array('php-5.3.0-alpha3'),
            array('php-5.3.0-beta2'),
            array('php-5.3.0-RC5'),
            array('5.3.0-dev'),
            array('5.3.0-alpha3'),
            array('5.3.0-beta2'),
            array('5.3.0-RC5'),
            array('5.3.3'),
            array('5.3.0'),
            array('5.3'),
            array('5'),
        );
    }

    /**
     * @dataProvider versionProvider
     */
    public function testVersionConstructor($versionStr)
    {
        $version = new Version($versionStr);
        $this->assertNotNull($version->major);
        $this->assertEquals($versionStr, $version->getCanonicalizedVersionName());
    }
}



