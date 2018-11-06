<?php
use Doctrine\ORM\Mapping as ORM;


require_once (__DIR__ . "/Rol.php");
require_once (__DIR__ . "/Permiso.php");

/**
 * Usuario
 *
 * @ORM\Table(name="usuario", uniqueConstraints={@ORM\UniqueConstraint(name="usuario_username_uindex", columns={"username"}), @ORM\UniqueConstraint(name="usuario_email_uindex", columns={"email"})})
 * @ORM\Entity
 */
class Usuario implements JsonSerializable
{

    public function jsonSerialize() {
        return array(
            'nombre' => $this->firstName,
            'apellido'=> $this->lastName,
            'email' => $this->email,
            'permisos' => $this->permiso,
            'roles' => $this->rol,
            'usuario' =>$this->username,

        );
    }

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=false, unique=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255, nullable=false, unique=true)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=false)
     */
    private $password;

    /**
     * @var bool
     *
     * @ORM\Column(name="activo", type="boolean", nullable=false)
     */
    private $activo = '0';

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=255, nullable=false)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=255, nullable=false)
     */
    private $lastName;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_superuser", type="boolean", nullable=false)
     */
    private $isSuperuser = '0';

    /**
     * @var float
     *
     * @ORM\Column(name="eliminado", type="float", precision=10, scale=0, nullable=false)
     */
    private $eliminado = '0';

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Permiso", inversedBy="usuario")
     * @ORM\JoinTable(name="usuario_permisos",
     *   joinColumns={
     *     @ORM\JoinColumn(name="usuario_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="permiso_id", referencedColumnName="id")
     *   }
     * )
     */
    private $permiso;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Rol", inversedBy="usuario", cascade={"remove", "persist", "refresh"}))
     * @ORM\JoinTable(name="usuario_tiene_rol",
     *   joinColumns={
     *     @ORM\JoinColumn(name="usuario_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="rol_id", referencedColumnName="id")
     *   }
     * )
     */

    private $rol;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->permiso = new \Doctrine\Common\Collections\ArrayCollection();
        $this->rol = new \Doctrine\Common\Collections\ArrayCollection();
    }




    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set email.
     *
     * @param string $email
     *
     * @return Usuario
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set username.
     *
     * @param string $username
     *
     * @return Usuario
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username.
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password.
     *
     * @param string $password
     *
     * @return Usuario
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set activo.
     *
     * @param bool $activo
     *
     * @return Usuario
     */
    public function setActivo($activo)
    {
        $this->activo = $activo;

        return $this;
    }

    /**
     * Get activo.
     *
     * @return bool
     */
    public function getActivo()
    {
        return $this->activo;
    }

    /**
     * Set updatedAt.
     *
     * @param \DateTime|null $updatedAt
     *
     * @return Usuario
     */
    public function setUpdatedAt($updatedAt = null)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt.
     *
     * @return \DateTime|null
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime|null $createdAt
     *
     * @return Usuario
     */
    public function setCreatedAt($createdAt = null)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt.
     *
     * @return \DateTime|null
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set firstName.
     *
     * @param string $firstName
     *
     * @return Usuario
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName.
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName.
     *
     * @param string $lastName
     *
     * @return Usuario
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName.
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set isSuperuser.
     *
     * @param bool $isSuperuser
     *
     * @return Usuario
     */
    public function setIsSuperuser($isSuperuser)
    {
        $this->isSuperuser = $isSuperuser;

        return $this;
    }

    /**
     * Get isSuperuser.
     *
     * @return bool
     */
    public function getIsSuperuser()
    {
        return $this->isSuperuser;
    }

    /**
     * Set eliminado.
     *
     * @param float $eliminado
     *
     * @return Usuario
     */
    public function setEliminado($eliminado)
    {
        $this->eliminado = $eliminado;

        return $this;
    }

    /**
     * Get eliminado.
     *
     * @return float
     */
    public function getEliminado()
    {
        return $this->eliminado;
    }

    /**
     * Add permiso.
     *
     * @param \Usuario $permiso
     *
     * @return Usuario
     */
    public function addPermiso(Permiso $permiso)
    {
        $this->permiso[] = $permiso;

        return $this;
    }

    /**
     * Remove permiso.
     *
     * @param \Usuario $permiso
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removePermiso(Permiso $permiso)
    {
        return $this->permiso->removeElement($permiso);
    }


    public function leaveOnlyThisPermissions($permissionsCollection) {
        $this->permiso->clear();
        $this->permiso = $permissionsCollection;
    }

    public function leaveOnlyThisRoles($rolesCollection) {
        $this->rol->clear();
        $this->rol = $rolesCollection;
    }


    /**
     * Get permiso.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPermiso()
    {
        return $this->permiso;
    }


    /**
     * Add rol.
     *
     * @param \Usuario $rol
     *
     * @return Usuario
     */
    public function addRol(\Rol $rol)
    {
        $this->rol[] = $rol;

        return $this;
    }

    public function hasRol($rol_elem) {
        return $this->rol->contains($rol_elem);
    }

    /**
     * Remove rol.
     *
     * @param \Usuario $rol
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeRol(\Rol $rol)
    {
        return $this->rol->removeElement($rol);
    }

    /**
     * Get rol.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRol()
    {
        return $this->rol;
    }

    /**
     * @param \Permiso $permissionInstance
     * @return bool
     */
    public function hasPermissionInheritFromRol(Permiso $permissionInstance){
        /** @var Rol $rol */
        foreach ($permissionInstance->getRol() as $rol) {
            if($this->getRol()->contains($rol)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param \Permiso $permissionInstance
     * @return bool
     */
    public function hasPermission(Permiso $permissionInstance) {
        if ($this->getIsSuperuser() || $this->getPermiso()->contains($permissionInstance)) {
            return true;
        }
        return $this->hasPermissionInheritFromRol($permissionInstance);

    }


}
