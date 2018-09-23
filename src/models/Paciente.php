<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * Paciente
 *
 * @ORM\Table(name="paciente", indexes={@ORM\Index(name="FK_region_sanitaria_id", columns={"region_sanitaria_id"}), @ORM\Index(name="FK_obra_social_id", columns={"obra_social_id"}), @ORM\Index(name="FK_tipo_doc_id", columns={"tipo_doc_id"}), @ORM\Index(name="FK_localidad_id", columns={"localidad_id"}), @ORM\Index(name="FK_genero_id", columns={"genero_id"})})
 * @ORM\Entity
 */
class Paciente
{
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
     * @ORM\Column(name="apellido", type="string", length=255, nullable=false)
     */
    private $apellido;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255, nullable=false)
     */
    private $nombre;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_nac", type="date", nullable=false)
     */
    private $fechaNac;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="lugar_nac", type="date", nullable=true)
     */
    private $lugarNac;

    /**
     * @var string
     *
     * @ORM\Column(name="domicilio", type="string", length=255, nullable=false)
     */
    private $domicilio;

    /**
     * @var bool
     *
     * @ORM\Column(name="tiene_documento", type="boolean", nullable=false, options={"default"="1"})
     */
    private $tieneDocumento = '1';

    /**
     * @var int
     *
     * @ORM\Column(name="numero", type="integer", nullable=false)
     */
    private $numero;

    /**
     * @var string
     *
     * @ORM\Column(name="tel", type="string", length=255, nullable=false)
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
     * @var \RegionSanitaria
     *
     * @ORM\ManyToOne(targetEntity="RegionSanitaria")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="region_sanitaria_id", referencedColumnName="id")
     * })
     */
    private $regionSanitaria;

    /**
     * @var \TipoDocumento
     *
     * @ORM\ManyToOne(targetEntity="TipoDocumento")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="tipo_doc_id", referencedColumnName="id")
     * })
     */
    private $tipoDoc;


}
