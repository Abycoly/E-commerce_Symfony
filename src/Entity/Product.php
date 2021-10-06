<?php

namespace App\Entity;

use App\Entity\Category;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProductRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(type="integer")
	 */
	private $id;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $title;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $description;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $media;

	/**
	 * @ORM\Column(type="integer")
	 */
	private $price;

	/**
	 * @ORM\Column(type="integer")
	 */
	private $size;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $color;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $slug;


	/**
	 * @ORM\Column(type="integer")
	 */
	private $quantity;

	/**
	 * @ORM\Column(type="boolean", nullable=true)
	 */
	private $isBest;

	/**
	 * @ORM\Column(type="boolean", nullable=true)
	 */
	private $isNewArrival;

	/**
	 * @ORM\Column(type="boolean", nullable=true)
	 */
	private $isSpecialOffert;

	/**
	 * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="products")
	 */
	private $category;


	/**
	 * @ORM\OneToMany(targetEntity=RelatedProduct::class, mappedBy="product")
	 */
	private $relatedProducts;

	/**
	 * @ORM\OneToMany(targetEntity=ReviewsProduct::class, mappedBy="product")
	 */
	private $reviewsProducts;

	/**
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $tags;

	/**
	 * @ORM\ManyToMany(targetEntity=VideosGallery::class, mappedBy="product")
	 */
	private $videosGalleries;

	/**
	 * @ORM\ManyToMany(targetEntity=ImagesGallery::class, mappedBy="product")
	 */
	private $imagesGalleries;

	public function __construct()
	{
		$this->tagsProducts = new ArrayCollection();
		$this->relatedProducts = new ArrayCollection();
		$this->reviewsProducts = new ArrayCollection();
		$this->videosGalleries = new ArrayCollection();
		$this->imagesGalleries = new ArrayCollection();
	}

	public function __toString()
	{
		return $this->title;
	}

	public function getId(): ?int
	{
		return $this->id;
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

	public function getDescription(): ?string
	{
		return $this->description;
	}

	public function setDescription(string $description): self
	{
		$this->description = $description;

		return $this;
	}

	public function getMedia(): ?string
	{
		return $this->media;
	}

	public function setMedia($media): self
	{
		$this->media = $media;

		return $this;
	}

	public function getPrice(): ?int
	{
		return $this->price;
	}

	public function setPrice(int $price): self
	{
		$this->price = $price;

		return $this;
	}

	public function getSize(): ?int
	{
		return $this->size;
	}

	public function setSize(int $size): self
	{
		$this->size = $size;

		return $this;
	}

	public function getColor(): ?string
	{
		return $this->color;
	}

	public function setColor(string $color): self
	{
		$this->color = $color;

		return $this;
	}

	public function getSlug(): ?string
	{
		return $this->slug;
	}

	public function setSlug(string $slug): self
	{
		$this->slug = $slug;

		return $this;
	}



	public function getQuantity(): ?int
	{
		return $this->quantity;
	}

	public function setQuantity(int $quantity): self
	{
		$this->quantity = $quantity;

		return $this;
	}

	public function getIsBest(): ?bool
	{
		return $this->isBest;
	}

	public function setIsBest(bool $isBest): self
	{
		$this->isBest = $isBest;

		return $this;
	}

	public function getIsNewArrival(): ?bool
	{
		return $this->isNewArrival;
	}

	public function setIsNewArrival(?bool $isNewArrival): self
	{
		$this->isNewArrival = $isNewArrival;

		return $this;
	}

	public function getIsSpecialOffert(): ?bool
	{
		return $this->isSpecialOffert;
	}

	public function setIsSpecialOffert(?bool $isSpecialOffert): self
	{
		$this->isSpecialOffert = $isSpecialOffert;

		return $this;
	}


	public function getCategory(): ?Category
	{
		return $this->category;
	}

	public function setCategory(?Category $category): self
	{
		$this->category = $category;

		return $this;
	}




	/**
	 * @return Collection|RelatedProduct[]
	 */
	public function getRelatedProducts(): Collection
	{
		return $this->relatedProducts;
	}

	public function addRelatedProduct(RelatedProduct $relatedProduct): self
	{
		if (!$this->relatedProducts->contains($relatedProduct)) {
			$this->relatedProducts[] = $relatedProduct;
			$relatedProduct->setProduct($this);
		}

		return $this;
	}

	public function removeRelatedProduct(RelatedProduct $relatedProduct): self
	{
		if ($this->relatedProducts->removeElement($relatedProduct)) {
			// set the owning side to null (unless already changed)
			if ($relatedProduct->getProduct() === $this) {
				$relatedProduct->setProduct(null);
			}
		}

		return $this;
	}

	/**
	 * @return Collection|ReviewsProduct[]
	 */
	public function getReviewsProducts(): Collection
	{
		return $this->reviewsProducts;
	}

	public function addReviewsProduct(ReviewsProduct $reviewsProduct): self
	{
		if (!$this->reviewsProducts->contains($reviewsProduct)) {
			$this->reviewsProducts[] = $reviewsProduct;
			$reviewsProduct->setProduct($this);
		}

		return $this;
	}

	public function removeReviewsProduct(ReviewsProduct $reviewsProduct): self
	{
		if ($this->reviewsProducts->removeElement($reviewsProduct)) {
			// set the owning side to null (unless already changed)
			if ($reviewsProduct->getProduct() === $this) {
				$reviewsProduct->setProduct(null);
			}
		}

		return $this;
	}

	public function getTags(): ?string
	{
		return $this->tags;
	}

	public function setTags(?string $tags): self
	{
		$this->tags = $tags;

		return $this;
	}

	/**
	 * @return Collection|VideosGallery[]
	 */
	public function getVideosGalleries(): Collection
	{
		return $this->videosGalleries;
	}

	public function addVideosGallery(VideosGallery $videosGallery): self
	{
		if (!$this->videosGalleries->contains($videosGallery)) {
			$this->videosGalleries[] = $videosGallery;
			$videosGallery->addProduct($this);
		}

		return $this;
	}

	public function removeVideosGallery(VideosGallery $videosGallery): self
	{
		if ($this->videosGalleries->removeElement($videosGallery)) {
			$videosGallery->removeProduct($this);
		}

		return $this;
	}

	/**
	 * @return Collection|ImagesGallery[]
	 */
	public function getImagesGalleries(): Collection
	{
		return $this->imagesGalleries;
	}

	public function addImagesGallery(ImagesGallery $imagesGallery): self
	{
		if (!$this->imagesGalleries->contains($imagesGallery)) {
			$this->imagesGalleries[] = $imagesGallery;
			$imagesGallery->addProduct($this);
		}

		return $this;
	}

	public function removeImagesGallery(ImagesGallery $imagesGallery): self
	{
		if ($this->imagesGalleries->removeElement($imagesGallery)) {
			$imagesGallery->removeProduct($this);
		}

		return $this;
	}
}
