<?php
namespace VersionKit;
use VersionKit\VersionProvider;
use InvalidArgumentException;

/**
 * Version class to handle version conversions:
 *
 *   - 5.3 => php-5.3.29 (get the latest patched version)
 *   - 5.3.29 => php-5.3.29
 *   - php-5.4 => php-5.4.26
 *   - hhvm-3.3 => hhvm-3.3
 */
class Version implements VersionProvider
{

    /**
     * @var string version string
     */
    public $version;


    /**
     * @var string the original version string (which is set by user)
     */
    protected $originalVersionString;

    public $majorVersion;

    public $minorVersion;

    public $patchVersion;

    /**
     * @var string dist name
     */
    public $distName;

    /**
     *
     *
     * @param $version string version string
     * @param $distName the distribution name
     */
    public function __construct($version, $distName = null)
    {
        $this->setVersion($version, $distName);
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


    /**
     * Parse the version string and set the dist name if available
     *
     * @param $version string version string
     * @param $distName the distribution name
     */
    public function setVersion($version, $distName = null)
    {
        $this->originalVersionString = $version;
        if ($ret = $this->parseDistName($version)) {
            list($distName, $version) = $ret;
        }
        list($major, $minor, $patch) = $this->parseVersion($version);
        $this->majorVersion = $major;
        $this->minorVersion = $minor;
        $this->patchVersion = $patch;
    }


    public function getPatchVersion() {
        return $this->patchVersion;
    }

    public function getMinorVersion()
    {
        return $this->minorVersion;
    }

    public function getMajorVersion()
    {
        return $this->majorVersion;
    }

    public function getVersion()
    {
        return join('.', array($this->majorVersion, $this->minorVersion, $this->patchVersion));
    }

    public function getDistName()
    {
        return $this->distName;
    }

    public function compare(VersionProvider $b)
    {
        return version_compare($a->getVersion(), $b->getVersion());
    }

    /**
     * @return string the version string, php-5.3.29, php-5.4.2 without prefix
     */
    public function getCanonicalizedVersionName()
    {
        return $this->distName . '-' . $this->getVersion();
    }

    public function __toString()
    {
        return $this->getCanonicalizedVersionName();
    }

}






