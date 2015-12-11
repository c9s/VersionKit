<?php
namespace VersionKit;
use ArrayIterator;
use ArrayAccess;
use IteratorAggregate;

class VersionCollection implements ArrayAccess, IteratorAggregate
{
    protected $versions;

    public function __construct(array $versions = array())
    {
        $this->versions = $versions;
    }

    /**
     * @param string $minorVersion the version string  to the minor version.
     *
     * @return string found version string
     */
    public function findLatestPatchVersion($minorVersion)
    {
        // Trim 5.4.29 to 5.4
        $va = explode('.', $minorVersion);
        if (count($va) == 3) {
            list($cMajor, $cMinor, $cPatch) = $va;
        } elseif(count($va) == 2) {
            list($cMajor, $cMinor) = $va;
            $cPatch = 0;
        }
        foreach ($this->versions as $version) {
            list($major, $minor, $patch) = explode('.', $version);
            if ($major == $cMajor && $minor == $cMinor && $patch > $cPatch) {
                $cPatch = $patch;
            }
        }
        return join('.', array($cMajor, $cMinor, $cPatch));
    }


    /**
     * Bump a version's patch version to the latest available patch version.
     *
     * @param string $version
     */
    public function upgradePatchVersion($version)
    {
        $this->version = $this->findLatestPatchVersion($version);
    }

}



