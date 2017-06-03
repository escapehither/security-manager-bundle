<?php
namespace StarterKit\SecurityManagerBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use StarterKit\CrudBundle\Entity\Resource as BaseResource;
//use Doctrine\ORM\Mapping as ORM;

/**
 * This file is part of the Genia package.
 * (c) Georden Gaël LOUZAYADIO
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * Date: 06/03/17
 * Time: 19:49
 */

/**
 * @UniqueEntity(fields="email", message="Email already taken")
 * @UniqueEntity(fields="username", message="Username already taken")
 */
class User extends BaseResource implements UserInterface, UserAccountInterface
{

    /**
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    protected $email;

    /**
     * @Assert\NotBlank()
     */
    protected $username;

    /**
     * The encoded password
     */
    protected $password;

    /**
     * A non-persisted field that's used to create the encoded password.
     *
     *
     */
    /**
     * @var string
     * @Assert\NotBlank(groups={"Registration"})
     * @Assert\Length(max=4096)
     */
    protected $plainPassword;

    /**
     *@var json_array
     */
    protected $roles = [];
    public function __construct()
    {

    }

    public function getUsername()
    {
        return $this->email;
    }

    public function getRoles()
    {
        $roles = $this->roles;
        // If the user have no role at least he will have the Role User
        if (!in_array('ROLE_USER', $roles)) {
            $roles[] = 'ROLE_USER';
        }
        return $roles;
    }

    public function getPassword()
    {
        return $this->password;
    }


    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }
    public function getEmail()
    {
        return $this->email;
    }


    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }

    function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username) {
        $this->username = $username;
    }


    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password) {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getPlainPassword() {
        return $this->plainPassword;
    }

    /**
     * @param string $plainPassword
     */
    public function setPlainPassword($plainPassword) {
        $this->plainPassword = $plainPassword;
        $this->password = null;
    }

    /**
     * @param mixed $roles
     */
    public function setRoles(array $roles) {
        $this->roles = $roles;
    }






}