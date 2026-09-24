<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Cart;
use App\Http;
use App\MessageRepository;
use App\OrderRepository;
use App\Session;
use App\Validator;

/** Formulaires : commande, contact, newsletter. */
final class FormController
{
    public static function commande(): void
    {
        $data = [
            'customer_name' => Http::input('customer_name'),
            'phone'         => Http::input('phone'),
            'email'         => Http::input('email'),
            'address'       => Http::input('address'),
            'note'          => Http::input('note'),
        ];
        $v = (new Validator($data))
            ->required('customer_name', 'Le nom', 2, 120)
            ->required('phone', 'Le téléphone', 6, 40)->phone('phone')
            ->email('email', required: false)
            ->required('address', "L'adresse", 3, 255)
            ->optional('note', 'La précision', 2000);

        $cart = Cart::summary();
        if ($cart['items'] === []) {
            Session::flash('error', 'Votre panier est vide.');
            Http::redirect('/panier');
        }
        if ($v->fails()) {
            Session::flashForm('checkout', $data, $v->errors());
            Session::flash('error', 'Impossible d\'envoyer la commande. Vérifiez vos informations.');
            Http::redirect('/panier');
        }

        $order = OrderRepository::create($data, array_map(
            static fn (array $i): array => ['id' => $i['id'], 'name' => $i['name'], 'price' => $i['price'], 'qty' => $i['qty']],
            $cart['items']
        ));
        Cart::clear();
        Session::put('last_order', [
            'id'            => $order['id'],
            'total'         => $order['total'],
            'customer_name' => $data['customer_name'],
            'phone'         => $data['phone'],
        ]);
        Http::redirect('/commande/confirmee');
    }

    public static function contact(): void
    {
        $data = [
            'name'    => Http::input('name'),
            'email'   => Http::input('email'),
            'subject' => Http::input('subject'),
            'message' => Http::input('message'),
        ];
        $v = (new Validator($data))
            ->required('name', 'Le nom', 2, 120)
            ->email('email')
            ->required('subject', 'Le sujet', 2, 190)
            ->required('message', 'Le message', 5, 5000);

        if ($v->fails()) {
            self::fail('contact', $data, $v->errors(), 'Envoi impossible. Vérifiez les champs.');
            return;
        }
        if (!self::isBot()) {
            MessageRepository::createContact($data['name'], $data['email'], $data['subject'], $data['message']);
        }
        self::succeed('Message envoyé. Nous répondons sous 24 h.', '/contact');
    }

    public static function newsletter(): void
    {
        $data = ['email' => Http::input('email')];
        $v = (new Validator($data))->email('email');
        if ($v->fails()) {
            self::fail('newsletter', $data, $v->errors(), 'Adresse e-mail invalide.');
            return;
        }
        if (!self::isBot()) {
            MessageRepository::subscribe($data['email']);
        }
        self::succeed('Merci ! Vous recevrez nos nouvelles créations en avant-première.');
    }

    /** Le champ piège a été rempli : on fait comme si tout allait bien, sans rien enregistrer. */
    private static function isBot(): bool
    {
        return Http::input('website') !== '';
    }

    private static function fail(string $form, array $data, array $errors, string $message): void
    {
        if (Http::wantsJson()) {
            Http::json(['ok' => false, 'message' => $message, 'errors' => $errors], 422);
            return;
        }
        Session::flashForm($form, $data, $errors);
        Session::flash('error', $message);
        Http::back();
    }

    private static function succeed(string $message, ?string $redirect = null): void
    {
        if (Http::wantsJson()) {
            Http::json(['ok' => true, 'message' => $message]);
            return;
        }
        Session::flash('success', $message);
        $redirect !== null ? Http::redirect($redirect) : Http::back();
    }
}
