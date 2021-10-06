<?php

namespace App\Entity;

use App\Repository\AddressRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AddressRepository::class)
 */
class Address
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
	private $label;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $city;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $postal_code;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $country;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $phone_number;

	/**
	 * @ORM\ManyToOne(targetEntity=User::class, inversedBy="addresses")
	 */
	private $user;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $address;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $firstname;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $lastname;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $compagny;

	public function __toString()
	{
		return '[b]'. $this->getLabel(). '[/b]'. '[br]' . $this->getAddress() . '[br]' . $this->getCity() . ' - ' . $this->getCountry();
	}

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getLabel(): ?string
	{
		return $this->label;
	}

	public function setLabel(string $label): self
	{
		$this->label = $label;

		return $this;
	}

	public function getCity(): ?string
	{
		return $this->city;
	}

	public function setCity(string $city): self
	{
		$this->city = $city;

		return $this;
	}

	public function getPostalCode(): ?string
	{
		return $this->postal_code;
	}

	public function setPostalCode(string $postal_code): self
	{
		$this->postal_code = $postal_code;

		return $this;
	}

	public function getCountry(): ?string
	{
		return $this->country;
	}

	public function setCountry(string $country): self
	{
		$this->country = $country;

		return $this;
	}

	public function getPhoneNumber(): ?string
	{
		return $this->phone_number;
	}

	public function setPhoneNumber(string $phone_number): self
	{
		$this->phone_number = $phone_number;

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

	public function getAddress(): ?string
	{
		return $this->address;
	}

	public function setAddress(string $address): self
	{
		$this->address = $address;

		return $this;
	}

	public function getFirstname(): ?string
	{
		return $this->firstname;
	}

	public function setFirstname(string $firstname): self
	{
		$this->firstname = $firstname;

		return $this;
	}

	public function getLastname(): ?string
	{
		return $this->lastname;
	}

	public function setLastname(string $lastname): self
	{
		$this->lastname = $lastname;

		return $this;
	}

	public function getCompagny(): ?string
	{
		return $this->compagny;
	}

	public function setCompagny(?string $compagny): self
	{
		$this->compagny = $compagny;

		return $this;
	}
}