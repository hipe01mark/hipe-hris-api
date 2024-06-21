<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Error Message Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used by the Laravel Responder package.
    | When it generates error responses, it will search the messages array
    | below for any key matching the given error code for the response.
    |
    */

    'unauthenticated' => 'You are not authenticated for this request.',
    'unauthorized' => 'You are not authorized for this request.',
    'page_not_found' => 'The requested page does not exist.',
    'relation_not_found' => 'The requested relation does not exist.',
    'validation_failed' => 'The given data failed to pass validation.',

    /*
    |--------------------------------------------------------------------------
    | Custom Error Message Language Lines for UI/UX
    |--------------------------------------------------------------------------
    |
    | The following language lines are used for UI/UX.
    |
    */

    'invalid_login' => 'These credentials do not match our records.',
    'invalid_token' => 'The password reset token has already been used/expired!',
    'exceed_login_attempts' => 'Your account has been locked due to 10 failed attempts. It will be unlocked after 10 minutes. Recommend to use forgot password.',


    /*
    |--------------------------------------------------------------------------
    | HTTP Error Message Language Lines for UI/UX
    |--------------------------------------------------------------------------
    |
    | The following language lines are used for UI/UX.
    |
    */
    'server_error' => "Something went wrong. We'll back in no time",
    'email_not_verified' => 'Your email address is not verified.',

];
