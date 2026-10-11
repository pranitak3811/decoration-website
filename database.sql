-- ============================================
-- DECORATION WEBSITE DATABASE
-- ============================================

CREATE DATABASE IF NOT EXISTS decoration_db;
USE decoration_db;

-- Gallery / Portfolio Table
CREATE TABLE IF NOT EXISTS gallery (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    category VARCHAR(100) NOT NULL,
    image_path VARCHAR(500) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Services Table
CREATE TABLE IF NOT EXISTS services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    base_price DECIMAL(10,2) NOT NULL,
    icon VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Bookings Table
CREATE TABLE IF NOT EXISTS bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    event_type VARCHAR(100) NOT NULL,
    event_date DATE NOT NULL,
    venue VARCHAR(500) NOT NULL,
    guest_count INT NOT NULL,
    budget VARCHAR(100),
    special_requests TEXT,
    status ENUM('pending', 'confirmed', 'completed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Contact Messages
CREATE TABLE IF NOT EXISTS contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- SAMPLE DATA
-- ============================================

INSERT INTO services (name, description, base_price, icon) VALUES
('Wedding Decoration', 'Complete wedding venue decoration including floral arrangements, lighting, stage setup, and table decor.', 25000.00, '💍'),
('Birthday Party', 'Fun and colorful birthday decorations with balloons, banners, cake table, and themed setups.', 8000.00, '🎂'),
('Corporate Events', 'Professional event decoration for conferences, product launches, and corporate parties.', 15000.00, '🏢'),
('Baby Shower', 'Adorable baby shower setups with pastel themes, floral walls, and sweet table displays.', 7000.00, '👶'),
('Anniversary Party', 'Romantic anniversary setups with elegant floral decor, lighting, and personalized touches.', 12000.00, '🌹'),
('Festival Decoration', 'Traditional and modern festival decoration for Diwali, Ganpati, Christmas & more.', 5000.00, '🪔');

INSERT INTO gallery (title, category, image_path, description) VALUES
('Royal Wedding Setup', 'Wedding', 'https://images.unsplash.com/photo-1519741497674-611481863552?w=600', 'Elegant royal wedding decoration with gold and white theme'),
('Floral Wedding Arch', 'Wedding', 'https://images.unsplash.com/photo-1465495976277-4387d4b0e4a6?w=600', 'Beautiful floral arch for the perfect wedding backdrop'),
('Birthday Balloon Wall', 'Birthday', 'https://images.unsplash.com/photo-1530103862676-de8c9debad1d?w=600', 'Colorful balloon wall for an unforgettable birthday party'),
('Corporate Gala Night', 'Corporate', 'https://images.unsplash.com/photo-1431540015161-0bf868a2d407?w=600', 'Sophisticated corporate event with elegant lighting'),
('Baby Shower Pink Theme', 'Baby Shower', 'https://images.unsplash.com/photo-1529543544282-ea669407fca3?w=600', 'Sweet pink themed baby shower setup'),
('Anniversary Romantic Setup', 'Anniversary', 'https://images.unsplash.com/photo-1518895949257-7621c3c786d7?w=600', 'Romantic candlelit anniversary decoration'),
('Festival Diwali Decor', 'Festival', 'https://images.unsplash.com/photo-1605197161470-5d0a4b1e0809?w=600', 'Traditional Diwali festival decoration with diyas and flowers'),
('Outdoor Garden Wedding', 'Wedding', 'https://images.unsplash.com/photo-1511285560929-80b456fea0bc?w=600', 'Stunning outdoor garden wedding setup'),
('Kids Birthday Carnival', 'Birthday', 'https://images.unsplash.com/photo-1464349095431-e9a21285b5f3?w=600', 'Fun carnival themed birthday party decoration');
