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

    /**
     * @var string dist name
     */
    public $distName;

    public $major;

    public $minor;

    public $patch;

    public $stability;


    /**
     *
     *
     * @param $version string version string
     * @param $distName the distribution name
     */
    public function __construct($version, $distName = null, $stability = null)
    {
        $this->setVersion($version, $distName, $stability);
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
        if (preg_match('/^(\w+)-(.*?)(?:-(\w+))?$/',$version, $regs)) {
            return array($regs[1], $regs[2], isset($regs[3]) ? $regs[3] : null);
        }
        return null;
    }

    /**
     * Strip the dist name from version string.
     *
     * @param string $version
     * @return string version name
     */
    protected function stripDistName($versionStr)
    {
        return preg_replace('#^\w+-#', '', $versionStr);
    }

    protected function parseVersion($version)
    {
        $verison = $this->stripDistName($version);

        // -dev, -patch (-p), -alpha (-a), -beta (-b) or -RC


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
    public function setVersion($version, $distName = null, $stability = null)
    {
        $this->originalVersionString = $version;
        if ($ret = $this->parseDistName($version)) {
            list($distName, $version, $stability) = $ret;
        }
        $this->distName = $distName;
        $this->stability = $stability;
        list($major, $minor, $patch) = $this->parseVersion($version);
        $this->major = $major;
        $this->minor = $minor;
        $this->patch = $patch;
    }


    public function getVersion()
    {
        $a = array();
        $a[] = $this->major;
        if ($this->minor !== null) {
            $a[] = $this->minor;
            if ($this->patch !== null) {
                $a[] = $this->patch;
            }
        }
        return join('.', $a);
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
        $a = [];
        if ($this->distName) {
            $a[] = $this->distName;
        }
        $a[] = $this->getVersion();
        if ($this->stability) {
            $a[] = $this->stability;
        }
        return join('-', $a);
    }

    public function __toString()
    {
        return $this->getCanonicalizedVersionName();
    }

}






