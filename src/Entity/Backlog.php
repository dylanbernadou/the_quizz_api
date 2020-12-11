<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\BacklogRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *   mercure=true,
 *   normalizationContext={"groups"={"read"}, "enable_max_depth"="true"},
 *   denormalizationContext={"groups"={"write"}}
 * )
 * @ORM\Entity(repositoryClass=BacklogRepository::class)
 */
class Backlog
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     * @Groups({"read"})
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     *
     * @Groups({"read", "write"})
     */
    private $score;

    /**
     * @ORM\Column(type="datetime")
     *
     * @Groups({"read"})
     */
    private $datetime;

    /**
     * @ORM\ManyToOne(targetEntity=Game::class, inversedBy="backlogs")
     * @ORM\JoinColumn(nullable=false)
     *
     * @Groups({"read", "write"})
     */
    private $game;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="backlogs")
     * @ORM\JoinColumn(nullable=false)
     *
     * @Groups({"read", "write"})
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(int $score): self
    {
        $this->score = $score;

        return $this;
    }

    public function getDatetime(): ?\DateTimeInterface
    {
        return $this->datetime;
    }

    public function setDatetime(\DateTimeInterface $datetime): self
    {
        $this->datetime = $datetime;

        return $this;
    }

    public function getGame(): ?Game
    {
        return $this->game;
    }

    public function setGame(?Game $game): self
    {
        $this->game = $game;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
