<?php

namespace monolitum\auth;

use monolitum\core\Active;
use monolitum\core\Find;
use monolitum\core\GlobalContext;
use monolitum\core\panic\DevPanic;
use monolitum\core\Renderable_Node;
use monolitum\database\Manager_DB;
use monolitum\entity\Entities_Manager;
use monolitum\entity\Entity;
use monolitum\entity\Model;

class Auth_Manager extends Renderable_Node implements Active
{

    private $entityClass;

    private $usernameAttr;
    private $passwordAttr;
    private $enabledAttr;

    /**
     * @var Entities_Manager
     */
    private $entitiesManager;

    /**
     * @var Model
     */
    private $entityModel;

    /**
     * @var array<string, callable>
     */
    private $permissions = [];

    /**
     * Logged user set by requireLogin
     * @var Entity
     */
    private $user;

    /**
     * @var Manager_DB
     */
    private $managerDB;

    public function __construct($builder = null)
    {
        parent::__construct($builder);
    }

    public function setUserModel($entityClass, $usernameAttr, $passwordAttr, $enabledAttr)
    {
        $this->entityClass = $entityClass;
        $this->usernameAttr = $usernameAttr;
        $this->passwordAttr = $passwordAttr;
        $this->enabledAttr = $enabledAttr;

        $this->entityModel = $this->entitiesManager->getModel($this->entityClass);

    }

    /**
     * @param callable $builder
     * @return Auth_Manager
     */
    public static function add($builder)
    {
        $fc = new Auth_Manager($builder);
        GlobalContext::add($fc);
        return $fc;
    }

    /**
     * @param string $permissionId
     * @param callable $predicate
     * @return void
     */
    public function permission($permissionId, $predicate)
    {
        $this->permissions[$permissionId] = $predicate;
    }

    /**
     * @param Entity $user
     * @param string $plainPassword
     * @return $this
     */
    public function changePassword($user, $plainPassword)
    {
        // TODO hash
        $user->setValue($this->passwordAttr, password_hash(
            $plainPassword,
            PASSWORD_DEFAULT,
            array('cost' => 9)
        ));
        return $this;
    }

    protected function buildNode()
    {
        $this->entitiesManager = Find::sync(Entities_Manager::class);
        $this->managerDB = Find::sync(Manager_DB::class);

        parent::buildNode(); // TODO: Change the autogenerated stub
    }

    public function logIn($username, $password)
    {

        $userIterable = $this->managerDB->newQuery($this->entityModel)
            ->filter([
                $this->usernameAttr => $username,
                $this->enabledAttr => true
            ])
            ->store()
            ->execute();

        /** @var Entity|null $user */
        $this->user = $userIterable->first();

        if($this->user == null){
            return false;
        }else{

            $userPassword = $this->user->getString($this->passwordAttr);

            if($userPassword === null)
                return false;

            if(!password_verify($password, $userPassword))
                return false;

            if(! session_id())
                session_start();

            $_SESSION['username'] = $this->user->getString($this->usernameAttr);

            return true;
        }

    }

    private function requireLogin()
    {
        if($this->user == null){

            if(! session_id())
                throw new AuthPanic_NoUser();
//                session_start();

            if(!isset($_SESSION['username']) || $_SESSION['username'] == null)
                throw new AuthPanic_NoUser();

            $userIterable = $this->managerDB->newQuery($this->entityModel)
                ->filter([
                    $this->usernameAttr => $_SESSION['username']
                ])
                ->store()
                ->execute();

            /** @var Entity|null $user */
            $this->user = $userIterable->first();

            if($this->user == null)
                $_SESSION['username'] = null;
        }

    }

    private function getUser()
    {
        if($this->user == null){

            if(! session_id())
                session_start();

            if(!isset($_SESSION['username']) || $_SESSION['username'] == null)
                return null;

            $userIterable = $this->managerDB->newQuery($this->entityModel)
                ->filter([
                    $this->usernameAttr => $_SESSION['username']
                ])
                ->store()
                ->execute();

            /** @var Entity|null $user */
            $this->user = $userIterable->first();

        }

        return $this->user;
    }

    public function requirePermission($permissionId)
    {
        $this->requireLogin();
        $user = $this->getUser();

        if(array_key_exists($permissionId, $this->permissions)){

            $callable = $this->permissions[$permissionId];

            if(!$callable($user))
                throw new AuthPanic_NoPermissions();

        }else{
            throw new DevPanic("Permission '" . $permissionId . "' is not defined.");
        }

    }

    /**
     * @param string $permissionId
     * @return bool
     */
    public function hasPermission($permissionId)
    {
        $this->requireLogin();
        $user = $this->getUser();

        if(array_key_exists($permissionId, $this->permissions)){

            $callable = $this->permissions[$permissionId];

            if(!$callable($user))
                return false;

        }else{
            return false;
        }

        return true;
    }

    private function logout()
    {
        if(session_id()){
            session_destroy();
        }
//            session_start();
//        $_SESSION['username'] = null;

    }

    private function isLoggedIn()
    {
        if(! session_id())
            return false;

        if(!isset($_SESSION['username']) || $_SESSION['username'] == null)
            return false;

        return true;
    }

    public static function go_requireLogin()
    {
        /** @var Auth_Manager $manager */
        $manager = Find::sync(Auth_Manager::class);
        $manager->requireLogin();
    }

    /**
     * @param string $permissionId
     * @return void
     */
    public static function go_requirePermission($permissionId)
    {
        /** @var Auth_Manager $manager */
        $manager = Find::sync(Auth_Manager::class);
        $manager->requirePermission($permissionId);
    }

    public static function go_logout()
    {
        /** @var Auth_Manager $manager */
        $manager = Find::sync(Auth_Manager::class);
        $manager->logout();
    }

    public static function go_isLoggedIn()
    {
        /** @var Auth_Manager $manager */
        $manager = Find::sync(Auth_Manager::class);
        return $manager->isLoggedIn();
    }

}