<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\QuestionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *   mercure=true,
 *   normalizationContext={"groups"={"read"}, "enable_max_depth"="true"},
 *   denormalizationContext={"groups"={"write"}}
 * )
 * @ORM\Entity(repositoryClass=QuestionRepository::class)
 */
class Question
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
    private $question;

    /**
     * @ORM\Column(type="array")
     *
     * @Groups({"read", "write"})
     */
    private $answers = [];

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @Groups({"read", "write"})
     */
    private $doYouKnowIt;

    /**
     * @ORM\ManyToOne(targetEntity=Game::class, inversedBy="questions")
     * @ORM\JoinColumn(nullable=false)
     *
     * @Groups({"read", "write"})
     */
    private $game;

    /**
     * @ORM\ManyToOne(targetEntity=Theme::class, inversedBy="questions")
     *
     * @Groups({"read", "write"})
     */
    private $theme;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(string $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function getAnswers(): ?array
    {
        return $this->answers;
    }

    public function setAnswers(array $answers): self
    {
        $this->answers = $answers;

        return $this;
    }

    public function getDoYouKnowIt(): ?string
    {
        return $this->doYouKnowIt;
    }

    public function setDoYouKnowIt(?string $doYouKnowIt): self
    {
        $this->doYouKnowIt = $doYouKnowIt;

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

    public function getTheme(): ?Theme
    {
        return $this->theme;
    }

    public function setTheme(?Theme $theme): self
    {
        $this->theme = $theme;

        return $this;
    }
}
