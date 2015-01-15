<?php

namespace Gaskata\Utils;

class Authentication
{
    private $container;
    private $entityManager;

    // Yes, I want the service container here. I can't really understand how
    // it's possible to do without.
    public function __construct($container)
    {
        $this->container         = $container;
    }

    public function athenticateFromArray($data = array())
    {

        /* We *have* to have both an ident (user_ident or email) 
         * an a key (password or token). Without, we fail.
         * 
         * This is the back-end, so what we can discuss is wether to always
         * return a 404 on *everything* or diversify with 401, 403 and so on.
         * Then the front end(s) have to make sure they just return "Wrong user
         * ident or password" whatever happens. We do not want script kiddies
         * to find user idents based on error on login.
         **/
        if (!(
               (isset($data['user_ident']) || isset($data['email']))
            && (isset($data['password']) || isset($data['auth_token']))
            )) {
            return null;
        }

        $em = $this->getEntityManager();
        $entity = $em->getRepository('Gaskata:AuthUser')->findOneBy($data);

        if (!$entity) {
            return false;
        }

        // In case of password login, we'll make a new auth-token.
        if (isset($data['password'])) {
            $entity->createAuthToken();
            $em->persist($entity);
            $em->flush($entity);
        }

        $resp_data = array(
            'email' => $entity->getEmail(),
            'auth_token' => $entity->getAuthToken(),
        );

        return $resp_data;
    }

    private function getEntityManager()
    {
        if (!$this->entityManager) {
            $this->entityManager
                = $this->container->get('doctrine')->getManager();
        }
        return $this->entityManager;
    }

}

