<?php

namespace app\components;

class DeviceParser
{
  public static function getDeviceName($ua)
  {
    if (!$ua) {
      return 'Unknown';
    }

    $os = DeviceParser::detectOs($ua);
    $browser = DeviceParser::detectBrowser($ua);

    return trim("$os / $browser");
  }

  public static function detectOs($ua)
  {
    return match (true) {
      str_contains($ua, 'Windows NT') => 'Windows',
      str_contains($ua, 'Mac OS X') && !str_contains($ua, 'iPhone') => 'macOS',
      str_contains($ua, 'Android') => 'Android',
      str_contains($ua, 'iPhone') || str_contains($ua, 'iPad') => 'iOS',
      str_contains($ua, 'Linux') => 'Linux',
      default => 'Unknown',
    };
  }

  public static function detectBrowser($ua)
  {
    return match (true) {
      str_contains($ua, 'Edg') => 'Edge',
      str_contains($ua, 'OPR') => 'Opera',
      str_contains($ua, 'YaBrowser') => 'Yandex',
      str_contains($ua, 'Vivaldi') => 'Vivaldi',
      str_contains($ua, 'Firefox') => 'Firefox',
      str_contains($ua, 'Safari') && !str_contains($ua, 'Chrome') => 'Safari',
      str_contains($ua, 'Chrome') => 'Chrome',
      str_contains($ua, 'MSIE') || str_contains($ua, 'Trident') => 'Internet Explorer',
      default => 'Unknown',
    };
  }
}
