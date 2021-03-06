<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BookRepository")
 */
class Book implements \JsonSerializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Isbn(type = "isbn13", message = "Invalid ISBN")
     */
    private $isbn;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Author", inversedBy="books")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Author;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Category", inversedBy="books")
     */
    private $Category;

    public function __construct()
    {
        $this->Category = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getIsbn(): ?string
    {
        return $this->isbn;
    }

    public function setIsbn(string $isbn): self
    {
        $this->isbn = $isbn;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getAuthor(): ?Author
    {
        return $this->Author;
    }

    public function setAuthor(?Author $Author): self
    {
        $this->Author = $Author;

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategory(): Collection
    {
        return $this->Category;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->Category->contains($category)) {
            $this->Category[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->Category->contains($category)) {
            $this->Category->removeElement($category);
        }

        return $this;
    }

    /**
     * Implementing specification defined by http://jsonapi.org
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        $data = [
            'type' => 'book',
            'id' => $this->getId(),
            'attributes' => [
                'title' => $this->getTitle(),
                'isbn'  => $this->getIsbn(),
                'price' => $this->getPrice(),
            ],
            'relationships' => [
                'author' => [
                    'data' => [
                        'id' => $this->getAuthor()->getId(),
                        'type' => 'author',
                    ]
                ]
            ]
        ];

        foreach ($this->getCategory() as $category) {
            if (!isset($data['relationships']['categories'])) {
                $data['relationships']['categories'] = [];
            }

            $data['relationships']['categories'][] = [
                'data' => [
                    'id' => $category->getId(),
                    'type' => 'category',
                ]
            ];
        }

        return $data;
    }

    public function getIncludedRelationships()
    {
        $included = [];
        $included[] = $this->getAuthor();

        foreach ($this->getCategory() as $category) {
            $included[] = $category;
        }

        return $included;
    }
}
