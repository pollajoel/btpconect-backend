<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiFilter;
use App\Repository\UserEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\State\UserProcessorPost;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\Delete;
use Symfony\Component\Serializer\Annotation\Groups;
#[ApiResource(
    normalizationContext: ['groups' => ['user::read', 'mediaObject::read']],
    operations: [
        new GetCollection(uriTemplate: "/users",),
        new Get(uriTemplate: "/user/{id}"),
        new Post(
            uriTemplate: "/user",
            processor: UserProcessorPost::class,
        ),
        new Put(uriTemplate: "/user/{id}"),
        new Delete(
            uriTemplate: "/user/{id}",
            forceEager: false,
            processor: UserProcessorPost::class
        )
    ]
)]
#[ApiFilter(SearchFilter::class, properties: ['email' => 'partial', 'id' => 'partial'])]
#[ORM\Entity(repositoryClass: UserEntityRepository::class)]
#[ORM\Table(name: "user_entity", uniqueConstraints: [new ORM\UniqueConstraint(name: "unique_email", columns: ["email"])])]
class UserEntity implements UserInterface, PasswordAuthenticatedUserInterface
{

    use UuidTrait;

    #[ORM\Column(length: 255)]
    #[ApiFilter(SearchFilter::class, strategy: 'partial')]
    #[Groups(["user::read", "mediaObject::read"])]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    #[Groups(["user::read", "mediaObject::read"])]
    private ?string $name = null;


    #[ORM\Column(length: 255)]
    #[Groups(["user::read", "mediaObject::read"])]
    private ?string $firstname = null;

    #[ORM\Column(type: 'json')]
    #[Groups(["user::read", "mediaObject::read"])]
    private array $roles = [];

    #[ORM\Column(type: 'string')]
    #[Groups(["user::read", "mediaObject::read"])]
    private string $password;

    #[Assert\NotBlank]
    #[Groups(["user::read", "mediaObject::read"])]
    protected ?string $plainPassword = null;

    /**
     * @var Collection<UuidInterface, WorkSpaceEntity>
     */
    #[ORM\ManyToMany(targetEntity: WorkSpaceEntity::class, inversedBy: 'users', cascade: ["persist"])]
    #[Groups(["user::read", "mediaObject::read"])]
    private ?Collection $workSpaces;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["user::read", "mediaObject::read"])]
    private ?string $profilePicture = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[Groups(["user::read", "mediaObject::read"])]
    private ?MediaObject $profilePictureMain = null;

    public function __construct()
    {
        $this->workSpaces = new ArrayCollection();
    }



    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }


    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;
        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * The public representation of the user (e.g. a username, an email address, etc.)
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }
    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * @return Collection<int, WorkSpaceEntity>
     */
    public function getWorkSpaces(): ?Collection
    {
        return $this->workSpaces;
    }

    public function addWorkSpace(WorkSpaceEntity $workSpace): static
    {
        if (!$this->workSpaces->contains($workSpace)) {
            $this->workSpaces->add($workSpace);
        }

        return $this;
    }

    public function removeWorkSpace(WorkSpaceEntity $workSpace): static
    {
        $this->workSpaces->removeElement($workSpace);

        return $this;
    }

    public function getProfilePicture(): ?string
    {
        return $this->profilePicture;
    }

    public function setProfilePicture(?string $profilePicture): static
    {
        $this->profilePicture = $profilePicture;

        return $this;
    }

    public function getProfilePictureMain(): ?MediaObject
    {
        return $this->profilePictureMain;
    }

    public function setProfilePictureMain(?MediaObject $profilePictureMain): static
    {
        $this->profilePictureMain = $profilePictureMain;

        return $this;
    }
}
