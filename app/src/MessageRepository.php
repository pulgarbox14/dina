<?php
declare(strict_types=1);

namespace App;

/** Messages du formulaire de contact et inscriptions à la newsletter. */
final class MessageRepository
{
    public static function createContact(string $name, string $email, string $subject, string $message): string
    {
        $id = uuid4();
        Database::pdo()->prepare(
            'INSERT INTO contact_messages (id, name, email, subject, message)
             VALUES (:id, :name, :email, :subject, :message)'
        )->execute(compact('id', 'name', 'email', 'subject', 'message'));
        return $id;
    }

    /** Idempotent : une adresse déjà inscrite ne crée pas de doublon. */
    public static function subscribe(string $email): void
    {
        Database::pdo()->prepare(
            'INSERT INTO newsletter_subscribers (email) VALUES (:email)
             ON DUPLICATE KEY UPDATE email = email'
        )->execute(['email' => mb_strtolower($email)]);
    }
}
