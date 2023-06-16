<?php

namespace Akyos\PuppeteerSDK\Security\Voter;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class AccessVoter extends Voter
{
    const CAN_PUPPETEER_ACCESS = 'CAN_PUPPETEER_ACCESS';
    const CAN_PUPPETEER_ACCESS_URL = 'CAN_PUPPETEER_ACCESS_URL';

    private $container;
    private $requestStack;

    public function __construct(
        ContainerInterface $container,
        RequestStack $requestStack
    ){
        $this->container = $container;
        $this->requestStack = $requestStack;
    }

    protected function supports($attribute, $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::CAN_PUPPETEER_ACCESS, self::CAN_PUPPETEER_ACCESS_URL]);
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        $now = new \DateTime();
        $token = $this->container->getParameter('token');
        $tokenToValidate = $this->requestStack->getCurrentRequest()->get('token');
        if (!$tokenToValidate) {
            return false;
        }

        $validity_time = $token['validity_time'];
        $key = $token['key'];
        $algo = $token['algo'];

        switch ($attribute) {
            case self::CAN_PUPPETEER_ACCESS:
                $payload = JWT::decode($tokenToValidate, new Key($key, $algo));
                $date = new \DateTime($payload->date->date, new \DateTimeZone($payload->date->timezone));
                return $date->add(new \DateInterval("PT{$validity_time}S")) > $now;
            case self::CAN_PUPPETEER_ACCESS_URL:
                return $key === $tokenToValidate;
        }

        return false;
    }
}
