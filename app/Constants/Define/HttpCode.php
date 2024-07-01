<?php
namespace App\Constants\Define;

class HttpCode
{
    CONST OK = 'ok';
    CONST CREATED = 'created';
    CONST UNAUTHENTICATED = 'unauthenticated';
    CONST UNAUTHORIZED = 'unauthorized';
    CONST SERVER_ERROR = 'server_error';
    CONST INVALID_LOGIN = 'invalid_login';
    CONST INVALID_PASSWORD_RESET_TOKEN = 'invalid_password_reset_token';
    CONST VALIDATION_FAILED = 'validation_failed';
    CONST EXCEED_LOGIN_ATTEMPTS = 'exceed_login_attempts';
    CONST EMAIL_NOT_VERIFIED = 'email_not_verified';
}
