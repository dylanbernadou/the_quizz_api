<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\GameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *   mercure=true,
 *   normalizationContext={"groups"={"read"}, "enable_max_depth"="true"},
 *   denormalizationContext={"groups"={"write"}}
 * )
 * @ORM\Entity(repositoryClass=GameRepository::class)
 */
class Game
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
     * @ORM\Column(type="string", length=255)
     *
     * @Groups({"read", "write"})
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Question::class, mappedBy="game")
     * @ApiSubresource
     * @MaxDepth(1)
     *
     * @Groups({"read"})
     */
    private $questions;

    /**
     * @ORM\OneToMany(targetEntity=Backlog::class, mappedBy="game")
     * @ApiSubresource
     * @MaxDepth(1)
     *
     * @Groups({"read"})
     */
    private $backlogs;

    public function __construct()
    {
        $this->questions = new ArrayCollection();
        $this->backlogs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Question[]
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestion(Question $question): self
    {
        if (!$this->questions->contains($question)) {
            $this->questions[] = $question;
            $question->setGame($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): self
    {
        if ($this->questions->removeElement($question)) {
            // set the owning side to null (unless already changed)
            if ($question->getGame() === $this) {
                $question->setGame(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Backlog[]
     */
    public function getBacklogs(): Collection
    {
        return $this->backlogs;
    }

    public function addBacklog(Backlog $backlog): self
    {
        if (!$this->backlogs->contains($backlog)) {
            $this->backlogs[] = $backlog;
            $backlog->setGame($this);
        }

        return $this;
    }

    public function removeBacklog(Backlog $backlog): self
    {
        if ($this->backlogs->removeElement($backlog)) {
            // set the owning side to null (unless already changed)
            if ($backlog->getGame() === $this) {
                $backlog->setGame(null);
            }
        }

        return $this;
    }
}
