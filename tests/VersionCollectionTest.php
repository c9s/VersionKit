<?php
use VersionKit\Version;
use VersionKit\VersionCollection;

class VersionCollectionTest extends PHPUnit_Framework_TestCase
{
    public function testFilter()
    {
        $versions = new VersionCollection(['php-5.3.0', 'php-5.3.3', 'php-5.4.0', 'php-5.5.0', 'php-7.0.0']);
        $versions7 = $versions->filterByMajorVersion(7);
        $versions5 = $versions->filterByMajorVersion(5);
        $this->assertCount(1, $versions7);
        $this->assertCount(4, $versions5);
        $versions53 = $versions->filterByMajorAndMinorVersion(5,3);
        $this->assertCount(2, $versions53);
    }

}