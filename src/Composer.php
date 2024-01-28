<?php

declare(strict_types=1);

namespace Exewen\Utils;

use Illuminate\Support\Collection;

class Composer
{

    /**
     * @var array
     */
    private static array $extra = [];

    /**
     * 获取composer.lock extra合并信息
     * @param string|null $key
     * @return array
     */
    public static function getPackageExtra(string $key = null): array
    {
        if (!self::$extra) {
            self::getExtraContent();        // 读取composer.lock 依赖exewen所有项目的extra信息
            self::getSelfExtraContent();    // 读取composer.json 当前项目的extra信息
        }

        if ($key === null) {
            return self::$extra;
        }

        $extra = [];
        foreach (self::$extra ?? [] as $config) {
            foreach ($config ?? [] as $configKey => $item) {
                if ($key === $configKey && $item) {
                    foreach ($item as $k => $v) {
                        if (is_array($v)) {
                            $extra[$k] = array_merge($extra[$k] ?? [], $v);
                        } else {
                            $extra[$k][] = $v;
                        }
                    }
                }
            }
        }

        return $extra;
    }

    /**
     * 获取composer.lock 所有项目的extra信息
     * @return void
     */
    public static function getExtraContent()
    {
        $path = self::composerLockFile();
        if (!$path) {
            throw new \RuntimeException('composer.lock not found.');
        }
        $content = new Collection(json_decode(file_get_contents($path), true));
        $packagesDev = $content->offsetGet('packages-dev') ?? [];
        $packages = $content->offsetGet('packages') ?? [];
        foreach (array_merge($packages, $packagesDev) as $package) {
            $packageName = '';
            foreach ($package ?? [] as $key => $value) {
                if ($key === 'name') {
                    $packageName = $value;
                    continue;
                }

                if ($key == 'extra') {
                    $packageName && self::$extra[$packageName] = $value;
                }
            }
        }
    }

    public static function getSelfExtraContent()
    {
        $path = self::composerFile();
        if (!$path) {
            throw new \RuntimeException('composer.json not found.');
        }
        $selfComposer = json_decode(file_get_contents($path), true);
        $packageName = $selfComposer['name'] ?? '';
        $extraValue = $selfComposer['extra'] ?? [];
        self::$extra[$packageName] = $extraValue;
    }

    /**
     * 查找composer.lock路径
     * @return string
     */
    public static function composerLockFile(): string
    {
        $path = '';
        if (is_readable(BASE_PATH_PKG . '/composer.lock')) {
            $path = BASE_PATH_PKG . '/composer.lock';
        }
        return $path;
    }

    public static function composerFile(): string
    {
        $path = '';
        if (is_readable(BASE_PATH_PKG . '/composer.json')) {
            $path = BASE_PATH_PKG . '/composer.json';
        }
        return $path;
    }

}
