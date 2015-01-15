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

        $resp_data = $this->get('app.authentication')->athenticateFromArray($check_arr);

        if (!$resp_data) {
            throw $this->createNotFoundException('Unable to find AuthUser entity.');
        }

        return $this->returnRestData($request, $resp_data);
    }
}
