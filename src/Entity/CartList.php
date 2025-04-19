<?php

namespace App\Entity;

use App\Repository\CartListRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CartListRepository::class)]
#[ORM\HasLifecycleCallbacks]
class CartList
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $product = null;

    #[ORM\ManyToOne(inversedBy: 'cartLists')]
    #[ORM\JoinColumn(nullable: false)]
    private ?UserCart $userCart = null;

    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\Column(length: 5)]
    private ?string $size = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): static
    {
        $this->product = $product;

        return $this;
    }

    public function getUserCart(): ?UserCart
    {
        return $this->userCart;
    }

    public function setUserCart(?UserCart $userCart): static
    {
        $this->userCart = $userCart;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getSize(): ?string
    {
        return $this->size;
    }

    public function setSize(string $size): static
    {
        $this->size = $size;

        return $this;
    }
}
