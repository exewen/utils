<?php
declare(strict_types=1);

namespace Exewen\Utils\Contract;


/**
 * 容器接口类 PSR版本不兼容 用此替换
 */
interface ContainerInterface
{
    /**
     * 获取实例
     * @param string $id
     * @return mixed
     */
    public function get(string $id);

    /**
     * 是否存在实例
     * @param string $id
     * @return mixed
     */
    public function has(string $id): bool;

    /**
     * 绑定实例或者闭包
     * @param string $abstract
     * @param $concrete
     * @return mixed
     */
    public function bind(string $abstract, $concrete = null);

    /**
     * 绑定单例
     * @param string $abstract
     * @param $concrete
     * @return mixed
     */
    public function singleton(string $abstract, $concrete = null);

}
