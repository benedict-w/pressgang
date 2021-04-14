<?php

namespace PressGang;

class Flash {

    /**
     * session
     */
    protected static function session() {
        if(!session_id()) {
            session_start();
        }
    }

    /**
     * get
     *
     * @param $key
     * @return mixed
     */
    public static function get($key) {

        self::session();

        return $_SESSION['flash'][$key] ?? null;
    }

    /**
     * add
     *
     * @param $key
     * @param $value
     * @return mixed
     */
    public static function add($key, $value) {

        self::session();

        return $_SESSION['flash'][$key] = $value;
    }

    /**
     * clear
     *
     * clear session flash items
     */
    public static function clear() {
        self::session();
        if(isset($_SESSION['flash'])) {
            unset($_SESSION['flash']);
        }
    }
}
