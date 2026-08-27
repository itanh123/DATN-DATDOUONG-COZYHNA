CREATE TABLE IF NOT EXISTS "migrations"(
  "id" integer primary key autoincrement not null,
  "migration" varchar not null,
  "batch" integer not null
);
CREATE TABLE IF NOT EXISTS "roles"(
  "id" integer primary key autoincrement not null,
  "code" varchar not null,
  "name" varchar not null,
  "description" text,
  "status" tinyint(1) not null default '1',
  "created_at" datetime,
  "updated_at" datetime
);
CREATE UNIQUE INDEX "roles_code_unique" on "roles"("code");
CREATE TABLE IF NOT EXISTS "permissions"(
  "id" integer primary key autoincrement not null,
  "code" varchar not null,
  "name" varchar not null,
  "module" varchar,
  "description" text,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE UNIQUE INDEX "permissions_code_unique" on "permissions"("code");
CREATE TABLE IF NOT EXISTS "role_permissions"(
  "role_id" integer not null,
  "permission_id" integer not null,
  foreign key("role_id") references "roles"("id") on delete cascade,
  foreign key("permission_id") references "permissions"("id") on delete cascade,
  primary key("role_id", "permission_id")
);
CREATE TABLE IF NOT EXISTS "users"(
  "id" integer primary key autoincrement not null,
  "role_id" integer not null,
  "username" varchar not null,
  "name" varchar,
  "full_name" varchar,
  "email" varchar not null,
  "password" varchar not null,
  "phone" varchar,
  "avatar" varchar,
  "status" tinyint(1) not null default '1',
  "is_restricted" tinyint(1) not null default '0',
  "google_id" varchar,
  "email_verified_at" datetime,
  "gender" varchar,
  "birthday" date,
  "remember_token" varchar,
  "login_provider" varchar,
  "last_login_at" datetime,
  "last_login_ip" varchar,
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime,
  foreign key("role_id") references "roles"("id")
);
CREATE UNIQUE INDEX "users_username_unique" on "users"("username");
CREATE UNIQUE INDEX "users_email_unique" on "users"("email");
CREATE UNIQUE INDEX "users_phone_unique" on "users"("phone");
CREATE TABLE IF NOT EXISTS "customer_profiles"(
  "id" integer primary key autoincrement not null,
  "user_id" integer not null,
  "full_name" varchar,
  "gender" varchar,
  "birthday" date,
  "loyalty_points" integer not null default '0',
  "membership_level" varchar not null default 'Member',
  "total_orders" integer not null default '0',
  "total_spent" numeric not null default '0',
  "favorite_category" varchar,
  "last_order_at" datetime,
  "status" tinyint(1) not null default '1',
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("user_id") references "users"("id")
);
CREATE UNIQUE INDEX "customer_profiles_user_id_unique" on "customer_profiles"(
  "user_id"
);
CREATE TABLE IF NOT EXISTS "customer_addresses"(
  "id" integer primary key autoincrement not null,
  "customer_id" integer not null,
  "receiver_name" varchar,
  "receiver_phone" varchar,
  "province" varchar,
  "district" varchar,
  "ward" varchar,
  "address" text,
  "latitude" numeric,
  "longitude" numeric,
  "is_default" tinyint(1) not null default '0',
  "note" text,
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime,
  "province_code" varchar,
  "district_code" varchar,
  "ward_code" varchar,
  "is_saved" tinyint(1) not null default '1',
  foreign key("customer_id") references "customer_profiles"("id")
);
CREATE TABLE IF NOT EXISTS "employee_profiles"(
  "id" integer primary key autoincrement not null,
  "user_id" integer not null,
  "employee_code" varchar,
  "address" text,
  "citizen_id" varchar,
  "hire_date" date,
  "resignation_date" date,
  "salary" numeric,
  "status" tinyint(1) not null default '1',
  "emergency_contact" varchar,
  "emergency_phone" varchar,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("user_id") references "users"("id")
);
CREATE UNIQUE INDEX "employee_profiles_user_id_unique" on "employee_profiles"(
  "user_id"
);
CREATE UNIQUE INDEX "employee_profiles_employee_code_unique" on "employee_profiles"(
  "employee_code"
);
CREATE TABLE IF NOT EXISTS "shipper_profiles"(
  "id" integer primary key autoincrement not null,
  "user_id" integer not null,
  "full_name" varchar,
  "phone" varchar,
  "vehicle_type" varchar,
  "license_plate" varchar,
  "current_lat" numeric,
  "current_lng" numeric,
  "rating" numeric,
  "total_deliveries" integer,
  "status" varchar,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("user_id") references "users"("id")
);
CREATE UNIQUE INDEX "shipper_profiles_user_id_unique" on "shipper_profiles"(
  "user_id"
);
CREATE TABLE IF NOT EXISTS "categories"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "description" text,
  "image" varchar,
  "display_order" integer not null default '0',
  "status" tinyint(1) not null default '1',
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime
);
CREATE UNIQUE INDEX "categories_name_unique" on "categories"("name");
CREATE TABLE IF NOT EXISTS "products"(
  "id" integer primary key autoincrement not null,
  "category_id" integer not null,
  "code" varchar not null,
  "name" varchar not null,
  "slug" varchar,
  "image" varchar,
  "short_description" varchar,
  "description" text,
  "sold_count" integer not null default '0',
  "favorite_count" integer not null default '0',
  "is_featured" tinyint(1) not null default '0',
  "status" tinyint(1) not null default '1',
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime,
  foreign key("category_id") references "categories"("id")
);
CREATE UNIQUE INDEX "products_code_unique" on "products"("code");
CREATE UNIQUE INDEX "products_slug_unique" on "products"("slug");
CREATE TABLE IF NOT EXISTS "product_images"(
  "id" integer primary key autoincrement not null,
  "product_id" integer not null,
  "image" varchar,
  "sort_order" integer not null default '1',
  "is_primary" tinyint(1) not null default '0',
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("product_id") references "products"("id")
);
CREATE TABLE IF NOT EXISTS "sizes"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "volume_ml" integer,
  "description" varchar,
  "status" tinyint(1) not null default '1',
  "created_at" datetime,
  "updated_at" datetime
);
CREATE UNIQUE INDEX "sizes_name_unique" on "sizes"("name");
CREATE TABLE IF NOT EXISTS "product_sizes"(
  "id" integer primary key autoincrement not null,
  "product_id" integer not null,
  "size_id" integer not null,
  "selling_price" numeric,
  "cost_price" numeric,
  "calories" integer,
  "is_default" tinyint(1) not null default '0',
  "status" tinyint(1) not null default '1',
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("product_id") references "products"("id"),
  foreign key("size_id") references "sizes"("id")
);
CREATE TABLE IF NOT EXISTS "product_toppings"(
  "id" integer primary key autoincrement not null,
  "product_id" integer,
  "topping_id" integer,
  "extra_price" numeric not null default '0',
  "is_default" tinyint(1) not null default '0',
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("product_id") references "products"("id"),
  foreign key("topping_id") references "toppings"("id")
);
CREATE TABLE IF NOT EXISTS "measurement_units"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "symbol" varchar not null,
  "description" varchar,
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime
);
CREATE UNIQUE INDEX "measurement_units_name_unique" on "measurement_units"(
  "name"
);
CREATE UNIQUE INDEX "measurement_units_symbol_unique" on "measurement_units"(
  "symbol"
);
CREATE TABLE IF NOT EXISTS "suppliers"(
  "id" integer primary key autoincrement not null,
  "code" varchar,
  "name" varchar,
  "contact_person" varchar,
  "phone" varchar,
  "email" varchar,
  "tax_code" varchar,
  "bank_name" varchar,
  "bank_account" varchar,
  "address" text,
  "note" text,
  "status" tinyint(1) not null default '1',
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime
);
CREATE UNIQUE INDEX "suppliers_code_unique" on "suppliers"("code");
CREATE TABLE IF NOT EXISTS "ingredients"(
  "id" integer primary key autoincrement not null,
  "supplier_id" integer,
  "unit_id" integer not null,
  "code" varchar,
  "name" varchar,
  "category" varchar,
  "image" varchar,
  "current_stock" numeric not null default '0',
  "minimum_stock" numeric not null default '0',
  "maximum_stock" numeric,
  "reorder_level" numeric,
  "expiry_warning_days" integer,
  "cost_price" numeric,
  "description" text,
  "status" tinyint(1) not null default '1',
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime,
  "expiration_date" datetime,
  "is_fresh" tinyint(1) not null default '0',
  foreign key("supplier_id") references "suppliers"("id"),
  foreign key("unit_id") references "measurement_units"("id")
);
CREATE UNIQUE INDEX "ingredients_code_unique" on "ingredients"("code");
CREATE TABLE IF NOT EXISTS "recipes"(
  "id" integer primary key autoincrement not null,
  "product_size_id" integer not null,
  "code" varchar,
  "name" varchar,
  "preparation_time" integer not null default '0',
  "estimated_cost" numeric not null default '0',
  "instruction" text,
  "waste_percentage" numeric not null default '0',
  "status" tinyint(1) not null default '1',
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime,
  foreign key("product_size_id") references "product_sizes"("id")
);
CREATE UNIQUE INDEX "recipes_product_size_id_unique" on "recipes"(
  "product_size_id"
);
CREATE UNIQUE INDEX "recipes_code_unique" on "recipes"("code");
CREATE TABLE IF NOT EXISTS "recipe_ingredients"(
  "id" integer primary key autoincrement not null,
  "recipe_id" integer,
  "ingredient_id" integer,
  "unit_id" integer,
  "quantity" numeric,
  "step_order" integer not null default '1',
  "is_optional" tinyint(1) not null default '0',
  "note" text,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("recipe_id") references "recipes"("id"),
  foreign key("ingredient_id") references "ingredients"("id"),
  foreign key("unit_id") references "measurement_units"("id")
);
CREATE TABLE IF NOT EXISTS "purchase_orders"(
  "id" integer primary key autoincrement not null,
  "supplier_id" integer,
  "code" varchar,
  "total_amount" numeric,
  "order_date" date,
  "received_date" date,
  "status" varchar,
  "created_by" integer,
  "note" text,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("supplier_id") references "suppliers"("id"),
  foreign key("created_by") references "users"("id")
);
CREATE UNIQUE INDEX "purchase_orders_code_unique" on "purchase_orders"("code");
CREATE TABLE IF NOT EXISTS "purchase_order_items"(
  "id" integer primary key autoincrement not null,
  "purchase_order_id" integer,
  "ingredient_id" integer,
  "quantity" numeric,
  "unit_price" numeric,
  "total_price" numeric,
  "expiry_date" date,
  "batch_number" varchar,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("purchase_order_id") references "purchase_orders"("id"),
  foreign key("ingredient_id") references "ingredients"("id")
);
CREATE TABLE IF NOT EXISTS "inventory_transactions"(
  "id" integer primary key autoincrement not null,
  "ingredient_id" integer,
  "transaction_type" varchar,
  "quantity" numeric,
  "unit_id" integer,
  "before_quantity" numeric,
  "after_quantity" numeric,
  "reference_type" varchar,
  "reference_id" integer,
  "created_by" integer,
  "note" text,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("ingredient_id") references "ingredients"("id"),
  foreign key("unit_id") references "measurement_units"("id"),
  foreign key("created_by") references "users"("id")
);
CREATE TABLE IF NOT EXISTS "floors"(
  "id" integer primary key autoincrement not null,
  "code" varchar not null,
  "name" varchar not null,
  "description" text,
  "display_order" integer not null default '1',
  "status" tinyint(1) not null default '1',
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime
);
CREATE UNIQUE INDEX "floors_code_unique" on "floors"("code");
CREATE TABLE IF NOT EXISTS "table_areas"(
  "id" integer primary key autoincrement not null,
  "floor_id" integer not null,
  "code" varchar,
  "name" varchar,
  "description" text,
  "display_order" integer not null default '1',
  "status" tinyint(1) not null default '1',
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime,
  foreign key("floor_id") references "floors"("id")
);
CREATE TABLE IF NOT EXISTS "restaurant_tables"(
  "id" integer primary key autoincrement not null,
  "area_id" integer not null,
  "code" varchar not null,
  "name" varchar,
  "table_name" varchar,
  "qr_code" varchar,
  "qr_token" varchar,
  "capacity" integer,
  "seating_capacity" integer,
  "minimum_capacity" integer,
  "shape" varchar,
  "status" varchar,
  "current_session_id" integer,
  "location_x" numeric,
  "location_y" numeric,
  "note" text,
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime,
  foreign key("area_id") references "table_areas"("id")
);
CREATE UNIQUE INDEX "restaurant_tables_code_unique" on "restaurant_tables"(
  "code"
);
CREATE TABLE IF NOT EXISTS "merged_tables"(
  "id" integer primary key autoincrement not null,
  "code" varchar,
  "name" varchar,
  "capacity" integer,
  "status" tinyint(1) not null default '1',
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime
);
CREATE UNIQUE INDEX "merged_tables_code_unique" on "merged_tables"("code");
CREATE TABLE IF NOT EXISTS "merged_table_items"(
  "id" integer primary key autoincrement not null,
  "merged_table_id" integer,
  "table_id" integer,
  "is_primary" tinyint(1) not null default '0',
  foreign key("merged_table_id") references "merged_tables"("id"),
  foreign key("table_id") references "restaurant_tables"("id")
);
CREATE TABLE IF NOT EXISTS "table_sessions"(
  "id" integer primary key autoincrement not null,
  "table_id" integer,
  "merged_table_id" integer,
  "customer_id" integer,
  "opened_by" integer,
  "closed_by" integer,
  "guest_count" integer,
  "session_status" varchar,
  "opened_at" datetime,
  "closed_at" datetime,
  "note" text,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("table_id") references "restaurant_tables"("id"),
  foreign key("merged_table_id") references "merged_tables"("id"),
  foreign key("customer_id") references "customer_profiles"("id"),
  foreign key("opened_by") references "users"("id"),
  foreign key("closed_by") references "users"("id")
);
CREATE TABLE IF NOT EXISTS "reservations"(
  "id" integer primary key autoincrement not null,
  "customer_id" integer,
  "table_id" integer,
  "merged_table_id" integer,
  "reservation_code" varchar,
  "guest_name" varchar,
  "guest_phone" varchar,
  "guest_count" integer,
  "reservation_time" datetime,
  "expected_duration" integer,
  "status" varchar,
  "special_request" text,
  "created_by" integer,
  "cancelled_by" integer,
  "cancelled_reason" text,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("customer_id") references "customer_profiles"("id"),
  foreign key("table_id") references "restaurant_tables"("id"),
  foreign key("merged_table_id") references "merged_tables"("id"),
  foreign key("created_by") references "users"("id"),
  foreign key("cancelled_by") references "users"("id")
);
CREATE UNIQUE INDEX "reservations_reservation_code_unique" on "reservations"(
  "reservation_code"
);
CREATE TABLE IF NOT EXISTS "vouchers"(
  "id" integer primary key autoincrement not null,
  "code" varchar not null,
  "name" varchar,
  "description" text,
  "discount_type" varchar,
  "discount_value" numeric,
  "minimum_order" numeric,
  "maximum_discount" numeric,
  "usage_limit" integer,
  "used_count" integer not null default '0',
  "quantity" integer,
  "used" integer not null default '0',
  "start_date" datetime,
  "end_date" datetime,
  "status" tinyint(1) not null default '1',
  "created_by" integer,
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime,
  "is_one_time_use" tinyint(1) not null default '0',
  foreign key("created_by") references "users"("id")
);
CREATE UNIQUE INDEX "vouchers_code_unique" on "vouchers"("code");
CREATE TABLE IF NOT EXISTS "orders"(
  "id" integer primary key autoincrement not null,
  "customer_id" integer not null,
  "address_id" integer,
  "table_session_id" integer,
  "reservation_id" integer,
  "shipper_id" integer,
  "voucher_id" integer,
  "code" varchar,
  "order_code" varchar,
  "order_source" varchar,
  "order_type" varchar,
  "order_status" varchar,
  "status" varchar,
  "payment_method" varchar,
  "receiver_name" varchar,
  "receiver_phone" varchar,
  "delivery_address" text,
  "kitchen_note" text,
  "customer_note" text,
  "note" text,
  "subtotal" numeric,
  "discount_amount" numeric,
  "shipping_fee" numeric,
  "tax_amount" numeric,
  "total_amount" numeric,
  "ordered_at" datetime,
  "estimated_completed_at" datetime,
  "completed_at" datetime,
  "cancelled_at" datetime,
  "cancel_reason" text,
  "created_by" integer,
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime,
  "shipper_rating" integer,
  "distance_km" numeric not null default '0',
  "delivery_latitude" numeric,
  "delivery_longitude" numeric,
  "route_duration_minutes" integer,
  "distance_method" varchar,
  "shipper_proof_image" varchar,
  foreign key("customer_id") references "customer_profiles"("id"),
  foreign key("address_id") references "customer_addresses"("id"),
  foreign key("table_session_id") references "table_sessions"("id"),
  foreign key("reservation_id") references "reservations"("id"),
  foreign key("shipper_id") references "shipper_profiles"("id"),
  foreign key("voucher_id") references "vouchers"("id"),
  foreign key("created_by") references "users"("id")
);
CREATE UNIQUE INDEX "orders_code_unique" on "orders"("code");
CREATE TABLE IF NOT EXISTS "order_items"(
  "id" integer primary key autoincrement not null,
  "order_id" integer,
  "product_size_id" integer,
  "product_name" varchar,
  "size_name" varchar,
  "quantity" integer,
  "unit_price" numeric,
  "discount" numeric,
  "final_price" numeric,
  "total_price" numeric,
  "note" text,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("order_id") references "orders"("id"),
  foreign key("product_size_id") references "product_sizes"("id")
);
CREATE TABLE IF NOT EXISTS "order_item_toppings"(
  "id" integer primary key autoincrement not null,
  "order_item_id" integer,
  "topping_id" integer,
  "quantity" integer,
  "unit_price" numeric,
  "total_price" numeric,
  foreign key("order_item_id") references "order_items"("id"),
  foreign key("topping_id") references "toppings"("id")
);
CREATE TABLE IF NOT EXISTS "order_status_histories"(
  "id" integer primary key autoincrement not null,
  "order_id" integer,
  "old_status" varchar,
  "new_status" varchar,
  "changed_by" integer,
  "note" text,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("order_id") references "orders"("id"),
  foreign key("changed_by") references "users"("id")
);
CREATE TABLE IF NOT EXISTS "payments"(
  "id" integer primary key autoincrement not null,
  "order_id" integer,
  "transaction_code" varchar,
  "gateway" varchar,
  "payment_method" varchar,
  "method" varchar,
  "payment_status" varchar,
  "status" varchar,
  "amount" numeric,
  "gateway_response" text,
  "refund_amount" numeric,
  "refunded_at" datetime,
  "paid_at" datetime,
  "note" text,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("order_id") references "orders"("id")
);
CREATE TABLE IF NOT EXISTS "product_reviews"(
  "id" integer primary key autoincrement not null,
  "user_id" integer,
  "product_id" integer,
  "customer_id" integer,
  "order_id" integer,
  "rating" integer,
  "comment" text,
  "image" varchar,
  "status" varchar,
  "reply" text,
  "admin_reply" text,
  "reply_by" integer,
  "reply_at" datetime,
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime,
  foreign key("user_id") references "users"("id"),
  foreign key("product_id") references "products"("id"),
  foreign key("customer_id") references "customer_profiles"("id"),
  foreign key("order_id") references "orders"("id"),
  foreign key("reply_by") references "users"("id")
);
CREATE TABLE IF NOT EXISTS "product_complaints"(
  "id" integer primary key autoincrement not null,
  "order_item_id" integer,
  "customer_id" integer,
  "complaint_type" varchar,
  "reason" varchar,
  "description" text,
  "image" varchar,
  "status" varchar,
  "resolved_by" integer,
  "resolved_at" datetime,
  "resolution_note" text,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("order_item_id") references "order_items"("id"),
  foreign key("customer_id") references "customer_profiles"("id"),
  foreign key("resolved_by") references "users"("id")
);
CREATE TABLE IF NOT EXISTS "chat_sessions"(
  "id" integer primary key autoincrement not null,
  "user_id" integer,
  "title" varchar,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("user_id") references "users"("id")
);
CREATE TABLE IF NOT EXISTS "chat_messages"(
  "id" integer primary key autoincrement not null,
  "session_id" integer,
  "role" varchar,
  "message" text,
  "token_usage" integer,
  "created_at" datetime,
  foreign key("session_id") references "chat_sessions"("id")
);
CREATE TABLE IF NOT EXISTS "banners"(
  "id" integer primary key autoincrement not null,
  "title" varchar,
  "image" varchar,
  "link" varchar,
  "position" varchar,
  "priority" integer not null default '1',
  "start_date" datetime,
  "end_date" datetime,
  "status" tinyint(1) not null default '1',
  "created_at" datetime,
  "updated_at" datetime
);
CREATE TABLE IF NOT EXISTS "sessions"(
  "id" varchar not null,
  "user_id" integer,
  "ip_address" varchar,
  "user_agent" text,
  "payload" text not null,
  "last_activity" integer not null,
  primary key("id")
);
CREATE INDEX "sessions_user_id_index" on "sessions"("user_id");
CREATE INDEX "sessions_last_activity_index" on "sessions"("last_activity");
CREATE TABLE IF NOT EXISTS "cache"(
  "key" varchar not null,
  "value" text not null,
  "expiration" integer not null,
  primary key("key")
);
CREATE TABLE IF NOT EXISTS "cache_locks"(
  "key" varchar not null,
  "owner" varchar not null,
  "expiration" integer not null,
  primary key("key")
);
CREATE TABLE IF NOT EXISTS "jobs"(
  "id" integer primary key autoincrement not null,
  "queue" varchar not null,
  "payload" text not null,
  "attempts" integer not null,
  "reserved_at" integer,
  "available_at" integer not null,
  "created_at" integer not null
);
CREATE INDEX "jobs_queue_index" on "jobs"("queue");
CREATE TABLE IF NOT EXISTS "favorite_products"(
  "id" integer primary key autoincrement not null,
  "user_id" integer not null,
  "product_id" integer not null,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("user_id") references "users"("id") on delete cascade,
  foreign key("product_id") references "products"("id") on delete cascade
);
CREATE UNIQUE INDEX "favorite_products_user_id_product_id_unique" on "favorite_products"(
  "user_id",
  "product_id"
);
CREATE TABLE IF NOT EXISTS "dining_tables"(
  "id" integer primary key autoincrement not null,
  "table_number" varchar not null,
  "qr_code_token" varchar not null,
  "is_active" tinyint(1) not null default '1',
  "created_at" datetime,
  "updated_at" datetime
);
CREATE UNIQUE INDEX "dining_tables_table_number_unique" on "dining_tables"(
  "table_number"
);
CREATE UNIQUE INDEX "dining_tables_qr_code_token_unique" on "dining_tables"(
  "qr_code_token"
);
CREATE TABLE IF NOT EXISTS "table_calls"(
  "id" integer primary key autoincrement not null,
  "table_id" integer not null,
  "status" varchar not null default 'PENDING',
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("table_id") references "restaurant_tables"("id") on delete cascade
);
CREATE TABLE IF NOT EXISTS "carts"(
  "id" integer primary key autoincrement not null,
  "customer_id" integer not null,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("customer_id") references "customer_profiles"("id") on delete cascade
);
CREATE TABLE IF NOT EXISTS "cart_items"(
  "id" integer primary key autoincrement not null,
  "cart_id" integer not null,
  "product_id" integer,
  "product_size_id" integer,
  "quantity" integer not null default '1',
  "unit_price" numeric not null default '0',
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("cart_id") references "carts"("id") on delete cascade,
  foreign key("product_id") references "products"("id") on delete cascade,
  foreign key("product_size_id") references "product_sizes"("id") on delete cascade
);
CREATE TABLE IF NOT EXISTS "cart_item_toppings"(
  "id" integer primary key autoincrement not null,
  "cart_item_id" integer not null,
  "topping_id" integer not null,
  "quantity" integer not null default '1',
  "price" numeric not null default '0',
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("cart_item_id") references "cart_items"("id") on delete cascade,
  foreign key("topping_id") references "toppings"("id") on delete cascade
);
CREATE TABLE IF NOT EXISTS "notifications"(
  "id" integer primary key autoincrement not null,
  "user_id" integer not null,
  "title" varchar not null,
  "content" text not null,
  "type" varchar not null default 'info',
  "is_read" tinyint(1) not null default '0',
  "read_at" datetime,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("user_id") references "users"("id") on delete cascade
);
CREATE TABLE IF NOT EXISTS "settings"(
  "id" integer primary key autoincrement not null,
  "key" varchar not null,
  "value" text,
  "description" varchar,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE UNIQUE INDEX "settings_key_unique" on "settings"("key");
CREATE TABLE IF NOT EXISTS "order_complaints"(
  "id" integer primary key autoincrement not null,
  "order_id" integer not null,
  "customer_id" integer not null,
  "incident_time" datetime,
  "target_person" varchar,
  "description" text not null,
  "images" text,
  "status" varchar check("status" in('PENDING', 'VIEWED', 'REPLIED')) not null default 'PENDING',
  "is_viewed_by_admin" tinyint(1) not null default '0',
  "admin_reply" text,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("order_id") references "orders"("id") on delete cascade,
  foreign key("customer_id") references "customer_profiles"("id") on delete cascade
);
CREATE TABLE IF NOT EXISTS "ai_knowledge_base"(
  "id" integer primary key autoincrement not null,
  "category" varchar not null,
  "question_pattern" text not null,
  "knowledge" text not null,
  "source" varchar not null default 'conversation',
  "confidence" integer not null default '50',
  "usage_count" integer not null default '0',
  "learned_from_session_id" integer,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE INDEX "ai_knowledge_base_category_index" on "ai_knowledge_base"(
  "category"
);
CREATE INDEX "ai_knowledge_base_confidence_index" on "ai_knowledge_base"(
  "confidence"
);
CREATE TABLE IF NOT EXISTS "ai_message_feedback"(
  "id" integer primary key autoincrement not null,
  "message_id" integer not null,
  "feedback" varchar check("feedback" in('positive', 'negative')) not null,
  "comment" text,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("message_id") references "chat_messages"("id") on delete cascade
);
CREATE UNIQUE INDEX "ai_message_feedback_message_id_unique" on "ai_message_feedback"(
  "message_id"
);
CREATE TABLE IF NOT EXISTS "toppings"(
  "id" integer primary key autoincrement not null,
  "code" varchar,
  "name" varchar,
  "image" varchar,
  "description" text,
  "price" numeric,
  "max_quantity" integer not null default('5'),
  "status" tinyint(1) not null default('1'),
  "created_at" datetime,
  "updated_at" datetime,
  "deleted_at" datetime,
  "ingredient_id" integer,
  "ingredient_quantity" numeric not null default '1',
  foreign key("ingredient_id") references "ingredients"("id") on delete set null
);
CREATE UNIQUE INDEX "toppings_code_unique" on "toppings"("code");

INSERT INTO migrations VALUES(1,'2026_07_01_000001_create_auth_module_tables',1);
INSERT INTO migrations VALUES(2,'2026_07_01_000002_create_profiles_module_tables',1);
INSERT INTO migrations VALUES(3,'2026_07_01_000003_create_products_module_tables',1);
INSERT INTO migrations VALUES(4,'2026_07_01_000004_create_inventory_module_tables',1);
INSERT INTO migrations VALUES(5,'2026_07_01_000005_create_table_module_tables',1);
INSERT INTO migrations VALUES(6,'2026_07_01_000006_create_order_module_tables',1);
INSERT INTO migrations VALUES(7,'2026_07_01_000007_create_feedback_module_tables',1);
INSERT INTO migrations VALUES(8,'2026_07_01_000008_create_misc_module_tables',1);
INSERT INTO migrations VALUES(9,'2026_07_14_075836_create_favorite_products_table',1);
INSERT INTO migrations VALUES(10,'2026_07_15_074810_add_expiration_and_fresh_to_ingredients_table',1);
INSERT INTO migrations VALUES(11,'2026_07_16_043533_add_name_to_users_table',1);
INSERT INTO migrations VALUES(12,'2026_07_16_100500_add_google_id_to_users_table',1);
INSERT INTO migrations VALUES(13,'2026_07_16_110000_create_dining_tables_table',1);
INSERT INTO migrations VALUES(14,'2026_07_16_110001_add_order_type_to_orders_table',1);
INSERT INTO migrations VALUES(15,'2026_07_17_092847_create_table_calls_table',1);
INSERT INTO migrations VALUES(16,'2026_07_18_091346_add_cancel_reason_to_orders_table',1);
INSERT INTO migrations VALUES(17,'2026_07_30_080411_add_admin_reply_to_product_reviews_table',1);
INSERT INTO migrations VALUES(18,'2026_07_30_100001_create_floors_table',1);
INSERT INTO migrations VALUES(19,'2026_07_30_100002_create_table_areas_table',1);
INSERT INTO migrations VALUES(20,'2026_07_30_100003_create_restaurant_tables_table',1);
INSERT INTO migrations VALUES(21,'2026_07_30_100004_create_merged_tables_table',1);
INSERT INTO migrations VALUES(22,'2026_07_30_100005_create_table_sessions_table',1);
INSERT INTO migrations VALUES(23,'2026_07_30_110001_create_product_images_table',1);
INSERT INTO migrations VALUES(24,'2026_07_30_110002_create_toppings_table',1);
INSERT INTO migrations VALUES(25,'2026_07_30_110003_create_order_item_toppings_table',1);
INSERT INTO migrations VALUES(26,'2026_07_30_110004_create_employee_profiles_table',1);
INSERT INTO migrations VALUES(27,'2026_07_30_110005_create_product_complaints_table',1);
INSERT INTO migrations VALUES(28,'2026_07_30_110006_create_order_status_histories_table',1);
INSERT INTO migrations VALUES(29,'2026_07_30_110007_create_reservations_table',1);
INSERT INTO migrations VALUES(30,'2026_07_30_110008_create_inventory_transactions_table',1);
INSERT INTO migrations VALUES(31,'2026_07_30_110009_create_chat_tables',1);
INSERT INTO migrations VALUES(32,'2026_07_30_110010_create_banners_table',1);
INSERT INTO migrations VALUES(33,'2026_07_31_140000_add_codes_to_customer_addresses_table',1);
INSERT INTO migrations VALUES(34,'2026_07_31_140500_add_is_saved_to_customer_addresses_table',1);
INSERT INTO migrations VALUES(35,'2026_08_01_023839_create_carts_and_cart_items_tables',1);
INSERT INTO migrations VALUES(36,'2026_08_01_023840_create_cart_item_toppings_table',1);
INSERT INTO migrations VALUES(37,'2026_08_03_153310_create_notifications_table',1);
INSERT INTO migrations VALUES(38,'2026_08_06_020833_add_is_primary_to_merged_table_items_table',2);
INSERT INTO migrations VALUES(39,'2026_08_06_140000_create_settings_table',3);
INSERT INTO migrations VALUES(40,'2026_08_07_100359_create_order_complaints_table',4);
INSERT INTO migrations VALUES(41,'2026_08_07_100400_add_shipper_rating_to_orders_table',4);
INSERT INTO migrations VALUES(42,'2026_08_07_042621_add_distance_km_to_orders_table',5);
INSERT INTO migrations VALUES(43,'2026_08_17_013530_add_is_one_time_use_to_vouchers_table',6);
INSERT INTO migrations VALUES(44,'2026_08_17_160000_create_ai_learning_tables',7);
INSERT INTO migrations VALUES(45,'2026_08_19_150842_add_delivery_coordinates_to_orders_table',7);
INSERT INTO migrations VALUES(46,'2026_08_19_163826_add_routing_info_to_orders_table',7);
INSERT INTO migrations VALUES(47,'2026_08_20_023459_add_shipper_proof_image_to_orders_table',7);
INSERT INTO migrations VALUES(48,'2026_08_21_041418_add_ingredient_id_to_toppings_table',7);
INSERT INTO migrations VALUES(49,'2026_08_21_083430_add_ingredient_quantity_to_toppings_table',7);
