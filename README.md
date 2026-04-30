# Order Delivery Platform - Backend API

## Overview
The Order Delivery Platform Backend is a robust, modular RESTful API built to power the entire ordering ecosystem. It supports multiple client applications including the Customer App and the Admin/Vendor Dashboard. The backend architecture separates concerns into specialized modules, ensuring scalability, maintainability, and secure data handling.

## System Architecture
This API is designed using a domain-driven approach, compartmentalizing functionality based on user roles and features.

### Core Modules
* **`admin/` & `super_admin/`**: High-level administrative operations, system-wide configurations, and analytics.
* **`vendor/`**: Vendor-specific operations, including store management, item catalog management, and order acceptance workflows.
* **`user_*/`**: Modules handling customer functionality, including:
  * `user_auth/`: Secure user registration and session management.
  * `user_cart/` & `user_orders/`: Core checkout pipeline, cart validation, and real-time order tracking.
  * `user_addresses/`, `user_favorites/`, `user_profile/`: Account management and user preferences.
  * `user_items/` & `user_home/`: Product catalog, search, and dynamic feed generation.
* **`core/` & `config/`**: Database connections, shared utilities, middleware, and application constants.
* **`upload/`**: Centralized media processing and secure file storage (e.g., product images, profile pictures).
* **`tests/`**: Unit and integration test suites.

## Features
* **Role-Based Access Control (RBAC):** Strict permissions enforcing secure access boundaries between Super Admins, Regional Admins, Vendors, and Customers.
* **Complex Routing System:** Regional branch and zone-based routing logic for order fulfillment.
* **Real-time Notifications:** Integration for push notifications alerting users to order status changes and vendors to new requests.
* **Robust Order Pipeline:** Enforced sequential state machine for orders (Pending → Admin Confirmed → Vendor Accepted → Delivering → Delivered).

## Getting Started

### Prerequisites
* PHP 8.x+
* MySQL 8.x+
* A web server (Apache/Nginx) configured to serve the `backendOrder` directory
* Composer (if utilizing external PHP packages)

### Installation
1. Clone the repository and navigate to the backend directory.
2. Ensure the `config/` directory contains the correct database credentials.
3. Import the database schema from the `sql/` directory into your MySQL instance.
4. Set appropriate read/write permissions on the `upload/` directory.

## Security Practices
* **Authentication:** Token-based authentication required for all protected endpoints.
* **Data Sanitization:** All incoming requests are parameterized and sanitized to prevent SQL injection and XSS.
* **Environment Configuration:** Sensitive credentials must be stored outside of the public document root.
