<?php

namespace Itoufo\Notifer\Traits;

trait SenderCallback
{
    /**
     * @return callable
     */
    public function getCallback()
    {
        $callback = app('notifer.sender')->getCallback(get_called_class());
        if (! is_callable($callback)) {
            throw new \UnexpectedValueException("The callback isn't callable.");
        }

        return $callback;
    }
}
