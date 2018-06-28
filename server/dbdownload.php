<?php
$dir = __DIR__.DIRECTORY_SEPARATOR;
if (!is_dir($dir."db")) {
    mkdir($dir."db");
    mkdir($dir."db/tmp");
}
exec('rm -r '.$dir.'db/*/ && wget -O '.$dir.'GeoLite2-City.tar.gz http://geolite.maxmind.com/download/geoip/database/GeoLite2-City.tar.gz && tar -xvzf '.$dir.'GeoLite2-City.tar.gz -C '.$dir.'db && rm '.$dir.'GeoLite2-City.tar.gz && wget -O '.$dir.'GeoLite2-ASN.tar.gz http://geolite.maxmind.com/download/geoip/database/GeoLite2-ASN.tar.gz && tar -xvzf '.$dir.'GeoLite2-ASN.tar.gz -C '.$dir.'db && rm '.$dir.'GeoLite2-ASN.tar.gz');
