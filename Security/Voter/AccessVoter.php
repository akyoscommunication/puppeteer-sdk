<?php

namespace Akyos\PuppeteerSDK\Security\Voter;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class AccessVoter extends Voter
{
    const CAN_PUPPETEER_ACCESS = 'CAN_PUPPETEER_ACCESS';

    private $parameterBag;
    private $requestStack;

    public function __construct(
        ParameterBagInterface $parameterBag,
        RequestStack $requestStack
    ){
        $this->parameterBag = $parameterBag;
        $this->requestStack = $requestStack;
    }

    protected function supports($attribute, $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::CAN_PUPPETEER_ACCESS]);
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        $now = new \DateTime();
        $validity_time = $this->parameterBag->get('puppeteer_sdk.token.validity_time');
        $key = $this->parameterBag->get('puppeteer_sdk.token.key');
        $algo = $this->parameterBag->get('puppeteer_sdk.token.algo');

        $payload = JWT::decode($this->requestStack->getCurrentRequest()->get('token'), new Key($key, $algo));

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::CAN_PUPPETEER_ACCESS:
                return $payload->date->add(new \DateInterval("P{$validity_time}S")) < $now;
        }

        return false;
    }
}
