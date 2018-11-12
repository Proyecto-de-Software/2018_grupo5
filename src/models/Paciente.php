<?php


require_once (__DIR__ . "/Genero.php");
require_once (__DIR__ . "/Localidad.php");
require_once (__DIR__ . "/ObraSocial.php");
require_once (__DIR__ . "/RegionSanitaria.php");
require_once (__DIR__ . "/TipoDocumento.php");
require_once (__DIR__ . "/Partido.php");
use Doctrine\ORM\Mapping as ORM;


/**
 * Paciente
 *
 * @ORM\Table(name="paciente", indexes={@ORM\Index(name="FK_obra_social_id", columns={"obra_social_id"}), @ORM\Index(name="FK_tipo_doc_id", columns={"tipo_doc_id"}), @ORM\Index(name="FK_localidad_id", columns={"localidad_id"}), @ORM\Index(name="FK_genero_id", columns={"genero_id"})})
 * @ORM\Entity
 */
class Paciente implements JsonSerializable
{

    public function jsonSerialize() {
        return array(
            'id' => $this->id,
            'nombre' => $this->getNombre(),
            'apellido'=> $this->getApellido(),
            'numero' => $this->getNumero(),
            'fechaNac' => ($this->fechaNac) ? $this->fechaNac->format('d-m-Y') : null,
            'lugarNac' => $this->getLugarNac(),
            'domicilio' => $this->getDomicilio(),
            'tipo_doc_id' => $this->getTipoDoc(),
            'tieneDocumento' => $this->getTieneDocumento(),
            'tel' => $this->getTel(),
            'nroHistoriaClinica' => $this->getNroHistoriaClinica(),
            'nroCarpeta' => $this->getNroCarpeta(),
            'genero_id' => $this->getGenero(),
            'localidad_id' => $this->getLocalidad(),
            'obra_social_id' => $this->getObraSocial(),
            'partido_id' => $this->getLocalidad() ? $this->getLocalidad()->getPartido()->getId() : null,
            'region_sanitaria_id' => $this->getLocalidad() ? $this->localidad->getPartido()->getRegionSanitaria()->getId() : null
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
     * @var string|null
     *
     * @ORM\Column(name="apellido", type="string", length=255, nullable=true)
     */
    private $apellido;

    /**
     * @var string|null
     *
     * @ORM\Column(name="nombre", type="string", length=255, nullable=true)
     */
    private $nombre;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="fecha_nac", type="date", nullable=true)
     */
    private $fechaNac;

    /**
     * @var string|null
     *
     * @ORM\Column(name="lugar_nac", type="string", length=50, nullable=true)
     */
    private $lugarNac;

    /**
     * @var string|null
     *
     * @ORM\Column(name="domicilio", type="string", length=255, nullable=true)
     */
    private $domicilio;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="tiene_documento", type="boolean", nullable=true)
     */
    private $tieneDocumento = '0';

    /**
     * @var int|null
     *
     * @ORM\Column(name="numero", type="integer", nullable=true)
     */
    private $numero;

    /**
     * @var string|null
     *
     * @ORM\Column(name="tel", type="string", length=255, nullable=true)
     */
    private $tel;

    /**
     * @var int|null
     *
     * @ORM\Column(name="nro_historia_clinica", type="integer", nullable=true)
     */
    private $nroHistoriaClinica;

    /**
     * @var int|null
     *
     * @ORM\Column(name="nro_carpeta", type="integer", nullable=true)
     */
    private $nroCarpeta;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="eliminado", type="boolean", nullable=true)
     */
    private $eliminado = '0';

    /**
     * @var \Genero
     *
     * @ORM\ManyToOne(targetEntity="Genero")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="genero_id", referencedColumnName="id")
     * })
     */
    private $genero;

    /**
     * @var \Localidad
     *
     * @ORM\ManyToOne(targetEntity="Localidad")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="localidad_id", referencedColumnName="id")
     * })
     */
    private $localidad;

    /**
     * @var \ObraSocial
     *
     * @ORM\ManyToOne(targetEntity="ObraSocial")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="obra_social_id", referencedColumnName="id")
     * })
     */
    private $obraSocial;

    /**
     * @var \TipoDocumento
     *
     * @ORM\ManyToOne(targetEntity="TipoDocumento")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="tipo_doc_id", referencedColumnName="id")
     * })
     */
    private $tipoDoc;



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
     * Set apellido.
     *
     * @param string|null $apellido
     *
     * @return Paciente
     */
    public function setApellido($apellido = null)
    {
        $this->apellido = $apellido;

        return $this;
    }

    /**
     * Get apellido.
     *
     * @return string|null
     */
    public function getApellido()
    {
        return $this->apellido;
    }

    /**
     * Set nombre.
     *
     * @param string|null $nombre
     *
     * @return Paciente
     */
    public function setNombre($nombre = null)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre.
     *
     * @return string|null
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set fechaNac.
     *
     * @param \DateTime|null $fechaNac
     *
     * @return Paciente
     */
    public function setFechaNac($fechaNac = null)
    {
        $this->fechaNac = $fechaNac;

        return $this;
    }

    /**
     * Get fechaNac.
     *
     * @return \DateTime|null
     */
    public function getFechaNac()
    {
        return $this->fechaNac;
    }

    /**
     * Set lugarNac.
     *
     * @param string|null $lugarNac
     *
     * @return Paciente
     */
    public function setLugarNac($lugarNac = null)
    {
        $this->lugarNac = $lugarNac;

        return $this;
    }

    /**
     * Get lugarNac.
     *
     * @return string|null
     */
    public function getLugarNac()
    {
        return $this->lugarNac;
    }

    /**
     * Set domicilio.
     *
     * @param string|null $domicilio
     *
     * @return Paciente
     */
    public function setDomicilio($domicilio = null)
    {
        $this->domicilio = $domicilio;

        return $this;
    }

    /**
     * Get domicilio.
     *
     * @return string|null
     */
    public function getDomicilio()
    {
        return $this->domicilio;
    }

    /**
     * Set tieneDocumento.
     *
     * @param bool|null $tieneDocumento
     *
     * @return Paciente
     */
    public function setTieneDocumento($tieneDocumento = null)
    {
        $this->tieneDocumento = $tieneDocumento;

        return $this;
    }

    /**
     * Get tieneDocumento.
     *
     * @return bool|null
     */
    public function getTieneDocumento()
    {
        return $this->tieneDocumento;
    }

    /**
     * Set numero.
     *
     * @param int|null $numero
     *
     * @return Paciente
     */
    public function setNumero($numero = null)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero.
     *
     * @return int|null
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set tel.
     *
     * @param string|null $tel
     *
     * @return Paciente
     */
    public function setTel($tel = null)
    {
        $this->tel = $tel;

        return $this;
    }

    /**
     * Get tel.
     *
     * @return string|null
     */
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * Set nroHistoriaClinica.
     *
     * @param int|null $nroHistoriaClinica
     *
     * @return Paciente
     */
    public function setNroHistoriaClinica($nroHistoriaClinica = null)
    {
        $this->nroHistoriaClinica = $nroHistoriaClinica;

        return $this;
    }

    /**
     * Get nroHistoriaClinica.
     *
     * @return int|null
     */
    public function getNroHistoriaClinica()
    {
        return $this->nroHistoriaClinica;
    }

    /**
     * Set nroCarpeta.
     *
     * @param int|null $nroCarpeta
     *
     * @return Paciente
     */
    public function setNroCarpeta($nroCarpeta = null)
    {
        $this->nroCarpeta = $nroCarpeta;

        return $this;
    }

    /**
     * Get nroCarpeta.
     *
     * @return int|null
     */
    public function getNroCarpeta()
    {
        return $this->nroCarpeta;
    }

    /**
     * Set eliminado.
     *
     * @param bool|null $eliminado
     *
     * @return Paciente
     */
    public function setEliminado($eliminado)
    {
        $this->eliminado = $eliminado;

        return $this;
    }

    /**
     * Get eliminado.
     *
     * @return bool|null
     */
    public function getEliminado()
    {
        return $this->eliminado;
    }

    /**
     * Set genero.
     *
     * @param \Genero|null $genero
     *
     * @return Paciente
     */
    public function setGenero(\Genero $genero = null)
    {
        $this->genero = $genero;

        return $this;
    }

    /**
     * Get genero.
     *
     * @return \Genero|null
     */
    public function getGenero()
    {
        return $this->genero;
    }

    /**
     * Set localidad.
     *
     * @param \Localidad|null $localidad
     *
     * @return Paciente
     */
    public function setLocalidad(\Localidad $localidad = null)
    {
        $this->localidad = $localidad;

        return $this;
    }

    /**
     * Get localidad.
     *
     * @return \Localidad|null
     */
    public function getLocalidad()
    {
        return $this->localidad;
    }

    /**
     * Set obraSocial.
     *
     * @param \ObraSocial|null $obraSocial
     *
     * @return Paciente
     */
    public function setObraSocial(\ObraSocial $obraSocial = null)
    {
        $this->obraSocial = $obraSocial;

        return $this;
    }

    /**
     * Get obraSocial.
     *
     * @return \ObraSocial|null
     */
    public function getObraSocial()
    {
        return $this->obraSocial;
    }

    /**
     * Set tipoDoc.
     *
     * @param \TipoDocumento|null $tipoDoc
     *
     * @return Paciente
     */
    public function setTipoDoc(\TipoDocumento $tipoDoc = null)
    {
        $this->tipoDoc = $tipoDoc;

        return $this;
    }

    /**
     * Get tipoDoc.
     *
     * @return \TipoDocumento|null
     */
    public function getTipoDoc()
    {
        return $this->tipoDoc;
    }
}
