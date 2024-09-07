<?php

namespace Empleados\Models;

class Empleado
{
    private string $name;
    private string $phone;
    private string $email;
    private float $weight; // Peso en kg
    private float $height; // Altura en metros
    private \DateTime $hireDate; // Fecha de contratación

    public function __construct(string $name, string $phone, string $email, float $weight, float $height, \DateTime $hireDate)
    {
        $this->setName($name);
        $this->setPhone($phone);
        $this->setEmail($email);
        $this->setWeight($weight);
        $this->setHeight($height);
        $this->setHireDate($hireDate);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getWeight(): float
    {
        return $this->weight;
    }

    public function getHeight(): float
    {
        return $this->height;
    }

    public function getHireDate(): \DateTime
    {
        return $this->hireDate;
    }

    public function setName(string $name): void
    {
        if (empty($name)) {
            throw new \InvalidArgumentException('Name cannot be empty');
        }
        $this->name = $name;
    }

    public function setPhone(string $phone): void
    {
        // Agregar validación de teléfono si es necesario
        $this->phone = $phone;
    }

    public function setEmail(string $email): void
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Invalid email address');
        }
        $this->email = $email;
    }

    public function setWeight(float $weight): void
    {
        if ($weight <= 0) {
            throw new \InvalidArgumentException('Weight must be positive');
        }
        $this->weight = $weight;
    }

    public function setHeight(float $height): void
    {
        if ($height <= 0) {
            throw new \InvalidArgumentException('Height must be positive');
        }
        $this->height = $height;
    }

    public function setHireDate(\DateTime $hireDate): void
    {
        $this->hireDate = $hireDate;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'weight' => $this->weight,
            'height' => $this->height,
            'hire_date' => $this->hireDate->format('Y-m-d'),
        ];
    }

    public function calculateIMC(): float
    {
        return $this->weight / ($this->height * $this->height);
    }

    public function timeInCompany(): string
    {
        $now = new \DateTime();
        $interval = $now->diff($this->hireDate);
        return $interval->y . ' years, ' . $interval->m . ' months';
    }
}

?>
