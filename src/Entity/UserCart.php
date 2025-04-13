<?php

namespace App\Entity;

use App\Repository\UserCartRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserCartRepository::class)]
class UserCart
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(type: 'datetime')]
    private $createdAt;

    /**
     * @var Collection<int, CartList>
     */
    #[ORM\OneToMany(targetEntity: CartList::class, mappedBy: 'userCart')]
    private Collection $cartLists;

    public function __construct()
    {
        $this->cartLists = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection<int, CartList>
     */
    public function getCartLists(): Collection
    {
        return $this->cartLists;
    }

    public function addCartList(CartList $cartList): static
    {
        if (!$this->cartLists->contains($cartList)) {
            $this->cartLists->add($cartList);
            $cartList->setUserCart($this);
        }

        return $this;
    }

    public function removeCartList(CartList $cartList): static
    {
        if ($this->cartLists->removeElement($cartList)) {
            // set the owning side to null (unless already changed)
            if ($cartList->getUserCart() === $this) {
                $cartList->setUserCart(null);
            }
        }

        return $this;
    }
}
