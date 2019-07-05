<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SubscriptionRepository")
 * @ORM\Table("subscription")
 */
class Subscription
{
    const STATUS_ACTIVE = 'active';
    const STATUS_NEW = 'new';
    const STATUS_CANCELLED = 'cancelled';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $user_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $subscription_shipping_address_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $subscription_billing_address_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @ORM\Column(type="integer")
     */
    private $subscription_pack_id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $started_at;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated_at;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\SubscriptionPayment", mappedBy="subscription", orphanRemoval=true)
     */
    private $subscriptionPayments;

    public function __construct()
    {
        $this->subscriptionPayments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getSubscriptionShippingAddressId(): ?int
    {
        return $this->subscription_shipping_address_id;
    }

    public function setSubscriptionShippingAddressId(int $subscription_shipping_address_id): self
    {
        $this->subscription_shipping_address_id = $subscription_shipping_address_id;

        return $this;
    }

    public function getSubscriptionBillingAddressId(): ?int
    {
        return $this->subscription_billing_address_id;
    }

    public function setSubscriptionBillingAddressId(int $subscription_billing_address_id): self
    {
        $this->subscription_billing_address_id = $subscription_billing_address_id;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getSubscriptionPackId(): ?int
    {
        return $this->subscription_pack_id;
    }

    public function setSubscriptionPackId(int $subscription_pack_id): self
    {
        $this->subscription_pack_id = $subscription_pack_id;

        return $this;
    }

    public function getStartedAt(): ?\DateTimeInterface
    {
        return $this->started_at;
    }

    public function setStartedAt(\DateTimeInterface $started_at): self
    {
        $this->started_at = $started_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function activate()
    {
        $this->status = self::STATUS_ACTIVE;
        $this->started_at = new \DateTime();
    }

    public function deactivate()
    {
        $this->status = self::STATUS_CANCELLED;
    }

    /**
     * @return Collection|SubscriptionPayment[]
     */
    public function getSubscriptionPayments(): Collection
    {
        return $this->subscriptionPayments;
    }

    public function addSubscriptionPayment(SubscriptionPayment $subscriptionPayment): self
    {
        if (!$this->subscriptionPayments->contains($subscriptionPayment)) {
            $this->subscriptionPayments[] = $subscriptionPayment;
            $subscriptionPayment->setSubscription($this);
        }

        return $this;
    }

    public function removeSubscriptionPayment(SubscriptionPayment $subscriptionPayment): self
    {
        if ($this->subscriptionPayments->contains($subscriptionPayment)) {
            $this->subscriptionPayments->removeElement($subscriptionPayment);
            // set the owning side to null (unless already changed)
            if ($subscriptionPayment->getSubscription() === $this) {
                $subscriptionPayment->setSubscription(null);
            }
        }

        return $this;
    }
}
