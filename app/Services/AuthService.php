<?php
namespace App\Services;

use App\Http\Requests\Api\Auth\LoginRequest;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AuthService
{
    const MAX_ATTEMPTS = 10;
    const SECONDS_LOCKED = 3600;

    CONST PASSWORD_GRANT_CLIENT_ID = 2;
    CONST GRANT_TYPE = 'password';

    public $userRepository;
    protected $client;

    /**
     * User service constructor
     */
    public function __construct()
    {
        $this->client = DB::table('oauth_clients')
            ->where('id', self::PASSWORD_GRANT_CLIENT_ID)
            ->first();
    }

    /**
     * Attempt to login the user with given credentials.
     */
    public function attemptLogin(array $credentials): bool
    {
        return auth()->attempt($credentials);
    }

    
    /**
     * Generate user token
     */
    public function generateAuthToken(): string
    {
        /** @var User $user */
        $user = auth()->user();
        return $user->createToken('hipe-hris-api')->accessToken;
    }

    /**
     * Request oauth token.
     */
    public function getOauthToken(LoginRequest $loginRequest): string
    {
        request()->request->add([
            'username' => $loginRequest->email,
            'password' => $loginRequest->password,
            'grant_type' => self::GRANT_TYPE,
            'client_id' => $this->client->id,
            'client_secret' => $this->client->secret,
            'scope' => ''
        ]);
        
        $proxy = request()->create(
            'oauth/token',
            'POST'
        );
        $token = Route::dispatch($proxy);
        return json_decode($token->getContent(), true);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::lower(request('email')) . '|' . request()->ip();
    }

    /**
     * Record a failed login attempt.
     */
    public function recordFailedAttempt(): void
    {
        RateLimiter::hit($this->throttleKey(), self::SECONDS_LOCKED);
    }

    /**
     * Clear the failed attempts.
     */
    public function clearFailedAttempts(): void
    {
        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     */
    public function isTooManyFailedAttempts(): bool
    {
        return RateLimiter::tooManyAttempts(
            $this->throttleKey(), self::MAX_ATTEMPTS
        ) ? true : false;
    }
}
