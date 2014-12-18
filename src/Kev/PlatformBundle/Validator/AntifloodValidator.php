<?php
// src/Kev/PlatformBundle/Validator/AntifloodValidator.php

namespace Kev\PlatformBundle\Validator;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Ip;
use Symfony\Component\Validator\ConstraintValidator;

class AntifloodValidator extends ConstraintValidator
{
    private $requestStack;
    private $em;

    public function __construct(RequestStack $requestStack, EntityManagerInterface $em)
    {
        $this->requestStack = $requestStack;
        $this->em = $em;
    }

    public function validate($value, Constraint $constraint)
    {
        $request = $this->requestStack->getCurrentRequest();

        $ip = $request->getClientIp();

        $isFlood = $this->em
            ->getRepository('KevPlatformBundle:Application')
            ->isFlood($ip, 15);

        if ($isFlood){
            $this->context->addViolation($constraint->message);
        }
    }

    /**
     * Only Example
     * @param Ip $ip
     * @param $sec Seconds before add another application
     * @return false
     *
     */
    function isFlood($ip, $sec) {
        return false;
    }

}