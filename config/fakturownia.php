<?php

return [

    /*
    |--------------------------------------------------------------------------
    | API token
    |--------------------------------------------------------------------------
    |
    | The Fakturownia API authorization code
    | (Settings -> Account settings -> Integration -> API authorization code).
    |
    */

    'token' => env('FAKTUROWNIA_TOKEN', ''),

    /*
    |--------------------------------------------------------------------------
    | Account subdomain
    |--------------------------------------------------------------------------
    |
    | Just the subdomain part of https://{domain}.fakturownia.pl.
    |
    */

    'domain' => env('FAKTUROWNIA_DOMAIN', ''),

    /*
    |--------------------------------------------------------------------------
    | Optional defaults
    |--------------------------------------------------------------------------
    */

    'department_id' => env('FAKTUROWNIA_DEPARTMENT_ID'),

    'place' => env('FAKTUROWNIA_PLACE', ''),

];
