<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class Card
{
    const TYPE_MC = 'MC';
    const TYPE_VI = 'VI';

    /**
     * @Assert\Length(min="16", max="16")
     */
    private $number;
    
    private $cvvNumber;

    private $type;

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getCvvNumber(): ?int
    {
        return $this->cvvNumber;
    }

    public function setCvvNumber(int $cvvNumber): self
    {
        $this->cvvNumber = $cvvNumber;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @Assert\Callback()
     * @todo: extend validation to cover all cases
     */
    public function validate(ExecutionContextInterface $context): void
    {
        $type = $this->getType();
        $number = $this->getNumber();
        switch ($type) {
            case (self::TYPE_MC): {
                if (!preg_match('/^5[12345].+/', $number)) {
                    $context->buildViolation('Incorrect format')->atPath('number')->addViolation();
                }
                break;
            }
            case (self::TYPE_VI): {
                if (!preg_match('/^4.+/', $number)) {
                    $context->buildViolation('Incorrect format')->atPath('number')->addViolation();
                }
                break;
            }
        }
    }

    public static function getTypes(): array
    {
        return [
            'MasterCard' => self::TYPE_MC,
            'Visa'       => self::TYPE_VI
        ];
    }
}
