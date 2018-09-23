<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * Partido
 *
 * @ORM\Table(name="partido", indexes={@ORM\Index(name="FK_partido_region_sanitaria_id", columns={"region_sanitaria_id"})})
 * @ORM\Entity
 */
class Partido
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
     * @var \RegionSanitaria
     *
     * @ORM\ManyToOne(targetEntity="RegionSanitaria")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="region_sanitaria_id", referencedColumnName="id")
     * })
     */
    private $regionSanitaria;


}
