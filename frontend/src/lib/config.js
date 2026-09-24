export const BRAND = {
  name: "Dina Perles",
  tagline: "Haute Perlerie Artisanale",
  artisan: "Zinsou Secondina Dagbédé",
  city: "Cotonou, Bénin",
  whatsapp: "22990000000",
  email: "bonjour@dinaperles.com",
  instagram: "@dina.perles",
};

export const ARTISAN_PHOTO =
  "https://static.prod-images.emergentagent.com/jobs/7ee9dc22-104f-4f5f-bce4-68271cc60488/images/5b13992477cd37024403810ba3c8dc8810262c2257b146d9abe91afb4a41274c.jpeg";
export const ARTISAN_PHOTO_ORIGINAL =
  "https://customer-assets-cm19k8pv.emergentagent.net/job_7ee9dc22-104f-4f5f-bce4-68271cc60488/artifacts/9pj36h2y_WhatsApp%20Image%202026-09-22%20at%2017.11.51.jpeg";

export const IMG =
  "https://static.prod-images.emergentagent.com/jobs/7ee9dc22-104f-4f5f-bce4-68271cc60488/images/";

export const GALLERY = {
  lune: IMG + "46cb48231813f0acda019e8e5e4356b4088a4400185d9ab3649fcedbbc567ab0.jpeg",
  classique: IMG + "948ae007bf90f1cccd44d63de2610a179d9d7fd504a584b2a390c834a7a1bdf1.jpeg",
  orangeRound: IMG + "638c5bb9c6b1c9f2320fb89647f1941b48f217bba82e11ac795513904763fac3.jpeg",
  orangeTote: IMG + "6fb44d546c549ddb989ca3038d6b535177b7e6eda7b6cbf12e5d215a4ce25389.jpeg",
  purple: IMG + "d483f02574a8bd6c9b24c025cad33c6617fc51b1ae1c176f97e8ab23d3ca16b1.jpeg",
  trio: IMG + "3162899abd160da05fb509fe34b1c229746b7566748d4ebef2af7e3423814f43.jpeg",
  whiteSet: IMG + "9f479b1f426704ec3959febadbe5f31f0d208ea76b3585c35a18461aa8ea1c11.jpeg",
  ringSet: IMG + "6d9b5a4bf8e02572a0ce17141868ead5d8303ba81184d4615a61c7354f9762b7.jpeg",
  amber: IMG + "85bcddc3e7e29cc572b81600bcbe7c945eda731b37605ed8314ff3aec5ca8d36.jpeg",
  amberPlate: IMG + "84b8e25e1f496e0775005a7db0dde27dd7a11a3e05c54e45b9a9f534c0c0ba65.jpeg",
};

export const formatPrice = (n) => `${new Intl.NumberFormat("fr-FR").format(n)} FCFA`;

export const whatsappLink = (text) =>
  `https://wa.me/${BRAND.whatsapp}?text=${encodeURIComponent(text)}`;
