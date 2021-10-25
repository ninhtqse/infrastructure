<?php


namespace Infrastructure\Libraries;

use Infrastructure\Exceptions;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\ValidationData;

class Jwt
{
    private $token;
    private $tokenKey;
    private $expirationTime;
    public function __construct() {
        $this->tokenKey = \Config('config.token.key');
        $this->expirationTime          = time() + (int) \Config('config.token.expiration_time');
    }

    //tao token
    public function genToken($userInfo = array())
    {
        $signer = new Sha256();
        $token = (new Builder())->setIssuer('https://....vn/') // Configures the issuer (iss claim)
        // ->setIssuedAt($time) // Configures the time that the token was issue (iat claim)
        // ->setNotBefore($time) // Configures the time that the token can be used (nbf claim)
        ->setExpiration($this->expirationTime) // Configures the expiration time of the token (exp claim)
        ->set('user_info', $userInfo) // user info
        ->sign($signer, $this->tokenKey) // creates a signature using "testing" as key
        ->getToken(); // Retrieves the generated token
        return $token;
    }


    //verify token
    public function verifyToken($token)
    {
        try{
            $token = (new Parser())->parse((string) $token);
        } catch(\Exception $e) {
            throw new Exceptions\GeneralException('IWE032');
        }
        $data = new ValidationData(); // It will use the current time to validate (iat, nbf and exp)
        $data->setCurrentTime(time());
        //validate
        if(!$token->validate($data)) {
            throw new Exceptions\GeneralException('IWE032');
        }
        //verify
        $signer = new Sha256();
        if(!$token->verify($signer, $this->tokenKey)) {
            throw new Exceptions\GeneralException('IWE032');
        }
        return $token->getClaim('user_info');
    }
}
