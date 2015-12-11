<?php
namespace VersionKit;
use ArrayIterator;
use ArrayAccess;
use IteratorAggregate;
use RuntimeException;

class VersionCollection implements ArrayAccess, IteratorAggregate
{
    protected $versions;

    public function __construct(array $versions = array())
    {
        $this->versions = array_map(function($version) {
            if ($version instanceof Version) {
                return $version;
            } else if (is_string($version)) {
                return new Version($version);
            } else {
                throw new RuntimeException('Invalid version type');
            }
        }, $versions);
    }

    /**
     * @param string $minorVersion the version string  to the minor version.
     * @return string found version string
     */
    public function findLatestPatchVersion(Version $versionConstraint)
    {
        $cPatch = $versionConstraint->patch ?: 0;
        foreach ($this->versions as $version) {
            if ($version->major == $versionConstraint->major 
                && $version->minor == $versionConstraint->minor
                && $version->patch > $cPatch)
            {
                $cPatch = $patch;
            }
        }
        $newVersion = clone $versionConstraint;
        $newVersion->patch = $cPatch;
        return $newVersion;
    }

    
    public function offsetSet($idx, $value)
    {
        $this->versions[ $idx ] = $value;
    }
    
    public function offsetExists($idx)
    {
        return isset($this->versions[ $idx ]);
    }
    
    public function offsetGet($idx)
    {
        return $this->versions[ $idx ];
    }
    
    public function offsetUnset($idx)
    {
        unset($this->versions[$idx]);
    }
    
    public function getIterator()
    {
        return new ArrayIterator($this->versions);
    }


}
