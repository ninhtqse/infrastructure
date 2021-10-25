<?php

namespace Infrastructure\Auth;

use Infrastructure\Auth\Exceptions\InvalidCredentialsException;
use Infrastructure\Exceptions as CustomException;
use Api\Users\Repositories\UserRepository;
use Illuminate\Foundation\Application;

class LoginProxy
{
    const REFRESH_TOKEN = 'refreshToken';

    // private $apiConsumer;

    private $auth;

    private $cookie;

    private $db;

    private $request;

    private $userRepository;

    public function __construct(
        Application $app,
        UserRepository $userRepository
    ) {
        $this->userRepository = $userRepository;

        $this->apiConsumer  = $app->make('apiconsumer');
        $this->auth         = $app->make('auth');
        $this->cookie       = $app->make('cookie');
        $this->db           = $app->make('db');
        $this->request      = $app->make('request');
    }

    /**
     * Attempt to create an access token using user credentials
     *
     * @param string $taxCode
     * @param string $email
     * @param string $password
     */
    public function attemptLogin($id, $password)
    {
        return $this->proxy('password', [
            'username' => $id,
            'password' => $password
        ]);
    }

    /**
     * Attempt to refresh the access token used a refresh token that 
     * has been saved in a cookie
     */
    public function attemptRefresh($refreshToken)
    {
        // $refreshToken = $this->request->cookie(self::REFRESH_TOKEN);

        return $this->proxy('refresh_token', [
            'refresh_token' => $refreshToken
        ]);
    }

    /**
     * Proxy a request to the OAuth server.
     * 
     * @param string $grantType what type of grant type should be proxied
     * @param array $data the data to send to the server
     */
    public function proxy($grantType, array $data = [])
    {
        // \Route::post('/oauth/token', [
        //     'uses' => '\Laravel\Passport\Http\Controllers\AccessTokenController@issueToken',
        //     // 'middleware' => 'throttle:60,1',
        // ]);
        $data = array_merge($data, [
            'client_id'     => \config('config.PASSWORD_CLIENT_ID'),
            'client_secret' => \config('config.PASSWORD_CLIENT_SECRET'),
            'grant_type'    => $grantType
        ]);
        $response = $this->apiConsumer->post('/oauth/token', $data);

        if (!$response->isSuccessful()) {
            throw new CustomException\GeneralException('IWE012');
            // throw new InvalidCredentialsException();
        }
        $data = json_decode($response->getContent());
        if(@$data->status == 'error') {
            throw new CustomException\GeneralException('IWE012');
        }
        // Create a refresh token cookie
        // $this->cookie->queue(
        //     self::REFRESH_TOKEN,
        //     $data->refresh_token,
        //     864000, // 10 days
        //     null,
        //     null,
        //     false,
        //     true // HttpOnly
        // );

        return [
            'access_token'  => $data->access_token,
            'expires_in'    => $data->expires_in,
            'refresh_token' => $data->refresh_token,
        ];
    }

    /**
     * Logs out the user. We revoke access token and refresh token. 
     * Also instruct the client to forget the refresh cookie.
     */
    public function logout()
    {
        $accessToken = $this->auth->user()->token();

        $refreshToken = $this->db
            ->table('oauth_refresh_tokens')
            ->where('access_token_id', $accessToken->id)
            ->update([
                'revoked' => true
            ]);

        $accessToken->revoke();

        // $this->cookie->queue($this->cookie->forget(self::REFRESH_TOKEN));
    }
}
