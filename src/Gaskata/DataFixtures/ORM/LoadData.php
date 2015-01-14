<?php

namespace Gaskata\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Gaskata\Entity\AuthUser as AuthUser;

class LoadUserData implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $authuser = new AuthUser();
        $authuser->setUserIdent('gaban');
        $authuser->setEmail('gaban@nte');
        $authuser->setPassword('test');

        $manager->persist($authuser);
        $manager->flush();
    }
}
