UPDATE `products` SET slug = lower(name),
slug = replace(slug, '.', ' '),
slug = replace(slug, '\'', '-'),
slug = replace(slug, '/', '-'),
slug = replace(slug, '&', '-'),
slug = replace(slug, '@', '-'),
slug = replace(slug, '?', '-'),
slug = replace(slug,'|','-'),
slug = replace(slug,'š','s'),
slug = replace(slug,'Ð','Dj'),
slug = replace(slug,'ž','z'),
slug = replace(slug,'Þ','B'),
slug = replace(slug,'ß','Ss'),
slug = replace(slug,'à','a'),
slug = replace(slug,'á','a'),
slug = replace(slug,'â','a'),
slug = replace(slug,'ã','a'),
slug = replace(slug,'ä','a'),
slug = replace(slug,'å','a'),
slug = replace(slug,'æ','a'),
slug = replace(slug,'ç','c'),
slug = replace(slug,'è','e'),
slug = replace(slug,'é','e'),
slug = replace(slug,'ê','e'),
slug = replace(slug,'ë','e'),
slug = replace(slug,'ì','i'),
slug = replace(slug,'í','i'),
slug = replace(slug,'î','i'),
slug = replace(slug,'ï','i'),
slug = replace(slug,'ð','o'),
slug = replace(slug,'ñ','n'),
slug = replace(slug,'ò','o'),
slug = replace(slug,'ó','o'),
slug = replace(slug,'ô','o'),
slug = replace(slug,'õ','o'),
slug = replace(slug,'ö','o'),
slug = replace(slug,'ø','o'),
slug = replace(slug,'ù','u'),
slug = replace(slug,'ú','u'),
slug = replace(slug,'û','u'),
slug = replace(slug,'ý','y'),
slug = replace(slug,'ý','y'),
slug = replace(slug,'þ','b'),
slug = replace(slug,'ÿ','y'),
slug = replace(slug,'ƒ','f'),
slug = replace(slug, 'œ', 'oe'),
slug = replace(slug, '€', 'euro'),
slug = replace(slug, '$', 'dollars'),
slug = replace(slug, '£', ''),
slug = trim(slug),
slug = replace(slug, ' ', '-');

UPDATE products LEFT JOIN product_bulk ON product_bulk.video_provider = products.video_provider SET products.purchase_price = product_bulk.purchase_price;
UPDATE products LEFT JOIN product_bulk ON product_bulk.video_provider = products.video_provider SET products.description = product_bulk.description;


ALTER TABLE `product_translations` CHANGE `lang` `lang` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT 'en';

ALTER TABLE `category_translations` CHANGE `lang` `lang` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT 'en';

UPDATE uploads SET file_original_name = CONCAT( "Product " , id);
UPDATE uploads SET file_size = '100';
UPDATE uploads SET extension = 'jpg';
UPDATE uploads SET user_id = 9;


UPDATE `categories` SET `parent_id` = '1' WHERE `categories`.`id` >= 2 && `categories`.`id`<=23;
UPDATE `categories` SET `parent_id` = '24' WHERE `categories`.`id` >= 25 && `categories`.`id`<=29;
UPDATE `categories` SET `parent_id` = '34' WHERE `categories`.`id` >= 35 && `categories`.`id`<=36;


ALTER TABLE `products` ADD `good_for` LONGTEXT NULL DEFAULT NULL AFTER `description`, ADD `skin_types` LONGTEXT NULL DEFAULT NULL AFTER `good_for`, ADD `key_ingredients` LONGTEXT NULL DEFAULT NULL AFTER `skin_types`;

ALTER TABLE `reviews` ADD `parent_id` INT NULL DEFAULT NULL AFTER `updated_at`;
ALTER TABLE `reviews` ADD `comment_author` INT NULL DEFAULT NULL AFTER `parent_id`;
ALTER TABLE `reviews` ADD `comment_email` INT NULL DEFAULT NULL AFTER `comment_author`;


ALTER TABLE `reviews` CHANGE `comment` `comment` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `reviews` CHANGE `user_id` `comment` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;

ALTER TABLE `orders` ADD `shipping_cost` DOUBLE NULL AFTER `shipping_type`; 

ALTER TABLE `products` CHANGE `category_id` `category_id` INT(11) NULL; 

CREATE TABLE `category_product` (
  `category_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `review_replays` (
  `id` int(11) NOT NULL,
  `review_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `replay` text NOT NULL,
  `is_active` tinyint(4) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

