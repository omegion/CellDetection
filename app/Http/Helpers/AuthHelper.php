<?php
namespace App\Http\Helpers;

class AuthHelper
{

    private static function auth()
    {

        if (auth('user')->check()) {

            $auth = auth('user');
        } else if (auth('admin')->check()) {

            $auth = auth('admin');

        }else{

            $auth = null;

        }
       
        return $auth;

    }

    private static function auth_name()
    {

        if (auth('user')->check()) {
            $name = 'user';
        }else if (auth('admin')->check()) {
            $name = 'admin';
        }else{
            $name = 'guest';
        }
       
        return $name;

    }

    public static function user()
    {
        $auth = self::auth();

        if (!is_null($auth)) {

            return $auth->user();
            
        }

        return null;
    }

    public static function class()
    {
        $auth = self::auth();

        if (!is_null($auth)) {

            return get_class($auth->user());
            
        }

        return null;
    }

    public static function authName()
    {

        return self::auth_name();

    }

    public static function is($given_auth)
    {
        $auth_name = self::auth_name();

        if ($auth_name == $given_auth) {

            return true;
            
        }

        return false;
    }

}