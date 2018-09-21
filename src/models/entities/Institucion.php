<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * Institucion
 *
 * @ORM\Table(name="institucion", indexes={@ORM\Index(name="FK_institucion_region_sanitaria_id", columns={"region_sanitaria_id"}), @ORM\Index(name="FK_tipo_institucion_id", columns={"tipo_institucion_id"})})
 * @ORM\Entity
 */
class Institucion
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
     * @ORM\Column(name="nombre", type="string", length=255, nullable=false)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="director", type="string", length=255, nullable=false)
     */
    private $director;

    /**
     * @var string
     *
     * @ORM\Column(name="telefono", type="string", length=255, nullable=false)
     */
    private $telefono;

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
     * @var \TipoInstitucion
     *
     * @ORM\ManyToOne(targetEntity="TipoInstitucion")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="tipo_institucion_id", referencedColumnName="id")
     * })
     */
    private $tipoInstitucion;


}
