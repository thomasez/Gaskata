<?php

namespace Gaskata\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Util\SecureRandom;
use Symfony\Component\Security\Core\Util\StringUtils;

/**
 * AuthUser
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Gaskata\Entity\AuthUserRepository")
 */
class AuthUser
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="user_ident", type="string", length=255, unique=true)
     */
    private $user_ident;

    /**
     * @var string
     *
     * @ORM\Column(name="domain", type="string", length=20)
     */
    private $domain = "default";

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /*
     * @var string
     * No storing of this one. It's just for comparision.
     */
    private $plain_password;

    /**
     * @var string
     *
     * @ORM\Column(name="auth_token", type="string", length=255, nullable=true)
     */
    private $auth_token;

    /**
     * @var string
     *
     * @ORM\Column(name="auth_token_created", type="datetime", nullable=true)
     */
    private $auth_token_created;

    /**
     * @var string
     * No need to set a new user as anything else than ACTIVE, they do register
     * for a reason.
     *
     * @ORM\Column(name="state", type="string", length=20)
     */
    private $state = 'ACTIVE';

    private $states = array('ACTIVE', 'INACTIVE', 'DELETED');

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set user_ident
     *
     * @param string $user_ident
     * @return AuthUser
     */
    public function setUserIdent($user_ident)
    {
        $this->user_ident = $user_ident;

        return $this;
    }

    /**
     * Get userIdent
     *
     * @return string 
     */
    public function getUserIdent()
    {
        return $this->user_ident;
    }

    /**
     * Set domain
     *
     * @param string $domain
     * @return AuthUser
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;

        return $this;
    }

    /**
     * Get domain
     *
     * @return string 
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return AuthUser
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set state
     *
     * @param string $state
     * @return AuthUser
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return string 
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return AuthUser
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the plaintext password
     *
     * @param string $password
     * @return AuthUser
     */
    public function setPlainPassword($plain_password)
    {
        $this->plain_password = $plain_password;

        return $this;
    }

    /*
     * Compare passwords. Probably not the right place, and we don't have 
     * the encode part added yet.
     */
    public function validatePassword()
    {
        if (0 !== strlen($this->passwd) || 0 !== strlen($this->plain_passwd)) {
            return null;
        }
        return StringUtils::equals($this->passwd, $this->plain_passwd);
    }

    /* 
     * This should probably also go into a Util/Service.
     */
    public function createAuthToken()
    {
        $generator = new SecureRandom();
        // TODO: This should be placed somewhere else I guess.
        $random = bin2hex($generator->nextBytes(15));
        $this->auth_token = $random;

        $this->auth_token_created = new \DateTime('now');

    }

    public function validateAuthToken($token)
    {
        if (0 !== strlen($this->auth_token) || 0 !== strlen($token)) {
            return null;
        }
        return StringUtils::equals($this->auth_token, $token);
    }

    /**
     * Get auth_token
     *
     * @return string 
     */
    public function getAuthToken()
    {
        return $this->auth_token;
    }

    /**
     * Get auth_token_created
     *
     * @return \timestamp 
     */
    public function getAuthTokenCreated()
    {
        return $this->auth_token_created;
    }
}
