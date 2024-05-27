<?php
declare(strict_types=1);

namespace Exewen\Utils;

use SplFileInfo;

class FileUtil
{
//    private static string $snapshotPath = BASE_PATH_PKG . "/config/nacos/env";
    private static $snapshotPath = BASE_PATH_PKG . "/config/nacos/env";

    /**
     * 设置配置储存地址
     * @param string $snapshotPath
     * @return string
     */
    public static function setSnapshotPath(string $snapshotPath): string
    {
        return self::$snapshotPath = $snapshotPath;
    }

    /**
     * 保存配置文件
     * @param string $namespaceId
     * @param string $dataId
     * @param string $group
     * @param $dataIdConfig
     * @return void
     */
    public static function saveSnapshot(string $namespaceId, string $dataId, string $group, $dataIdConfig)
    {
        $snapshotFile = self::getSnapshotFile($namespaceId, $dataId, $group);
        @unlink($snapshotFile);
        if (!$dataIdConfig) {
            @unlink($snapshotFile);
        } else {
            $file = new SplFileInfo($snapshotFile);
            if (!is_dir($file->getPath())) {
                mkdir($file->getPath(), 0777, true);
            }
            file_put_contents($snapshotFile, $dataIdConfig);
        }
    }

    /**
     * 获取配置文件地址
     * @param string $namespaceId
     * @param string $dataId
     * @param string $group
     * @return string
     */
    public static function getSnapshotFile(string $namespaceId, string $dataId, string $group = 'DEFAULT_GROUP'): string
    {
        return self::getSnapshotPath() . DIRECTORY_SEPARATOR . $namespaceId . DIRECTORY_SEPARATOR . $group . DIRECTORY_SEPARATOR . $dataId;
    }

    private static function getSnapshotPath(): string
    {
        return self::$snapshotPath;
    }


}