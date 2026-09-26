-- Dina Perles — schéma MySQL (MySQL 5.7+ / MariaDB 10.3+)
-- Usage : mysql -u <user> -p <base> < database/schema.sql

SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS products (
    id            VARCHAR(80)   NOT NULL,
    name          VARCHAR(160)  NOT NULL,
    subtitle      VARCHAR(255)  NOT NULL,
    price         INT UNSIGNED  NOT NULL COMMENT 'Prix en FCFA',
    category      VARCHAR(40)   NOT NULL,
    accent        VARCHAR(80)   NOT NULL,
    tag           VARCHAR(80)   NULL,
    weaving_hours SMALLINT UNSIGNED NOT NULL,
    dimensions    VARCHAR(120)  NOT NULL,
    description   TEXT          NOT NULL,
    featured      TINYINT(1)    NOT NULL DEFAULT 0,
    sort_order    SMALLINT      NOT NULL DEFAULT 0,
    active        TINYINT(1)    NOT NULL DEFAULT 1,
    created_at    DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_products_category (category, active),
    KEY idx_products_featured (featured, active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS product_images (
    id         INT UNSIGNED NOT NULL AUTO_INCREMENT,
    product_id VARCHAR(80)  NOT NULL,
    url        VARCHAR(500) NOT NULL,
    position   SMALLINT     NOT NULL DEFAULT 0,
    PRIMARY KEY (id),
    KEY idx_images_product (product_id, position),
    CONSTRAINT fk_images_product FOREIGN KEY (product_id)
        REFERENCES products (id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS orders (
    id            CHAR(36)      NOT NULL,
    customer_name VARCHAR(120)  NOT NULL,
    phone         VARCHAR(40)   NOT NULL,
    email         VARCHAR(190)  NULL,
    address       VARCHAR(255)  NOT NULL,
    note          TEXT          NULL,
    total         INT UNSIGNED  NOT NULL COMMENT 'Total en FCFA, recalculé côté serveur',
    status        VARCHAR(20)   NOT NULL DEFAULT 'nouvelle',
    created_at    DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_orders_created (created_at),
    KEY idx_orders_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Les lignes gardent le nom et le prix au moment de l'achat :
-- modifier un produit plus tard ne réécrit pas l'historique des commandes.
CREATE TABLE IF NOT EXISTS order_items (
    id           INT UNSIGNED NOT NULL AUTO_INCREMENT,
    order_id     CHAR(36)     NOT NULL,
    product_id   VARCHAR(80)  NULL,
    product_name VARCHAR(160) NOT NULL,
    unit_price   INT UNSIGNED NOT NULL,
    quantity     SMALLINT UNSIGNED NOT NULL,
    PRIMARY KEY (id),
    KEY idx_items_order (order_id),
    CONSTRAINT fk_items_order FOREIGN KEY (order_id)
        REFERENCES orders (id) ON DELETE CASCADE,
    CONSTRAINT fk_items_product FOREIGN KEY (product_id)
        REFERENCES products (id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS contact_messages (
    id         CHAR(36)     NOT NULL,
    name       VARCHAR(120) NOT NULL,
    email      VARCHAR(190) NOT NULL,
    subject    VARCHAR(190) NOT NULL,
    message    TEXT         NOT NULL,
    created_at DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_contact_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS newsletter_subscribers (
    id         INT UNSIGNED NOT NULL AUTO_INCREMENT,
    email      VARCHAR(190) NOT NULL,
    created_at DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_newsletter_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Avis clientes. Seuls les avis au statut « publie » s'affichent sur le site.
-- order_item_id relie l'avis à la ligne de commande achetée : c'est ce qui prouve
-- l'achat (« Achat vérifié »), et la clé unique empêche deux avis pour le même achat.
CREATE TABLE IF NOT EXISTS reviews (
    id            INT UNSIGNED     NOT NULL AUTO_INCREMENT,
    product_id    VARCHAR(80)      NOT NULL,
    order_item_id INT UNSIGNED     NULL,
    author_name   VARCHAR(120)     NOT NULL,
    city          VARCHAR(120)     NULL,
    rating        TINYINT UNSIGNED NOT NULL,
    title         VARCHAR(160)     NULL,
    body          TEXT             NOT NULL,
    status        VARCHAR(20)      NOT NULL DEFAULT 'en_attente' COMMENT 'en_attente, publie ou refuse',
    created_at    DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP,
    published_at  DATETIME         NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_reviews_order_item (order_item_id),
    KEY idx_reviews_product (product_id, status, published_at),
    CONSTRAINT chk_reviews_rating CHECK (rating BETWEEN 1 AND 5),
    CONSTRAINT fk_reviews_product FOREIGN KEY (product_id)
        REFERENCES products (id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_reviews_order_item FOREIGN KEY (order_item_id)
        REFERENCES order_items (id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
