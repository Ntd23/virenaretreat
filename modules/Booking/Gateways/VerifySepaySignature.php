<?php
namespace Modules\Booking\Gateways;

class VerifySepaySignature
{
    protected $publicKey = "-----BEGIN PUBLIC KEY-----\n" .
        "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA43b0YkO8A81+fLdTYnjz\n" .
        "0Dj0TeS0QJYebk7QsFeCL47yk1pMrheYKG+vIyuZvc50ZSFUwHDTr20zndxXySCY\n" .
        "r9P7cfPhG6pzgx1FbIsnl7SfUwICFnyNryzdezR/YwwKbmH0meThofLbOngBWmTH\n" .
        "pItU/ufUQprwBjrQcMfqXm8EAeYmY4oQkMEgp4lUcis0LLFscSnXGEAPZrSC7xV+\n" .
        "Xp3dNxvYHM6c7G6tFMkrrYhBLzBUBIvGK3T39YE7WmEHDPhoXKt4H9CoM0k7XoL1\n" .
        "XKOQryqIw9P+uSzTy/pKIIYmHtlAtiga6AqqXJAnnkbI3wtABFEzIWx3XOp2IEVU\n" .
        "FQIDAQAB\n" .
        "-----END PUBLIC KEY-----";

    public function __invoke(array $data): bool
    {
        $dataToVerify = "{$data['access_token']}.{$data['state']}";
        $signature = base64_decode($data['signature']);

        return openssl_verify(
            $dataToVerify,
            $signature,
            openssl_pkey_get_public($this->publicKey),
            OPENSSL_ALGO_SHA256
        ) === 1;
    }
}
