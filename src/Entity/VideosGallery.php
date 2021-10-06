<?php

namespace App\Entity;

use App\Repository\VideosGalleryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VideosGalleryRepository::class)
 */
class VideosGallery
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

	// /**
	//  * @ORM\Column(type="text")
	//  */
	// private $video;

	/**
	 * @ORM\ManyToMany(targetEntity=Product::class, inversedBy="videosGalleries")
	 */
	private $product;

    /**
     * @ORM\Column(type="text")
     */
    private $url;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;

	public function __toString()
                  	{
                  		return $this->title;
                  	}

	public function __construct()
                  	{
                  		$this->product = new ArrayCollection();
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

	// public function getVideo(): ?string
	// {
	// 	return $this->video;
	// }

	// public function setVideo(string $video): self
	// {
	// 	$this->video = $video;

	// 	return $this;
	// }

	/**
	 * @return Collection|Product[]
	 */
	public function getProduct(): Collection
                  	{
                  		return $this->product;
                  	}

	public function addProduct(Product $product): self
                  	{
                  		if (!$this->product->contains($product)) {
                  			$this->product[] = $product;
                  		}
                  
                  		return $this;
                  	}

	public function removeProduct(Product $product): self
                  	{
                  		$this->product->removeElement($product);
                  
                  		return $this;
                  	}

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }
}