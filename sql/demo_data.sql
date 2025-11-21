-- ============================================
-- MAGICBOOK DEMO DATA FOR PRODUCTION SERVER
-- ============================================
-- This file contains realistic demo data for the MagicBook system
-- Perfect for setting up a demo/testing environment on your online server
--
-- INCLUDES:
-- - 3 Test users (admin, subadmin, agent)
-- - 8 Delivery agents across major Nigerian states
-- - 45 Sample orders with various statuses
-- - Stock inventory for all 37 Nigerian states
-- - Sample stock movements (deliveries)
-- - Activity logs showing system usage
--
-- HOW TO USE:
-- 1. Ensure you have already created the tables using create_auth_system.sql
-- 2. Run this file: mysql -u username -p database_name < demo_data.sql
-- 3. Login with: admin / admin123
-- ============================================

USE copybook;

-- ============================================
-- 1. USERS (with correct bcrypt password hashes)
-- ============================================
-- Clear existing users first
DELETE FROM users WHERE username IN ('admin', 'subadmin', 'agent001');

-- Admin user (full access)
-- Username: admin | Password: admin123
INSERT INTO users (username, email, password, full_name, role, status, created_at) 
VALUES ('admin', 'admin@magicbook.com', '$2y$10$4pBISldZ9DSKz6TnpNHbU.XhIw0tQmTSTXEPUNskQtUa9mbCGyE8a', 
        'System Administrator', 'admin', 'active', NOW() - INTERVAL 90 DAY);

-- Subadmin user (can't delete or see analytics)
-- Username: subadmin | Password: subadmin123
INSERT INTO users (username, email, password, full_name, role, status, created_by, created_at) 
VALUES ('subadmin', 'subadmin@magicbook.com', '$2y$10$LK04KsQQjgs12JVRUXPwLu9LPEFNNqWkvN86YOU9EkTLyA36PNF0q', 
        'Sales Manager', 'subadmin', 'active', 1, NOW() - INTERVAL 60 DAY);

-- Agent user (view only)
-- Username: agent001 | Password: agent123
INSERT INTO users (username, email, password, full_name, role, status, created_by, created_at) 
VALUES ('agent001', 'agent@magicbook.com', '$2y$10$fh48IRs4RNJjSS72ghZ9L.j2w517twlISNdY6sbei/p5nkIRSBGPG', 
        'Delivery Agent User', 'agent', 'active', 1, NOW() - INTERVAL 45 DAY);

-- ============================================
-- 2. DELIVERY AGENTS (8 agents covering major states)
-- ============================================
DELETE FROM delivery_agents;

INSERT INTO delivery_agents (name, phone, email, state, local_govt, status, created_at, updated_at, updated_by) VALUES
('Chukwuemeka Okafor', '08012345601', 'chukwu@magicbook.com', 'Lagos', 'Ikeja', 'active', NOW() - INTERVAL 60 DAY, NOW() - INTERVAL 10 DAY, 1),
('Aisha Ibrahim', '08012345602', 'aisha@magicbook.com', 'Kano', 'Kano Municipal', 'active', NOW() - INTERVAL 55 DAY, NOW() - INTERVAL 5 DAY, 1),
('Oluwaseun Adebayo', '08012345603', 'seun@magicbook.com', 'Oyo', 'Ibadan North', 'active', NOW() - INTERVAL 50 DAY, NOW() - INTERVAL 15 DAY, 1),
('Ngozi Eze', '08012345604', 'ngozi@magicbook.com', 'Enugu', 'Enugu North', 'active', NOW() - INTERVAL 48 DAY, NOW() - INTERVAL 8 DAY, 1),
('Musa Ahmed', '08012345605', 'musa@magicbook.com', 'Kaduna', 'Kaduna North', 'active', NOW() - INTERVAL 45 DAY, NOW() - INTERVAL 20 DAY, 1),
('Blessing Okoro', '08012345606', 'blessing@magicbook.com', 'Rivers', 'Port Harcourt', 'active', NOW() - INTERVAL 40 DAY, NOW() - INTERVAL 12 DAY, 1),
('Yusuf Bello', '08012345607', 'yusuf@magicbook.com', 'Abuja', 'Municipal Area Council', 'active', NOW() - INTERVAL 35 DAY, NOW() - INTERVAL 3 DAY, 1),
('Chidinma Nwosu', '08012345608', 'chidinma@magicbook.com', 'Anambra', 'Onitsha South', 'inactive', NOW() - INTERVAL 30 DAY, NOW() - INTERVAL 25 DAY, 2);

-- ============================================
-- 3. AGENT STATE ASSIGNMENTS
-- ============================================
DELETE FROM agent_states;

INSERT INTO agent_states (agent_id, state) VALUES
(1, 'Lagos'), (1, 'Ogun'),
(2, 'Kano'), (2, 'Jigawa'), (2, 'Katsina'),
(3, 'Oyo'), (3, 'Osun'),
(4, 'Enugu'), (4, 'Ebonyi'),
(5, 'Kaduna'), (5, 'Niger'), (5, 'Plateau'),
(6, 'Rivers'), (6, 'Bayelsa'), (6, 'Akwa Ibom'),
(7, 'Abuja'), (7, 'Nasarawa'), (7, 'Kogi'),
(8, 'Anambra'), (8, 'Abia'), (8, 'Imo');

-- ============================================
-- 4. SAMPLE ORDERS (45 orders with various statuses)
-- ============================================
-- Note: Adjust order IDs if you have existing orders
DELETE FROM orders WHERE id > 0;
ALTER TABLE orders AUTO_INCREMENT = 1;

-- Recent orders (last 7 days) - Various statuses
INSERT INTO orders (customer_name, customer_phone, email, state, local_govt, delivery_address, package_type, quantity, total_amount, order_status, agent_id, payment_status, special_instructions, ordered_at, updated_by) VALUES
('Adebayo Johnson', '08101234501', 'adebayo@email.com', 'Lagos', 'Ikeja', '12 Allen Avenue, Ikeja', 'Complete Set', 5, 75000, 'pending', 1, 'pending', 'Please call before delivery', NOW() - INTERVAL 1 DAY, NULL),
('Fatima Mohammed', '08101234502', 'fatima@email.com', 'Kano', 'Kano Municipal', '45 Zoo Road, Kano', 'Basic Package', 2, 10000, 'confirmed', 2, 'paid', '', NOW() - INTERVAL 2 DAY, 1),
('Chioma Okeke', '08101234503', 'chioma@email.com', 'Enugu', 'Enugu North', '8 Independence Layout', 'Standard Set', 3, 30000, 'processing', 4, 'paid', '', NOW() - INTERVAL 2 DAY, 1),
('Ibrahim Suleiman', '08101234504', 'ibrahim@email.com', 'Abuja', 'Municipal Area Council', 'Wuse 2, Plot 234', 'Complete Set', 10, 150000, 'assigned', 7, 'paid', 'Corporate order for school', NOW() - INTERVAL 3 DAY, 1),
('Grace Udo', '08101234505', 'grace@email.com', 'Akwa Ibom', 'Uyo', '22 Aka Road, Uyo', 'Basic Package', 1, 5000, 'in_transit', 6, 'paid', '', NOW() - INTERVAL 4 DAY, 2),
('Ahmed Lawal', '08101234506', 'ahmed@email.com', 'Kaduna', 'Kaduna North', '15 Ahmadu Bello Way', 'Standard Set', 4, 40000, 'delivered', 5, 'paid', '', NOW() - INTERVAL 5 DAY, 1),
('Blessing Obi', '08101234507', 'blessing@email.com', 'Rivers', 'Port Harcourt', '7 Trans Amadi Layout', 'Complete Set', 2, 30000, 'delivered', 6, 'paid', '', NOW() - INTERVAL 6 DAY, 2),
('Usman Garba', '08101234508', 'usman@email.com', 'Katsina', 'Katsina', '33 Nagogo Road', 'Basic Package', 3, 15000, 'cancelled', NULL, 'pending', 'Customer changed mind', NOW() - INTERVAL 7 DAY, 1);

-- Orders from 1-2 weeks ago
INSERT INTO orders (customer_name, customer_phone, email, state, local_govt, delivery_address, package_type, quantity, total_amount, order_status, agent_id, payment_status, ordered_at, updated_by, delivered_at) VALUES
('Chiamaka Nwankwo', '08101234509', 'chiamaka@email.com', 'Anambra', 'Onitsha South', '18 Main Market Road', 'Standard Set', 5, 50000, 'delivered', 8, 'paid', NOW() - INTERVAL 10 DAY, 1, NOW() - INTERVAL 8 DAY),
('Mohammed Bala', '08101234510', 'mohammed@email.com', 'Jigawa', 'Dutse', '25 Hospital Road, Dutse', 'Complete Set', 3, 45000, 'delivered', 2, 'paid', NOW() - INTERVAL 11 DAY, 2, NOW() - INTERVAL 9 DAY),
('Esther Oladele', '08101234511', 'esther@email.com', 'Oyo', 'Ibadan North', '42 Ring Road, Ibadan', 'Basic Package', 4, 20000, 'delivered', 3, 'paid', NOW() - INTERVAL 12 DAY, 1, NOW() - INTERVAL 10 DAY),
('Victor Okon', '08101234512', 'victor@email.com', 'Lagos', 'Surulere', '9 Adelabu Street', 'Standard Set', 2, 20000, 'delivered', 1, 'paid', NOW() - INTERVAL 13 DAY, 1, NOW() - INTERVAL 11 DAY),
('Zainab Hassan', '08101234513', 'zainab@email.com', 'Niger', 'Minna', '14 Paiko Road, Minna', 'Complete Set', 1, 15000, 'delivered', 5, 'paid', NOW() - INTERVAL 14 DAY, 2, NOW() - INTERVAL 12 DAY);

-- Orders from 2-4 weeks ago
INSERT INTO orders (customer_name, customer_phone, email, state, local_govt, delivery_address, package_type, quantity, total_amount, order_status, agent_id, payment_status, ordered_at, updated_by, delivered_at) VALUES
('Oluwaseun Balogun', '08101234514', 'seun.b@email.com', 'Ogun', 'Abeokuta South', '31 Iberekodo, Abeokuta', 'Basic Package', 6, 30000, 'delivered', 1, 'paid', NOW() - INTERVAL 15 DAY, 1, NOW() - INTERVAL 13 DAY),
('Hauwa Yusuf', '08101234515', 'hauwa@email.com', 'Kano', 'Nassarawa', '56 France Road, Kano', 'Standard Set', 3, 30000, 'delivered', 2, 'paid', NOW() - INTERVAL 16 DAY, 1, NOW() - INTERVAL 14 DAY),
('Chidi Okafor', '08101234516', 'chidi@email.com', 'Imo', 'Owerri Municipal', '23 Wetheral Road, Owerri', 'Complete Set', 4, 60000, 'delivered', 8, 'paid', NOW() - INTERVAL 18 DAY, 2, NOW() - INTERVAL 16 DAY),
('Amina Abdullahi', '08101234517', 'amina@email.com', 'Plateau', 'Jos North', '11 Beach Road, Jos', 'Basic Package', 2, 10000, 'delivered', 5, 'paid', NOW() - INTERVAL 19 DAY, 1, NOW() - INTERVAL 17 DAY),
('Tunde Adeyemi', '08101234518', 'tunde@email.com', 'Osun', 'Osogbo', '7 Oke-Fia, Osogbo', 'Standard Set', 5, 50000, 'delivered', 3, 'paid', NOW() - INTERVAL 20 DAY, 1, NOW() - INTERVAL 18 DAY),
('Precious Nnamdi', '08101234519', 'precious@email.com', 'Ebonyi', 'Abakaliki', '19 Kpirikpiri, Abakaliki', 'Complete Set', 2, 30000, 'delivered', 4, 'paid', NOW() - INTERVAL 22 DAY, 2, NOW() - INTERVAL 20 DAY),
('Abdulrahman Umar', '08101234520', 'abdul@email.com', 'Abuja', 'Gwagwalada', '44 Park Road, Gwagwalada', 'Basic Package', 3, 15000, 'delivered', 7, 'paid', NOW() - INTERVAL 23 DAY, 1, NOW() - INTERVAL 21 DAY),
('Nneka Chukwu', '08101234521', 'nneka@email.com', 'Bayelsa', 'Yenagoa', '6 Imgbi Road, Yenagoa', 'Standard Set', 4, 40000, 'delivered', 6, 'paid', NOW() - INTERVAL 25 DAY, 1, NOW() - INTERVAL 23 DAY),
('Sani Abubakar', '08101234522', 'sani@email.com', 'Nasarawa', 'Lafia', '28 Jos Road, Lafia', 'Complete Set', 5, 75000, 'delivered', 7, 'paid', NOW() - INTERVAL 26 DAY, 2, NOW() - INTERVAL 24 DAY);

-- Orders from 1-2 months ago
INSERT INTO orders (customer_name, customer_phone, email, state, local_govt, delivery_address, package_type, quantity, total_amount, order_status, agent_id, payment_status, ordered_at, updated_by, delivered_at) VALUES
('Ifeoma Eze', '08101234523', 'ifeoma@email.com', 'Enugu', 'Nsukka', '12 University Road, Nsukka', 'Basic Package', 8, 40000, 'delivered', 4, 'paid', NOW() - INTERVAL 30 DAY, 1, NOW() - INTERVAL 28 DAY),
('Murtala Bello', '08101234524', 'murtala@email.com', 'Kano', 'Dala', '38 Ibrahim Taiwo Road', 'Standard Set', 6, 60000, 'delivered', 2, 'paid', NOW() - INTERVAL 32 DAY, 1, NOW() - INTERVAL 30 DAY),
('Funmilayo Ajayi', '08101234525', 'funmi@email.com', 'Oyo', 'Ibadan South-West', '51 Molete, Ibadan', 'Complete Set', 7, 105000, 'delivered', 3, 'paid', NOW() - INTERVAL 35 DAY, 2, NOW() - INTERVAL 33 DAY),
('Kenneth Obi', '08101234526', 'kenneth@email.com', 'Abia', 'Aba North', '15 Azikiwe Road, Aba', 'Basic Package', 5, 25000, 'delivered', 8, 'paid', NOW() - INTERVAL 38 DAY, 1, NOW() - INTERVAL 36 DAY),
('Halima Musa', '08101234527', 'halima@email.com', 'Kaduna', 'Zaria', '22 Sokoto Road, Zaria', 'Standard Set', 3, 30000, 'delivered', 5, 'paid', NOW() - INTERVAL 40 DAY, 1, NOW() - INTERVAL 38 DAY),
('Emeka Okonkwo', '08101234528', 'emeka@email.com', 'Lagos', 'Lagos Island', '9 Broad Street, Lagos', 'Complete Set', 15, 225000, 'delivered', 1, 'paid', NOW() - INTERVAL 42 DAY, 2, NOW() - INTERVAL 40 DAY),
('Khadija Ibrahim', '08101234529', 'khadija@email.com', 'Kogi', 'Lokoja', '17 Murtala Mohammed Way', 'Basic Package', 2, 10000, 'delivered', 7, 'paid', NOW() - INTERVAL 45 DAY, 1, NOW() - INTERVAL 43 DAY),
('Daniel Oladipo', '08101234530', 'daniel@email.com', 'Ogun', 'Ijebu-Ode', '33 Folagbade Street', 'Standard Set', 4, 40000, 'delivered', 1, 'paid', NOW() - INTERVAL 48 DAY, 1, NOW() - INTERVAL 46 DAY),
('Mercy Akpan', '08101234531', 'mercy@email.com', 'Akwa Ibom', 'Ikot Ekpene', '24 Obot Idim Road', 'Complete Set', 3, 45000, 'delivered', 6, 'paid', NOW() - INTERVAL 50 DAY, 2, NOW() - INTERVAL 48 DAY),
('Yusuf Aliyu', '08101234532', 'yusuf.a@email.com', 'Katsina', 'Funtua', '40 Gusau Road, Funtua', 'Basic Package', 4, 20000, 'delivered', 2, 'paid', NOW() - INTERVAL 52 DAY, 1, NOW() - INTERVAL 50 DAY);

-- Additional older orders for reporting
INSERT INTO orders (customer_name, customer_phone, email, state, local_govt, delivery_address, package_type, quantity, total_amount, order_status, agent_id, payment_status, ordered_at, updated_by, delivered_at) VALUES
('Chisom Nnadi', '08101234533', 'chisom@email.com', 'Anambra', 'Awka', '8 Zik Avenue, Awka', 'Standard Set', 10, 100000, 'delivered', 8, 'paid', NOW() - INTERVAL 55 DAY, 1, NOW() - INTERVAL 53 DAY),
('Bashir Ahmad', '08101234534', 'bashir@email.com', 'Jigawa', 'Hadejia', '29 Hospital Road, Hadejia', 'Complete Set', 6, 90000, 'delivered', 2, 'paid', NOW() - INTERVAL 58 DAY, 2, NOW() - INTERVAL 56 DAY),
('Cynthia Okafor', '08101234535', 'cynthia@email.com', 'Rivers', 'Obio-Akpor', '13 East-West Road, PH', 'Basic Package', 7, 35000, 'delivered', 6, 'paid', NOW() - INTERVAL 60 DAY, 1, NOW() - INTERVAL 58 DAY),
('Taiwo Ogunleye', '08101234536', 'taiwo@email.com', 'Lagos', 'Ikorodu', '25 Lagos Road, Ikorodu', 'Standard Set', 8, 80000, 'delivered', 1, 'paid', NOW() - INTERVAL 62 DAY, 1, NOW() - INTERVAL 60 DAY),
('Abubakar Sale', '08101234537', 'abubakar@email.com', 'Niger', 'Suleja', '36 Abuja Road, Suleja', 'Complete Set', 4, 60000, 'delivered', 5, 'paid', NOW() - INTERVAL 65 DAY, 2, NOW() - INTERVAL 63 DAY),
('Nkechi Obi', '08101234538', 'nkechi@email.com', 'Enugu', 'Enugu South', '41 Agbani Road, Enugu', 'Basic Package', 5, 25000, 'delivered', 4, 'paid', NOW() - INTERVAL 68 DAY, 1, NOW() - INTERVAL 66 DAY),
('Rasheed Adeyemi', '08101234539', 'rasheed@email.com', 'Oyo', 'Ogbomoso North', '19 Ilorin Road, Ogbomoso', 'Standard Set', 6, 60000, 'delivered', 3, 'paid', NOW() - INTERVAL 70 DAY, 1, NOW() - INTERVAL 68 DAY),
('Joy Okoro', '08101234540', 'joy@email.com', 'Imo', 'Orlu', '27 Bank Road, Orlu', 'Complete Set', 5, 75000, 'delivered', 8, 'paid', NOW() - INTERVAL 72 DAY, 2, NOW() - INTERVAL 70 DAY),
('Salisu Mohammed', '08101234541', 'salisu@email.com', 'Kaduna', 'Kaduna South', '52 Kachia Road, Kaduna', 'Basic Package', 3, 15000, 'delivered', 5, 'paid', NOW() - INTERVAL 75 DAY, 1, NOW() - INTERVAL 73 DAY),
('Amarachi Nweke', '08101234542', 'amarachi@email.com', 'Abia', 'Umuahia North', '16 Azikiwe Road, Umuahia', 'Standard Set', 7, 70000, 'delivered', 8, 'paid', NOW() - INTERVAL 78 DAY, 1, NOW() - INTERVAL 76 DAY),
('Ibrahim Danjuma', '08101234543', 'ibrahim.d@email.com', 'Plateau', 'Jos South', '34 Yakubu Gowon Way, Jos', 'Complete Set', 9, 135000, 'delivered', 5, 'paid', NOW() - INTERVAL 80 DAY, 2, NOW() - INTERVAL 78 DAY),
('Patience Udo', '08101234544', 'patience@email.com', 'Akwa Ibom', 'Eket', '21 Oron Road, Eket', 'Basic Package', 4, 20000, 'delivered', 6, 'paid', NOW() - INTERVAL 82 DAY, 1, NOW() - INTERVAL 80 DAY),
('Musa Ibrahim', '08101234545', 'musa.i@email.com', 'Abuja', 'Kuje', '48 Airport Road, Kuje', 'Standard Set', 5, 50000, 'delivered', 7, 'paid', NOW() - INTERVAL 85 DAY, 1, NOW() - INTERVAL 83 DAY);

-- ============================================
-- 5. STOCK INVENTORY (All 37 Nigerian states)
-- ============================================
-- Note: This populates initial stock levels for all states
DELETE FROM stock_inventory WHERE id > 0;

INSERT INTO stock_inventory (state, basic_package, standard_set, complete_set) VALUES
('Abia', 150, 200, 120),
('Adamawa', 100, 150, 80),
('Akwa Ibom', 180, 220, 140),
('Anambra', 200, 250, 150),
('Bauchi', 90, 130, 70),
('Bayelsa', 110, 160, 90),
('Benue', 130, 170, 100),
('Borno', 80, 120, 60),
('Cross River', 140, 180, 110),
('Delta', 160, 200, 130),
('Ebonyi', 120, 150, 85),
('Edo', 170, 210, 135),
('Ekiti', 100, 140, 75),
('Enugu', 190, 230, 145),
('Abuja', 250, 300, 200),
('Gombe', 85, 125, 65),
('Imo', 175, 215, 140),
('Jigawa', 95, 135, 75),
('Kaduna', 210, 260, 160),
('Kano', 220, 280, 170),
('Katsina', 105, 145, 80),
('Kebbi', 90, 130, 68),
('Kogi', 125, 165, 95),
('Kwara', 135, 175, 105),
('Lagos', 300, 350, 250),
('Nasarawa', 115, 155, 88),
('Niger', 140, 180, 110),
('Ogun', 195, 240, 150),
('Ondo', 145, 185, 115),
('Osun', 155, 195, 125),
('Oyo', 205, 255, 165),
('Plateau', 150, 190, 120),
('Rivers', 225, 275, 180),
('Sokoto', 100, 140, 75),
('Taraba', 95, 135, 70),
('Yobe', 80, 120, 60),
('Zamfara', 85, 125, 65);

-- ============================================
-- 6. STOCK MOVEMENTS (Sample deliveries to agents)
-- ============================================
DELETE FROM stock_movements WHERE id > 0;

INSERT INTO stock_movements (state, package_type, quantity, movement_type, agent_id, reference, created_by, created_at) VALUES
-- Recent deliveries (last week)
('Lagos', 'Complete Set', 50, 'delivery', 1, 'DEL-2024-001', 'admin', NOW() - INTERVAL 5 DAY),
('Lagos', 'Standard Set', 80, 'delivery', 1, 'DEL-2024-002', 'admin', NOW() - INTERVAL 5 DAY),
('Kano', 'Basic Package', 100, 'delivery', 2, 'DEL-2024-003', 'admin', NOW() - INTERVAL 6 DAY),
('Kano', 'Complete Set', 40, 'delivery', 2, 'DEL-2024-004', 'admin', NOW() - INTERVAL 6 DAY),

-- Deliveries from 2 weeks ago
('Oyo', 'Standard Set', 70, 'delivery', 3, 'DEL-2024-005', 'admin', NOW() - INTERVAL 14 DAY),
('Enugu', 'Complete Set', 35, 'delivery', 4, 'DEL-2024-006', 'admin', NOW() - INTERVAL 15 DAY),
('Kaduna', 'Basic Package', 90, 'delivery', 5, 'DEL-2024-007', 'admin', NOW() - INTERVAL 16 DAY),
('Rivers', 'Standard Set', 75, 'delivery', 6, 'DEL-2024-008', 'admin', NOW() - INTERVAL 18 DAY),

-- Deliveries from last month
('Abuja', 'Complete Set', 60, 'delivery', 7, 'DEL-2024-009', 'admin', NOW() - INTERVAL 30 DAY),
('Anambra', 'Standard Set', 65, 'delivery', 8, 'DEL-2024-010', 'admin', NOW() - INTERVAL 32 DAY),
('Lagos', 'Basic Package', 120, 'delivery', 1, 'DEL-2024-011', 'admin', NOW() - INTERVAL 35 DAY),
('Kano', 'Standard Set', 85, 'delivery', 2, 'DEL-2024-012', 'admin', NOW() - INTERVAL 38 DAY),

-- Older deliveries
('Oyo', 'Complete Set', 45, 'delivery', 3, 'DEL-2024-013', 'admin', NOW() - INTERVAL 45 DAY),
('Enugu', 'Basic Package', 95, 'delivery', 4, 'DEL-2024-014', 'admin', NOW() - INTERVAL 50 DAY),
('Kaduna', 'Standard Set', 80, 'delivery', 5, 'DEL-2024-015', 'admin', NOW() - INTERVAL 55 DAY),
('Rivers', 'Complete Set', 50, 'delivery', 6, 'DEL-2024-016', 'admin', NOW() - INTERVAL 60 DAY),
('Abuja', 'Basic Package', 110, 'delivery', 7, 'DEL-2024-017', 'admin', NOW() - INTERVAL 65 DAY),
('Anambra', 'Complete Set', 38, 'delivery', 8, 'DEL-2024-018', 'admin', NOW() - INTERVAL 70 DAY);

-- ============================================
-- 7. ACTIVITY LOGS (Sample system activities)
-- ============================================
DELETE FROM activity_logs WHERE id > 0;

-- Admin login activities
INSERT INTO activity_logs (user_id, action, entity_type, entity_id, description, ip_address, user_agent, created_at) VALUES
(1, 'login', 'user', 1, 'Admin logged in successfully', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)', NOW() - INTERVAL 1 HOUR),
(1, 'login', 'user', 1, 'Admin logged in successfully', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)', NOW() - INTERVAL 1 DAY),
(1, 'login', 'user', 1, 'Admin logged in successfully', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)', NOW() - INTERVAL 2 DAY);

-- Subadmin login activities
INSERT INTO activity_logs (user_id, action, entity_type, entity_id, description, ip_address, user_agent, created_at) VALUES
(2, 'login', 'user', 2, 'Subadmin logged in successfully', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)', NOW() - INTERVAL 3 HOUR),
(2, 'login', 'user', 2, 'Subadmin logged in successfully', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)', NOW() - INTERVAL 2 DAY);

-- Order status updates
INSERT INTO activity_logs (user_id, action, entity_type, entity_id, description, old_values, new_values, ip_address, created_at) VALUES
(1, 'update', 'order', 2, 'Updated order status from pending to confirmed', '{"status":"pending"}', '{"status":"confirmed"}', '127.0.0.1', NOW() - INTERVAL 2 DAY),
(1, 'update', 'order', 3, 'Updated order status from pending to processing', '{"status":"pending"}', '{"status":"processing"}', '127.0.0.1', NOW() - INTERVAL 2 DAY),
(1, 'update', 'order', 4, 'Updated order status from confirmed to assigned', '{"status":"confirmed"}', '{"status":"assigned","agent_id":"7"}', '127.0.0.1', NOW() - INTERVAL 3 DAY),
(2, 'update', 'order', 5, 'Updated order status from assigned to in_transit', '{"status":"assigned"}', '{"status":"in_transit"}', '127.0.0.1', NOW() - INTERVAL 4 DAY),
(1, 'update', 'order', 6, 'Updated order status from in_transit to delivered', '{"status":"in_transit"}', '{"status":"delivered"}', '127.0.0.1', NOW() - INTERVAL 5 DAY),
(2, 'update', 'order', 7, 'Updated order status from in_transit to delivered', '{"status":"in_transit"}', '{"status":"delivered"}', '127.0.0.1', NOW() - INTERVAL 6 DAY);

-- Agent management activities
INSERT INTO activity_logs (user_id, action, entity_type, entity_id, description, ip_address, created_at) VALUES
(1, 'create', 'agent', 1, 'Created new delivery agent: Chukwuemeka Okafor', '127.0.0.1', NOW() - INTERVAL 60 DAY),
(1, 'create', 'agent', 2, 'Created new delivery agent: Aisha Ibrahim', '127.0.0.1', NOW() - INTERVAL 55 DAY),
(1, 'create', 'agent', 3, 'Created new delivery agent: Oluwaseun Adebayo', '127.0.0.1', NOW() - INTERVAL 50 DAY);

-- Stock movements logged
INSERT INTO activity_logs (user_id, action, entity_type, entity_id, description, new_values, ip_address, created_at) VALUES
(1, 'create', 'stock_movement', 1, 'Delivered stock to Lagos agent', '{"state":"Lagos","type":"Complete Set","qty":50}', '127.0.0.1', NOW() - INTERVAL 5 DAY),
(1, 'create', 'stock_movement', 3, 'Delivered stock to Kano agent', '{"state":"Kano","type":"Basic Package","qty":100}', '127.0.0.1', NOW() - INTERVAL 6 DAY);

-- User management activities
INSERT INTO activity_logs (user_id, action, entity_type, entity_id, description, ip_address, created_at) VALUES
(1, 'create', 'user', 2, 'Created new user: Sales Manager (subadmin)', '127.0.0.1', NOW() - INTERVAL 60 DAY),
(1, 'create', 'user', 3, 'Created new user: Delivery Agent User (agent)', '127.0.0.1', NOW() - INTERVAL 45 DAY);

-- ============================================
-- SETUP COMPLETE
-- ============================================
-- You can now login with:
--   Username: admin     | Password: admin123     (Full Access)
--   Username: subadmin  | Password: subadmin123  (No Delete/Analytics)
--   Username: agent001  | Password: agent123     (View Only)
-- ============================================
