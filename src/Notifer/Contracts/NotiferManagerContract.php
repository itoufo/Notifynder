<?php

namespace Itoufo\Notifer\Contracts;

use Closure;

/**
 * Interface NotiferManagerContract.
 */
interface NotiferManagerContract
{
    /**
     * @param string|int|\Itoufo\Notifer\Models\NotificationCategory $category
     * @return $this
     */
    public function category($category);

    /**
     * @param array|\Traversable $data
     * @param Closure $callback
     * @return $this
     */
    public function loop($data, Closure $callback);

    /**
     * @return bool
     */
    public function send();

    /**
     * @param bool $force
     * @return \Itoufo\Notifer\Builder\Builder
     */
    public function builder($force = false);

    /**
     * @return SenderManagerContract
     */
    public function sender();

    /**
     * @param string $name
     * @param Closure $sender
     * @return bool
     */
    public function extend($name, Closure $sender);
}
