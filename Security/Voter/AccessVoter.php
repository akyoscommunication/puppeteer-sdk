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
        return in_array($attribute, [self::CAN_PUPPETEER_ACCESS]);
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        $now = new \DateTime();
        $token = $this->container->getParameter('token');
        $validity_time = $token['validity_time'];
        $key = $token['key'];
        $algo = $token['algo'];

        $payload = JWT::decode($this->requestStack->getCurrentRequest()->get('token'), new Key($key, $algo));
        $date = new \DateTime($payload->date->date, new \DateTimeZone($payload->date->timezone));

        switch ($attribute) {
            case self::CAN_PUPPETEER_ACCESS:
                return $date->add(new \DateInterval("PT{$validity_time}S")) > $now;
        }

        return false;
    }
}
