CREATE DATABASE IF NOT EXISTS tembea_tanzania
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE tembea_tanzania;

CREATE TABLE IF NOT EXISTS destinations (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(140) NOT NULL,
  region VARCHAR(140) NOT NULL,
  category VARCHAR(80) NOT NULL,
  description TEXT NOT NULL,
  price_from DECIMAL(12,2) NOT NULL DEFAULT 0,
  image_url VARCHAR(500) NOT NULL,
  featured TINYINT(1) NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS bookings (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  booking_code VARCHAR(24) NOT NULL UNIQUE,
  destination_id INT UNSIGNED NOT NULL,
  full_name VARCHAR(140) NOT NULL,
  email VARCHAR(190) NOT NULL,
  phone VARCHAR(40) NOT NULL,
  travel_date DATE NOT NULL,
  guests TINYINT UNSIGNED NOT NULL,
  notes TEXT NULL,
  status ENUM('pending','confirmed','cancelled','completed') NOT NULL DEFAULT 'pending',
  payment_status ENUM('not_started','pending','paid','failed','refunded') NOT NULL DEFAULT 'not_started',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_booking_destination FOREIGN KEY (destination_id) REFERENCES destinations(id)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  INDEX idx_booking_date (travel_date),
  INDEX idx_booking_status (status, payment_status)
);

CREATE TABLE IF NOT EXISTS admins (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(80) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO destinations (name, region, category, description, price_from, image_url, featured)
SELECT * FROM (
 SELECT 'Serengeti National Park','Mara / Simiyu','Wildlife Safari',
 'Experience wide golden plains, remarkable wildlife and unforgettable safari moments with a licensed local operator.',
 950000.00,'https://images.unsplash.com/photo-1516426122078-c23e76319801?auto=format&fit=crop&w=1200&q=85',1
 UNION ALL SELECT 'Zanzibar Island','Zanzibar','Beach & Culture',
 'Discover turquoise waters, historic Stone Town, spice heritage and beautiful Indian Ocean sunsets.',
 450000.00,'https://images.unsplash.com/photo-1500375592092-40eb2168fd21?auto=format&fit=crop&w=1200&q=85',1
 UNION ALL SELECT 'Mount Kilimanjaro','Kilimanjaro','Mountain Trek',
 'Plan a guided trek with licensed mountain professionals, suitable acclimatisation and a responsible itinerary.',
 1800000.00,'https://images.unsplash.com/photo-1609198092458-38a293c7ac4b?auto=format&fit=crop&w=1200&q=85',1
 UNION ALL SELECT 'Ngorongoro Crater','Arusha','Wildlife & Nature',
 'Explore a spectacular volcanic caldera and its rich wildlife with an experienced guide.',
 780000.00,'https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?auto=format&fit=crop&w=1200&q=85',1
 UNION ALL SELECT 'Nyerere National Park','Coastal Region','Wildlife & River Safari',
 'Discover wild landscapes and river experiences in one of Tanzania’s expansive protected areas.',
 850000.00,'https://images.unsplash.com/photo-1547471080-7cc2caa01a7e?auto=format&fit=crop&w=1200&q=85',0
 UNION ALL SELECT 'Lake Manyara','Manyara','Nature & Birding',
 'Enjoy a nature-focused visit with birdlife, scenic views and a chance to explore varied habitats.',
 520000.00,'https://images.unsplash.com/photo-1511497584788-876760111969?auto=format&fit=crop&w=1200&q=85',0
) AS seed
WHERE NOT EXISTS (SELECT 1 FROM destinations LIMIT 1);
