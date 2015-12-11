<?php
namespace VersionKit;

class Utils
{

    public static function hasPatchVersion($version)
    {
        $va = explode('.', $version);
        return count($va) >= 3;
    }

    /**
     * Bump a version's patch version to the latest available patch version.
     *
     * @param string $version
     */
    public static function upgradePatchVersion(Version $version)
    {
        // FIXME
        $this->findLatestPatchVersion($version);
    }
}





