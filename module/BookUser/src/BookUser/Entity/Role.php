<?php
namespace BookUser\Entity;

use BjyAuthorize\Acl\HierarchicalRoleInterface;

class Role implements HierarchicalRoleInterface
{
    /**
     * @var int
     */
    protected $id;
    /**
     * @var string
     */
    protected $roleId;
    /**
     * @var Role
     */
    protected $parent;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getRoleId()
    {
        return $this->roleId;
    }

    /**
     * @param $roleId
     */
    public function setRoleId($roleId)
    {
        $this->roleId = (string) $roleId;
    }

    /**
     * @return Role
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param Role $parent
     */
    public function setParent(Role $parent)
    {
        $this->parent = $parent;
    }
}