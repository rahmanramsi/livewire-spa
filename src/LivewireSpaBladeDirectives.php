<?php

namespace Rahmanramsi\LivewireSpa;

use Illuminate\Support\Str;

class LivewireSpaBladeDirectives
{
  public static function livewireSpaStyles()
  {
    return "<style>#nprogress{pointer-events:none}#nprogress .bar{background:#29d;position:fixed;z-index:1031;top:0;left:0;width:100%;height:2px}#nprogress .peg{display:block;position:absolute;right:0;width:100px;height:100%;box-shadow:0 0 10px #29d,0 0 5px #29d;opacity:1;-webkit-transform:rotate(3deg) translate(0,-4px);-ms-transform:rotate(3deg) translate(0,-4px);transform:rotate(3deg) translate(0,-4px)}#nprogress .spinner{display:block;position:fixed;z-index:1031;top:15px;right:15px}#nprogress .spinner-icon{width:18px;height:18px;box-sizing:border-box;border:solid 2px transparent;border-top-color:#29d;border-left-color:#29d;border-radius:50%;-webkit-animation:nprogress-spinner .4s linear infinite;animation:nprogress-spinner .4s linear infinite}.nprogress-custom-parent{overflow:hidden;position:relative}.nprogress-custom-parent #nprogress .bar,.nprogress-custom-parent #nprogress .spinner{position:absolute}@-webkit-keyframes nprogress-spinner{0%{-webkit-transform:rotate(0)}100%{-webkit-transform:rotate(360deg)}}@keyframes nprogress-spinner{0%{transform:rotate(0)}100%{transform:rotate(360deg)}}</style>";
  }

  public static function livewireSpaScripts()
  {
    $manifest = json_decode(file_get_contents(__DIR__ . '/../dist/manifest.json'), true);
    $versionedFileName = $manifest['/livewire-spa.js'];
    Str::startsWith($versionedFileName, '/') && $versionedFileName = substr($versionedFileName, 1);

    return '<script src="' . route("livewire-spa.assets", $versionedFileName) . '"></script>';
  }
}
