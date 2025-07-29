<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use App\Repository\ProduitRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'La référence du produit ne peut pas être vide.')]
    #[Assert\Length(min: 4 ,max: 255,minMessage: 'La référence du produit doit être suppérieur {{ limit }} caractères.' , maxMessage: 'La référence du produit ne peut pas dépasser {{ limit }} caractères.')]
    private ?string $reference = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: 'Le nom du produit ne peut pas être vide.')]
    #[Assert\Length(max: 255, maxMessage: 'Le nom du produit ne peut pas dépasser {{ limit }} caractères.')]
    private ?string $libelle = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\NotBlank(message: 'La description du produit ne peut pas être vide.')]
    #[Assert\Length(max: 65535, maxMessage: 'La description du produit ne peut pas dépasser {{ limit }} caractères.')]
    private ?string $description = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 6, scale: 2, nullable: true)]
    #[Assert\NotBlank(message: 'Le prix du produit ne peut pas être vide.')]
    #[Assert\Type('numeric')]
    #[Assert\Range(
        min: 0.01,
        max: 9999.99,
        notInRangeMessage: 'Le prix du produit doit être compris entre {{ min }} et {{ max }}.',
    )]
    #[Assert\Regex(
        pattern: '/^\d+(\.\d{1,2})?$/',
        message: 'Le prix doit être un nombre valide avec jusqu\'à deux décimales.'
    )]
    private ?string $prix = null;

    #[ORM\ManyToOne(inversedBy: 'produits')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank(message: 'La catégorie du produit ne peut pas être vide.')]
    private ?Categorie $categorie = null;

    // Champ qui sera enregistré en base (nom du fichier)
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photo = null;

    // Champ pour l’upload (non mappé à la base, donc pas d’annotation ORM)
    #[Assert\File(
        maxSize: '2M',
        mimeTypes: ['image/jpeg', 'image/png'],
        mimeTypesMessage: "Merci d'uploader une image JPG ou PNG"
    )]
    private ?File $imageFile = null;

    #[ORM\Column(nullable: true)]
    #[Assert\NotBlank(message: 'Le stock disponible ne peut pas être vide.')]
    #[Assert\length(min: 0, max: 999999,
        minMessage: 'Le stock doit être supérieur ou égal à {{ limit }}.',
        maxMessage: 'Le stock ne peut pas dépasser {{ limit }}.'
    )]
    #[Assert\PositiveOrZero(message: 'Le stock doit être un nombre positif ou zéro.')]  
    private ?int $stock = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): static
    {
        $this->reference = $reference;

        return $this;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(?string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrix(): ?string
    {
        return $this->prix;
    }

    public function setPrix(?string $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): static
    {
        $this->photo = $photo;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(?int $stock): static
    {
        $this->stock = $stock;

        return $this;
    }
}
