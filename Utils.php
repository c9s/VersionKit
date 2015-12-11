<?php
namespace VersionKit;

class Utils
{

    public static function hasPatchVersion($version)
    {
        $va = explode('.', $version);
        return count($va) >= 3;
    }
}





