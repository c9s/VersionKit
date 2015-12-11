<?php
namespace VersionKit;
use InvalidArgumentException;

/**
 * Version class to handle version conversions:
 *
 *   - 5.3 => php-5.3.29 (get the latest patched version)
 *   - 5.3.29 => php-5.3.29
 *   - php-5.4 => php-5.4.26
 *   - hhvm-3.3 => hhvm-3.3
 */
class Version
{

    /**
     * @var string version string
     */
    public $version;

    protected $majorVersion;

    protected $minorVersion;

    protected $patchVersion;

    /**
     * @var string dist name
     */
    public $dist;

    /**
     *
     *
     * @param $version string version string
     * @param $distName the distribution name
     */
    public function __construct($version, $distName = null)
    {
        if ($ret = $this->parseDistName($version)) {
            list($distName, $version) = $ret;
        }

        $this->distName = $distName;
        list($major, $minor, $patch) = $this->parseVersion($version);
        $this->majorVersion = $major;
        $this->minorVersion = $minor;
        $this->patchVersion = $patch;
    }

    /**
     * Parse dist name from version string
     * 
     * @param string $version
     *
     * @return array [dist name, version name]
     */
    protected function parseDistName($version)
    {
        if (preg_match('/^(\w+)-(.*?)$/',$version, $regs)) {
            return array($regs[1], $regs[2]);
        }
        return null;
    }



    /**
     * Strip the dist name from version string.
     *
     * @param string $version
     * @return string version name
     */
    protected function stripDistName($version)
    {
        return preg_replace('#^\w+-#', '', $version);
    }

    protected function parseVersion($version)
    {
        $verison = $this->stripDistName($version);
        $p = explode('.', $version);
        $ret= array(intval($p[0]));
        if (isset($p[1])) {
            $ret[] = intval($p[1]);
        } else {
            $ret[] = null;
        }
        if (isset($p[2])) {
            $ret[] = intval($p[2]);
        } else {
            $ret[] = null;
        }
        return $ret;
    }


    public function setVersion($version)
    {
        $verison = $this->stripDistName($version);
        $p = explode('.', $version);
        $this->majorVersion = intval($p[0]);
        if (isset($p[1])) {
            $this->minorVersion = intval($p[1]);
        }

        if (isset($p[2])) {
            $this->patchVersion = intval($p[2]);
        }
        $this->version = $version;
    }


    public function getPatchVersion() {
        return $this->patchVersion;
    }

    public function getMinorVersion() {
        return $this->minorVersion;
    }

    public function getMajorVersion() {
        return $this->majorVersion;
    }

    public function getVersion() {
        return $this->version;
    }

    public function getDist() {
        return $this->dist;
    }

    public function compare($b) {
        return version_compare($a->getVersion(), $b->getVersion());
    }

    public function upgradePatchVersion(array $availableVersions) {
        $this->version = self::findLatestPatchVersion($this->getVersion(), $availableVersions);
    }

    public static function hasPatchVersion($version) {
        $va = explode('.', $version);
        return count($va) >= 3;
    }

    public static function findLatestPatchVersion($currentVersion, array $versions) {
        // Trim 5.4.29 to 5.4
        $va = explode('.', $currentVersion);
        if (count($va) == 3) {
            list($cMajor, $cMinor, $cPatch) = $va;
        } elseif(count($va) == 2) {
            list($cMajor, $cMinor) = $va;
            $cPatch = 0;
        }
        foreach ($versions as $version) {
            list($major, $minor, $patch) = explode('.', $version);
            if ($major == $cMajor && $minor == $cMinor && $patch > $cPatch) {
                $cPatch = $patch;
            }
        }
        return join('.', array($cMajor, $cMinor, $cPatch));
    }

    /**
     * @return string the version string, php-5.3.29, php-5.4.2 without prefix
     */
    public function getCanonicalizedVersionName() {
        return $this->dist . '-' . $this->version;
    }

    public function __toString() {
        return $this->getCanonicalizedVersionName();
    }

}






