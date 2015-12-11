<?php
namespace VersionKit;
use ArrayIterator;
use ArrayAccess;
use Countable;
use IteratorAggregate;
use RuntimeException;

class VersionCollection implements ArrayAccess, IteratorAggregate, Countable
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

    public function sortAscending()
    {
        return usort($this->versions, function($a, $b) {
            return $a->compare($b);
        });
    }

    public function sortDescending()
    {
        return usort($this->versions, function($a, $b) {
            return $b->compare($a);
        });
    }

    public function filterByMajorAndMinorVersion($majorVersion, $minorVersion)
    {
        return new VersionCollection(array_filter($this->versions, function($version) use ($majorVersion, $minorVersion) {
            return $version->major == $majorVersion && $version->minor == $minorVersion;
        }));
    }

    public function filterByMajorVersion($majorVersion)
    {
        return new VersionCollection(array_filter($this->versions, function($version) use ($majorVersion) {
            return $version->major == $majorVersion;
        }));
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

    public function toJson()
    {
        return json_encode(array_map(function($version) {
            return $version->__toString();
        }, $this->versions));
    }

    public function toArray()
    {
        return $this->versions;
    }

    public function count()
    {
        return count($this->versions);
    }


}
