<?php

namespace App\Domain\Proxy;

class ProxyMesh
{
    public static function getProxy(): string
    {
        $proxy = [
            'http://',
            config('proxymesh.username') . ':',
            config('proxymesh.password') . '@',
            self::getRandomProxyServer(false) . ':',
            config('proxymesh.port'),
        ];

        return implode($proxy);
    }

    /**
     * @param boolean $useOpenProxy
     * @param string $exclude
     * @return string
     */
    public static function getRandomProxyServer($useOpenProxy = true, $exclude = null): string
    {
        $servers = config('proxymesh.servers');

        if ($useOpenProxy) {
            // Temporarily disable open proxy
            $servers[] = 'open.proxymesh.com';
        }

        if (!is_null($exclude) && ($key = array_search($exclude, $servers)) !== false) {
            unset($servers[$key]);
        }

        return $servers[mt_rand(0, count($servers) - 1)];
    }
}
