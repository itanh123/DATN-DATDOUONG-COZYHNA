-- =====================================================
-- AUTHENTICATION & AUTHORIZATION
-- =====================================================

CREATE TABLE roles (
    id BIGINT AUTO_INCREMENT PRIMARY KEY COMMENT 'Khóa chính',
    code VARCHAR(50) NOT NULL UNIQUE COMMENT 'Mã vai trò',
    name VARCHAR(100) NOT NULL COMMENT 'Tên vai trò',
    description TEXT,
    status BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) COMMENT 'Danh sách vai trò';

-- =====================================================

CREATE TABLE permissions (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(100) NOT NULL UNIQUE,
    name VARCHAR(255) NOT NULL,
    module VARCHAR(100),
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) COMMENT 'Danh sách quyền';

-- =====================================================

CREATE TABLE role_permissions (
    role_id BIGINT NOT NULL,
    permission_id BIGINT NOT NULL,
    PRIMARY KEY (role_id, permission_id),
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE,
    FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE
) COMMENT 'Quan hệ Role - Permission';

-- =====================================================

CREATE TABLE users (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    role_id BIGINT NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    full_name VARCHAR(255),
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20) UNIQUE,
    avatar VARCHAR(255),
    email_verified_at TIMESTAMP NULL,
    gender VARCHAR(20),
    birthday DATE,
    remember_token VARCHAR(255),
    login_provider VARCHAR(30) COMMENT 'LOCAL | GOOGLE | FACEBOOK',
    last_login_at TIMESTAMP NULL,
    last_login_ip VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    FOREIGN KEY (role_id) REFERENCES roles(id)
) COMMENT 'Thông tin tài khoản';

-- =====================================================
-- CUSTOMER MODULE
-- =====================================================

CREATE TABLE customer_profiles (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT NOT NULL UNIQUE,
    loyalty_points INT DEFAULT 0,
    membership_level VARCHAR(30) DEFAULT 'Member',
    total_orders INT DEFAULT 0,
    total_spent DECIMAL(12,2) DEFAULT 0.00,
    favorite_category VARCHAR(100),
    last_order_at TIMESTAMP NULL,
    status BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
) COMMENT 'Thông tin khách hàng';

-- =====================================================

CREATE TABLE customer_addresses (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    customer_id BIGINT NOT NULL,
    province VARCHAR(100),
    district VARCHAR(100),
    ward VARCHAR(100),
    address TEXT,
    latitude DECIMAL(10,7),
    longitude DECIMAL(10,7),
    is_default BOOLEAN DEFAULT FALSE,
    note TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    FOREIGN KEY (customer_id) REFERENCES customer_profiles(id)
) COMMENT 'Địa chỉ giao hàng';

-- =====================================================
-- EMPLOYEE MODULE
-- =====================================================

CREATE TABLE employee_profiles (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT NOT NULL UNIQUE,
    employee_code VARCHAR(30) UNIQUE,
    address TEXT,
    citizen_id VARCHAR(20),
    hire_date DATE,
    resignation_date DATE,
    salary DECIMAL(12,2),
    status BOOLEAN DEFAULT TRUE,
    emergency_contact VARCHAR(255),
    emergency_phone VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
) COMMENT 'Thông tin nhân viên';

-- =====================================================
-- PRODUCT MODULE
-- =====================================================

CREATE TABLE categories (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    image VARCHAR(255),
    display_order INT DEFAULT 0,
    status BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL
) COMMENT 'Danh mục sản phẩm';

-- =====================================================

CREATE TABLE products (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    category_id BIGINT NOT NULL,
    code VARCHAR(30) NOT NULL UNIQUE,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE,
    short_description VARCHAR(500),
    description TEXT,
    sold_count INT DEFAULT 0,
    favorite_count INT DEFAULT 0,
    is_featured BOOLEAN DEFAULT FALSE,
    status BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    FOREIGN KEY (category_id) REFERENCES categories(id)
) COMMENT 'Thông tin sản phẩm';

-- =====================================================

CREATE TABLE product_images (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    product_id BIGINT NOT NULL,
    image VARCHAR(255),
    sort_order INT DEFAULT 1,
    is_primary BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) COMMENT 'Ảnh sản phẩm';

-- =====================================================

CREATE TABLE sizes (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(30) NOT NULL UNIQUE,
    volume_ml INT,
    description VARCHAR(255),
    status BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) COMMENT 'Danh sách size';

-- =====================================================

CREATE TABLE product_sizes (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    product_id BIGINT NOT NULL,
    size_id BIGINT NOT NULL,
    selling_price DECIMAL(12,2),
    cost_price DECIMAL(12,2),
    calories INT,
    is_default BOOLEAN DEFAULT FALSE,
    status BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id),
    FOREIGN KEY (size_id) REFERENCES sizes(id)
) COMMENT 'Giá bán theo từng size';

-- =====================================================
-- TOPPING
-- =====================================================

CREATE TABLE toppings (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(30) UNIQUE,
    name VARCHAR(255),
    image VARCHAR(255),
    description TEXT,
    price DECIMAL(12,2),
    max_quantity INT DEFAULT 5,
    status BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL
) COMMENT 'Danh sách topping';

-- =====================================================

CREATE TABLE product_toppings (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    product_id BIGINT,
    topping_id BIGINT,
    extra_price DECIMAL(12,2) DEFAULT 0.00,
    is_default BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id),
    FOREIGN KEY (topping_id) REFERENCES toppings(id)
) COMMENT 'Topping áp dụng cho sản phẩm';

-- =====================================================
-- REVIEW
-- =====================================================

CREATE TABLE product_reviews (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    product_id BIGINT,
    customer_id BIGINT,
    rating INT,
    comment TEXT,
    image VARCHAR(255),
    reply TEXT,
    reply_by BIGINT,
    reply_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    FOREIGN KEY (product_id) REFERENCES products(id),
    FOREIGN KEY (customer_id) REFERENCES customer_profiles(id),
    FOREIGN KEY (reply_by) REFERENCES users(id)
) COMMENT 'Đánh giá sản phẩm';

-- =====================================================
-- PRODUCT COMPLAINT
-- =====================================================

CREATE TABLE product_complaints (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    order_item_id BIGINT,
    customer_id BIGINT,
    complaint_type VARCHAR(100),
    reason VARCHAR(255),
    description TEXT,
    image VARCHAR(255),
    status VARCHAR(30),
    resolved_by BIGINT,
    resolved_at TIMESTAMP NULL,
    resolution_note TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customer_profiles(id),
    FOREIGN KEY (resolved_by) REFERENCES users(id)
    -- order_item_id FK will be added after order_items table creation
) COMMENT 'Khiếu nại sản phẩm';

-- =====================================================
-- MEASUREMENT UNIT MODULE
-- =====================================================

CREATE TABLE measurement_units (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    symbol VARCHAR(20) NOT NULL UNIQUE,
    description VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) COMMENT 'Đơn vị đo';

-- =====================================================
-- SUPPLIER MODULE
-- =====================================================

CREATE TABLE suppliers (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(30) UNIQUE,
    name VARCHAR(255),
    contact_person VARCHAR(255),
    phone VARCHAR(20),
    email VARCHAR(255),
    tax_code VARCHAR(30),
    bank_name VARCHAR(255),
    bank_account VARCHAR(100),
    address TEXT,
    note TEXT,
    status BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL
) COMMENT 'Nhà cung cấp';

-- =====================================================
-- INGREDIENT MODULE
-- =====================================================

CREATE TABLE ingredients (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    supplier_id BIGINT,
    unit_id BIGINT NOT NULL,
    code VARCHAR(30) UNIQUE,
    name VARCHAR(255),
    category VARCHAR(100),
    image VARCHAR(255),
    current_stock DECIMAL(10,2) DEFAULT 0.00,
    minimum_stock DECIMAL(10,2) DEFAULT 0.00,
    maximum_stock DECIMAL(10,2),
    reorder_level DECIMAL(10,2),
    expiry_warning_days INT,
    cost_price DECIMAL(12,2),
    description TEXT,
    status BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    FOREIGN KEY (supplier_id) REFERENCES suppliers(id),
    FOREIGN KEY (unit_id) REFERENCES measurement_units(id)
) COMMENT 'Nguyên liệu';

-- =====================================================
-- RECIPE MODULE
-- =====================================================

CREATE TABLE recipes (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    product_size_id BIGINT NOT NULL UNIQUE,
    code VARCHAR(30) UNIQUE,
    name VARCHAR(255),
    preparation_time INT DEFAULT 0,
    estimated_cost DECIMAL(12,2) DEFAULT 0.00,
    instruction TEXT,
    waste_percentage DECIMAL(5,2) DEFAULT 0.00,
    status BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    FOREIGN KEY (product_size_id) REFERENCES product_sizes(id)
) COMMENT 'Công thức pha chế';

-- =====================================================

CREATE TABLE recipe_ingredients (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    recipe_id BIGINT,
    ingredient_id BIGINT,
    unit_id BIGINT,
    quantity DECIMAL(10,2),
    step_order INT DEFAULT 1,
    is_optional BOOLEAN DEFAULT FALSE,
    note TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (recipe_id) REFERENCES recipes(id),
    FOREIGN KEY (ingredient_id) REFERENCES ingredients(id),
    FOREIGN KEY (unit_id) REFERENCES measurement_units(id)
) COMMENT 'Chi tiết công thức';

-- =====================================================
-- PURCHASE ORDER
-- =====================================================

CREATE TABLE purchase_orders (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    supplier_id BIGINT,
    code VARCHAR(30) UNIQUE,
    total_amount DECIMAL(12,2),
    order_date DATE,
    received_date DATE,
    status VARCHAR(30) COMMENT 'PENDING | RECEIVED | CANCELLED',
    created_by BIGINT,
    note TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (supplier_id) REFERENCES suppliers(id),
    FOREIGN KEY (created_by) REFERENCES users(id)
) COMMENT 'Phiếu nhập kho';

-- =====================================================

CREATE TABLE purchase_order_items (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    purchase_order_id BIGINT,
    ingredient_id BIGINT,
    quantity DECIMAL(10,2),
    unit_price DECIMAL(12,2),
    total_price DECIMAL(12,2),
    expiry_date DATE,
    batch_number VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (purchase_order_id) REFERENCES purchase_orders(id),
    FOREIGN KEY (ingredient_id) REFERENCES ingredients(id)
) COMMENT 'Chi tiết phiếu nhập';

-- =====================================================
-- INVENTORY TRANSACTIONS
-- =====================================================

CREATE TABLE inventory_transactions (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    ingredient_id BIGINT,
    transaction_type VARCHAR(30) COMMENT 'IMPORT | EXPORT | ADJUST | WASTE',
    quantity DECIMAL(10,2),
    unit_id BIGINT,
    before_quantity DECIMAL(10,2),
    after_quantity DECIMAL(10,2),
    reference_type VARCHAR(50) COMMENT 'PURCHASE | ORDER | RECIPE | MANUAL',
    reference_id BIGINT,
    created_by BIGINT,
    note TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ingredient_id) REFERENCES ingredients(id),
    FOREIGN KEY (unit_id) REFERENCES measurement_units(id),
    FOREIGN KEY (created_by) REFERENCES users(id)
) COMMENT 'Lịch sử nhập xuất kho';

-- =====================================================
-- FLOOR MODULE
-- =====================================================

CREATE TABLE floors (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(30) NOT NULL UNIQUE,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    display_order INT DEFAULT 1,
    status BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL
) COMMENT 'Quản lý tầng nhà hàng';

-- =====================================================
-- TABLE AREA MODULE
-- =====================================================

CREATE TABLE table_areas (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    floor_id BIGINT NOT NULL,
    code VARCHAR(30),
    name VARCHAR(100),
    description TEXT,
    display_order INT DEFAULT 1,
    status BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    FOREIGN KEY (floor_id) REFERENCES floors(id)
) COMMENT 'Khu vực trong từng tầng';

-- =====================================================
-- RESTAURANT TABLE MODULE
-- =====================================================

CREATE TABLE restaurant_tables (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    area_id BIGINT NOT NULL,
    code VARCHAR(30) NOT NULL UNIQUE,
    table_name VARCHAR(100),
    qr_code VARCHAR(255),
    capacity INT,
    minimum_capacity INT,
    shape VARCHAR(30) COMMENT 'ROUND | SQUARE | RECTANGLE',
    status VARCHAR(30) COMMENT 'AVAILABLE | OCCUPIED | RESERVED | DISABLED',
    current_session_id BIGINT,
    location_x DECIMAL(8,2),
    location_y DECIMAL(8,2),
    note TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    FOREIGN KEY (area_id) REFERENCES table_areas(id)
) COMMENT 'Danh sách bàn';

-- =====================================================
-- MERGED TABLE MODULE
-- =====================================================

CREATE TABLE merged_tables (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(30) UNIQUE,
    name VARCHAR(100),
    capacity INT,
    status BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL
) COMMENT 'Thông tin bàn ghép';

-- =====================================================

CREATE TABLE merged_table_items (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    merged_table_id BIGINT,
    table_id BIGINT,
    FOREIGN KEY (merged_table_id) REFERENCES merged_tables(id),
    FOREIGN KEY (table_id) REFERENCES restaurant_tables(id)
) COMMENT 'Danh sách bàn thuộc bàn ghép';

-- =====================================================
-- TABLE SESSION MODULE
-- =====================================================

CREATE TABLE table_sessions (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    table_id BIGINT,
    merged_table_id BIGINT,
    customer_id BIGINT,
    opened_by BIGINT,
    closed_by BIGINT,
    guest_count INT,
    session_status VARCHAR(30) COMMENT 'OPEN | CLOSED',
    opened_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    closed_at TIMESTAMP NULL,
    note TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (table_id) REFERENCES restaurant_tables(id),
    FOREIGN KEY (merged_table_id) REFERENCES merged_tables(id),
    FOREIGN KEY (customer_id) REFERENCES customer_profiles(id),
    FOREIGN KEY (opened_by) REFERENCES users(id),
    FOREIGN KEY (closed_by) REFERENCES users(id)
) COMMENT 'Phiên sử dụng bàn';

-- Cập nhật FK current_session_id trong restaurant_tables
ALTER TABLE restaurant_tables ADD FOREIGN KEY (current_session_id) REFERENCES table_sessions(id);

-- =====================================================
-- RESERVATION MODULE
-- =====================================================

CREATE TABLE reservations (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    customer_id BIGINT,
    table_id BIGINT,
    merged_table_id BIGINT,
    reservation_code VARCHAR(30) UNIQUE,
    guest_name VARCHAR(255),
    guest_phone VARCHAR(20),
    guest_count INT,
    reservation_time TIMESTAMP,
    expected_duration INT,
    status VARCHAR(30) COMMENT 'PENDING | CONFIRMED | CANCELLED | COMPLETED',
    special_request TEXT,
    created_by BIGINT,
    cancelled_by BIGINT,
    cancelled_reason TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customer_profiles(id),
    FOREIGN KEY (table_id) REFERENCES restaurant_tables(id),
    FOREIGN KEY (merged_table_id) REFERENCES merged_tables(id),
    FOREIGN KEY (created_by) REFERENCES users(id),
    FOREIGN KEY (cancelled_by) REFERENCES users(id)
) COMMENT 'Đặt bàn';

-- =====================================================
-- ORDER MODULE
-- =====================================================

CREATE TABLE vouchers (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) NOT NULL UNIQUE,
    name VARCHAR(255),
    description TEXT,
    discount_type VARCHAR(20) COMMENT 'PERCENT | AMOUNT',
    discount_value DECIMAL(12,2),
    minimum_order DECIMAL(12,2),
    maximum_discount DECIMAL(12,2),
    usage_limit INT,
    used_count INT DEFAULT 0,
    start_date DATETIME,
    end_date DATETIME,
    status BOOLEAN DEFAULT TRUE,
    created_by BIGINT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    FOREIGN KEY (created_by) REFERENCES users(id)
) COMMENT 'Voucher giảm giá';

-- =====================================================

CREATE TABLE shipper_profiles (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNIQUE,
    vehicle_type VARCHAR(50),
    license_plate VARCHAR(30),
    current_lat DECIMAL(10,7),
    current_lng DECIMAL(10,7),
    rating DECIMAL(3,2),
    total_deliveries INT,
    status VARCHAR(30) COMMENT 'ONLINE | OFFLINE | BUSY',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
) COMMENT 'Thông tin shipper';

-- =====================================================

CREATE TABLE orders (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    customer_id BIGINT NOT NULL,
    table_session_id BIGINT,
    reservation_id BIGINT,
    shipper_id BIGINT,
    voucher_id BIGINT,
    code VARCHAR(30) NOT NULL UNIQUE,
    order_source VARCHAR(30) COMMENT 'APP | WEBSITE | QR | STAFF | PHONE',
    order_type VARCHAR(30) COMMENT 'DINE_IN | TAKE_AWAY | DELIVERY',
    order_status VARCHAR(30) COMMENT 'PENDING | CONFIRMED | PREPARING | READY | DELIVERING | COMPLETED | CANCELLED',
    receiver_name VARCHAR(255),
    receiver_phone VARCHAR(20),
    delivery_address TEXT,
    kitchen_note TEXT,
    customer_note TEXT,
    subtotal DECIMAL(12,2),
    discount_amount DECIMAL(12,2),
    shipping_fee DECIMAL(12,2),
    tax_amount DECIMAL(12,2),
    total_amount DECIMAL(12,2),
    estimated_completed_at TIMESTAMP NULL,
    completed_at TIMESTAMP NULL,
    cancelled_at TIMESTAMP NULL,
    cancel_reason TEXT,
    created_by BIGINT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    FOREIGN KEY (customer_id) REFERENCES customer_profiles(id),
    FOREIGN KEY (table_session_id) REFERENCES table_sessions(id),
    FOREIGN KEY (reservation_id) REFERENCES reservations(id),
    FOREIGN KEY (shipper_id) REFERENCES shipper_profiles(id),
    FOREIGN KEY (voucher_id) REFERENCES vouchers(id),
    FOREIGN KEY (created_by) REFERENCES users(id)
) COMMENT 'Thông tin đơn hàng';

-- =====================================================

CREATE TABLE order_items (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    order_id BIGINT,
    product_size_id BIGINT,
    product_name VARCHAR(255),
    size_name VARCHAR(50),
    quantity INT,
    unit_price DECIMAL(12,2),
    discount DECIMAL(12,2),
    final_price DECIMAL(12,2),
    note TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (product_size_id) REFERENCES product_sizes(id)
) COMMENT 'Chi tiết đơn hàng';

-- =====================================================

CREATE TABLE order_item_toppings (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    order_item_id BIGINT,
    topping_id BIGINT,
    quantity INT,
    unit_price DECIMAL(12,2),
    total_price DECIMAL(12,2),
    FOREIGN KEY (order_item_id) REFERENCES order_items(id),
    FOREIGN KEY (topping_id) REFERENCES toppings(id)
) COMMENT 'Topping khách chọn';

-- =====================================================

CREATE TABLE order_status_histories (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    order_id BIGINT,
    old_status VARCHAR(30),
    new_status VARCHAR(30),
    changed_by BIGINT,
    note TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (changed_by) REFERENCES users(id)
) COMMENT 'Lịch sử thay đổi trạng thái đơn';

-- =====================================================
-- PAYMENT MODULE
-- =====================================================

CREATE TABLE payments (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    order_id BIGINT UNIQUE,
    transaction_code VARCHAR(100),
    gateway VARCHAR(30) COMMENT 'CASH | VNPAY | MOMO | BANKING',
    payment_method VARCHAR(30),
    payment_status VARCHAR(30),
    amount DECIMAL(12,2),
    gateway_response TEXT,
    refund_amount DECIMAL(12,2),
    refunded_at TIMESTAMP NULL,
    paid_at TIMESTAMP NULL,
    note TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id)
) COMMENT 'Thanh toán';

-- =====================================================
-- Thêm FK order_item_id cho product_complaints
-- =====================================================

ALTER TABLE product_complaints ADD FOREIGN KEY (order_item_id) REFERENCES order_items(id);

-- =====================================================
-- AI CHAT MODULE
-- =====================================================

CREATE TABLE chat_sessions (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT,
    title VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
) COMMENT 'Phiên trò chuyện AI';

-- =====================================================

CREATE TABLE chat_messages (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    session_id BIGINT,
    role VARCHAR(30) COMMENT 'USER | ASSISTANT | SYSTEM',
    message TEXT,
    token_usage INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (session_id) REFERENCES chat_sessions(id)
) COMMENT 'Lịch sử tin nhắn AI';

-- =====================================================
-- BANNER MODULE
-- =====================================================

CREATE TABLE banners (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255),
    image VARCHAR(255),
    link VARCHAR(255),
    position VARCHAR(50),
    priority INT DEFAULT 1,
    start_date DATETIME,
    end_date DATETIME,
    status BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) COMMENT 'Banner hiển thị';

-- =====================================================
-- END
-- =====================================================