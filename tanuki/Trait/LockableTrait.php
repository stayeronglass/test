<?php
namespace tanuki;

/**
 *
 * Trait LockableTrait
 *
 * Некий функционал не позволяющий иметь 2 запущенных одноверменно таска
 */
trait LockableTrait{

    public function lock(){

        return true;
    }

};