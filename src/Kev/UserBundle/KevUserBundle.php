<?php

namespace Kev\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class KevUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
