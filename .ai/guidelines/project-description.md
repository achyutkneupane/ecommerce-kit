# Ecommerce Starter Kit

## Tech Stack
- **Framework:** Laravel 13
- **Frontend/Admin:** Filament v5, Livewire v4, Tailwind CSS v4
- **SEO:** `achyutn/laravel-seo`

## Core Architectural Rules

1. **Database & Relationships (Strictly Follow):**
    - **NO Pivot-of-Pivots:** Avoid complex multi-level pivot tables for product variations.
    - **Flat SKU Pattern:** Products have many SKUs. The `skus` table handles variants using a JSON `properties` column (e.g., `{"RAM": "16GB", "Color": "Red"}`).
    - **Multi-domain Specifications:** Use JSON columns (`specifications` or `properties`) for entity-specific data to maintain a shallow, highly flexible database structure.
    - **Currency Handling:** ALL prices, totals, and financial metrics are stored as integers (cents) in the database. Use the custom `App\Casts\Currency` cast on models to handle float/int conversion. Do not use decimal columns.

2. **Code Conventions & Standards:**
    - **Strict Typing:** All PHP files must declare `declare(strict_types=1);` at the top.
    - **Enums:** Use strongly typed Enums for all state/status management (e.g., `OrderStatus`, `PaymentMethodType`). Implement Filament's `HasColor` and `HasLabel` directly on the Enums.
    - **Model Logic:** Use Observers for automated tasks like sequence code generation and status logging. Keep controllers and Filament resources lean.
    - **Concurrency:** When generating sequential codes (like SKUs), utilize pessimistic locking (`DB::transaction` with `lockForUpdate()`) to prevent race conditions.

3. **Domain Specifics:**
    - **Orders:** Orders track their status changes automatically via an `OrderObserver` writing to an `order_logs` table.
    - **Payments:** Handled polymorphically or via JSON payloads to support various methods (COD, QR Screenshot, Stripe) without altering schema.
    - **Base Models:** Utilize `AchyutN\LaravelHelpers\Models\MediaModel` where Spatie Media Library integration is required.

## AI Generation Directives
- When generating Filament Resources, use `Grid`, `Section`, and `Repeater` components to keep the UI clean.
- Favor standard Laravel methods, Route Model Binding, and Eloquent relationships over raw SQL.
- If a feature requires filtering JSON properties, suggest programmatic grouping in PHP memory first to avoid full table scans, unless indexing is explicitly requested.
