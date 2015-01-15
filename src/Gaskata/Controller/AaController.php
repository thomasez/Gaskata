<?php

namespace Gaskata\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Gaskata\Entity\AuthUser;
use BisonLab\CommonBundle\Controller\CommonController as CommonController;

/**
 * AA controller.
 * Aka "Authentication and Authorization", the front meat of this system.
 * This is pure rest.
 *
 * @Route("/aa")
 */
class AaController extends CommonController
{

    /**
     * Finds and displays a AuthUser entity.
     *
     * @Route("/auth", name="aa_authenticate")
     * @Method("POST")
     */
    public function authenticateAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $check_arr = array();

        $request->request->get('user_ident') ? $check_arr['user_ident'] = $request->request->get('user_ident') : null ;
        $request->request->get('email') ? $check_arr['email'] = $request->request->get('email') : null ;
        $request->request->get('password') ? $check_arr['password'] = $request->request->get('password') : null ;
        $request->request->get('auth_token') ? $check_arr['auth_token'] = $request->request->get('auth_token') : null ;

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
               (isset($check_arr['user_ident']) || isset($check_arr['email']))
            && (isset($check_arr['password']) || isset($check_arr['auth_token']))
            )) {
            throw $this->createNotFoundException('Unable to find AuthUser entity.');
        }

        $entity = $em->getRepository('Gaskata:AuthUser')->findOneBy($check_arr);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find AuthUser entity.');
        }

        // In case of password login, we'll make a new auth-token.
        if (isset($check_arr['password'])) {
            $entity->createAuthToken();
            $em->persist($entity);
            $em->flush($entity);
        }

        $resp_data = array(
            'email' => $entity->getEmail(),
            'auth_token' => $entity->getAuthToken(),
        );

        return $this->returnRestData($request, $resp_data);
    }
}
