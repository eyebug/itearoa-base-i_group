<?php

class Enum_Login {

    const LOGIN_USER_SAVE_KEY = "liheinfo_123!@#";

    const LOGIN_USER_PASS_KEY = "jAvAname123*";

    const LOGIN_TIMEOUT = 14400;

    const LOGIN_INFO_COOKIE_KEY_AID = 'iadminAid';

    const LOGIN_INFO_COOKIE_KEY_SID = 'iadminSid';

    /**
     * 加密密码
     * ---
     *
     * @param $password 原始密码            
     * @return string 加密后的密码
     */
    public static function getMd5Pass($password) {
        return md5(self::LOGIN_USER_PASS_KEY . md5($password . self::LOGIN_USER_PASS_KEY));
    }
}
?>