<?php


namespace catchAdmin\jwt\provider\JWT;

use catchAdmin\jwt\Payload;
use Exception;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Ecdsa;
use Lcobucci\JWT\Signer\Ecdsa\Sha256 as ES256;
use Lcobucci\JWT\Signer\Ecdsa\Sha384 as ES384;
use Lcobucci\JWT\Signer\Ecdsa\Sha512 as ES512;
use Lcobucci\JWT\Signer\Hmac\Sha256 as HS256;
use Lcobucci\JWT\Signer\Hmac\Sha384 as HS384;
use Lcobucci\JWT\Signer\Hmac\Sha512 as HS512;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Rsa;
use Lcobucci\JWT\Signer\Rsa\Sha256 as RS256;
use Lcobucci\JWT\Signer\Rsa\Sha384 as RS384;
use Lcobucci\JWT\Signer\Rsa\Sha512 as RS512;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use ReflectionClass;
use catchAdmin\jwt\exception\JWTException;
use catchAdmin\jwt\exception\TokenInvalidException;
use Lcobucci\JWT\Signer;
use think\App;

class Lcobucci extends Provider
{
    /**
     * @var array|string[]
     */
    protected array $signers
        = [
            'HS256' => HS256::class,
            'HS384' => HS384::class,
            'HS512' => HS512::class,
            'RS256' => RS256::class,
            'RS384' => RS384::class,
            'RS512' => RS512::class,
            'ES256' => ES256::class,
            'ES384' => ES384::class,
            'ES512' => ES512::class,
        ];

    /**
     * @var ?Builder
     */
    protected ?Builder $builder;

    /**
     * @var ?Parser
     */
    protected ?Parser $parser;

    /**
     * @var Signer
     */
    protected Signer $signer;

    /**
     * @var Configuration
     */
    protected Configuration $configuration;

    /**
     * @param $algo
     * @param $keys
     * @throws JWTException
     * @throws \ReflectionException
     */
    public function __construct($algo, $keys)
    {
        $this->algo = $algo;

        $this->keys = $keys;

        $this->signer = $this->getSign();

        $key = $this->getSigningKey();

        if (is_array($key)) {
            $this->configuration = Configuration::forAsymmetricSigner($this->getSign(), Key\InMemory::plainText($key[0]), Key\InMemory::base64Encoded($key[1]));
        } else {
            $this->configuration = Configuration::forSymmetricSigner($this->getSign(), Key\InMemory::base64Encoded($key));
        }
    }


    /**
     * @throws JWTException
     */
    public function encode(array $payload): string
    {
        try {
            $builder = $this->configuration->builder();

            foreach ($payload as $key => $val) {
                if (isset(Payload::CLAIMS_MAP[$key])) {
                    $builder->{Payload::CLAIMS_MAP[$key]}($val->getValue(true));
                } else {
                    $builder->withClaim($key, $val->getValue());
                }
            }

            $token = $builder->getToken($this->configuration->signer(), $this->configuration->signingKey());

            return $token->toString();
        } catch (Exception $e) {
            throw new JWTException(
                'Could not create token :'.$e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }

    /**
     * @time 2022年01月13日
     * @param string $token
     * @return array
     * @throws TokenInvalidException
     */
    public function decode(string $token): array
    {
        try {
            $token = $this->configuration->parser()->parse($token);
        } catch (Exception $e) {
            throw new TokenInvalidException('Could not decode token: ' . $e->getMessage(), $e->getCode(), $e);
        }

        try {
            $singed = new SignedWith($this->configuration->signer(), $this->configuration->signingKey());

            $singed->assert($token);
        } catch (\Exception $e) {
            throw new TokenInvalidException('Token Invalid');
        }

        $claims = $token->claims()->all();

        $payload = \app()->make(Payload::class);

        foreach ($claims as $key => $val) {
            if ($claim = $payload->matchClassMap($key)) {
                if ($val instanceof \DateTimeImmutable) {
                    $claims[$key] = new $claim($val->getTimestamp());
                } else {
                    $claims[$key] = new $claim($val);
                }
            }
        }

        return $claims;
    }


    /**
     * @time 2022年01月13日
     * @return bool
     * @throws \ReflectionException
     */
    protected function isAsymmetric(): bool
    {
        $reflect = new ReflectionClass($this->signer);

        return $reflect->isSubclassOf(Rsa::class)
            || $reflect->isSubclassOf(Ecdsa::class);
    }

    /**
     * @time 2022年01月13日
     * @return array|string
     * @throws \ReflectionException
     */
    protected function getSigningKey()
    {
        return $this->isAsymmetric() ? [$this->getPrivateKey(), $this->getPassword()] : $this->getSecret();
    }

    /**
     * @throws JWTException
     */
    protected function getSign()
    {
        if (! isset($this->signers[$this->algo])) {
            throw new JWTException('Cloud not find '.$this->algo.' algo');
        }

        return new $this->signers[$this->algo];
    }
}
