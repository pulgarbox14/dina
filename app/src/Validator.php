<?php
declare(strict_types=1);

namespace App;

/**
 * Validation des formulaires. Chaque règle renvoie un message en français,
 * affiché sous le champ concerné.
 */
final class Validator
{
    /** @var array<string, string> */
    private array $errors = [];

    public function __construct(private readonly array $data)
    {
    }

    public function required(string $field, string $label, int $min = 1, int $max = 255): self
    {
        $value = (string) ($this->data[$field] ?? '');
        $len = mb_strlen($value);
        if ($value === '') {
            $this->errors[$field] ??= "$label est obligatoire.";
        } elseif ($len < $min) {
            $this->errors[$field] ??= "$label doit contenir au moins $min caractères.";
        } elseif ($len > $max) {
            $this->errors[$field] ??= "$label ne doit pas dépasser $max caractères.";
        }
        return $this;
    }

    public function optional(string $field, string $label, int $max = 255): self
    {
        if (mb_strlen((string) ($this->data[$field] ?? '')) > $max) {
            $this->errors[$field] ??= "$label ne doit pas dépasser $max caractères.";
        }
        return $this;
    }

    public function email(string $field, bool $required = true): self
    {
        $value = (string) ($this->data[$field] ?? '');
        if ($value === '' && !$required) {
            return $this;
        }
        if ($value === '' || mb_strlen($value) > 190 || filter_var($value, FILTER_VALIDATE_EMAIL) === false) {
            $this->errors[$field] ??= 'Adresse e-mail invalide.';
        }
        return $this;
    }

    public function phone(string $field): self
    {
        $value = (string) ($this->data[$field] ?? '');
        $digits = preg_replace('/\D/', '', $value);
        if (!preg_match('/^[+0-9 ().-]{6,40}$/', $value) || strlen((string) $digits) < 6) {
            $this->errors[$field] ??= 'Numéro de téléphone invalide.';
        }
        return $this;
    }

    public function fails(): bool
    {
        return $this->errors !== [];
    }

    /** @return array<string, string> */
    public function errors(): array
    {
        return $this->errors;
    }
}
