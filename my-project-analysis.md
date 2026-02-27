# Unique Studio - Project Analysis & Implementation Plan

## 1. Goal

Implement a deep, multi-level category hierarchy (e.g., `Category -> Subcategory -> Sub-subcategory -> Product`) without changing the existing `unique-studio-simplified-plan.md` file. The goal is to make the navigation dynamic, update product fetching to support parents/children, and allow scalable category growth.

## 2. Current Architecture Issues

Based on the review of `database/schema.sql`, `products/index.php`, `includes/functions.php`, and `inc/header.php`:

- **Database:** `categories` table has a `parent_id`, meaning it supports subcategories. However, the exact data seeded currently is flat.
- **Frontend Menu (`inc/header.php`):** The "CATEGORY" dropdown is entirely hardcoded using standalone PHP files (`business-id-cards.php`, `cup-printing.php`, etc.).
- **Product Fetching (`includes/functions.php`):**
  - `getAllProducts()` filters by an exact `category_id`. It does not support nested fetching (e.g., pulling products from subcategories if a parent is selected).
  - `getCategories()` pulls everything, ignoring hierarchy or parent-child relationships.
- **Category Files:** Scattered files (`accessories-decoration.php`, `digital-printing.php`) exist individually instead of being handled by a single dynamic route.

## 3. Required Implementation Flow

### Phase 1: Database Modification

We must update `schema.sql` (or run a patch script) to insert real parent/child data to act as the foundation.

- **Example Data Needed:**
  - `Mobile Cases` (parent_id: NULL) -> `Apple` (parent_id: Mobile Cases ID) -> `iPhone 14` (parent_id: Apple ID).
  - `Stationary` (parent_id: NULL) -> `ID Cards` (parent_id: Stationary ID) -> `PVC ID Cards` (parent_id: ID Cards ID).

### Phase 2: Updating Backend Functions (`includes/functions.php`)

1. **Create `getCategoryTree()`**: A recursive function to fetch all categories and nest them as arrays inside their respective `parent_id`.
2. **Create `getCategoryDescendants($category_id)`**: A function that returns a flat array of all child/grandchild IDs for a given category.
3. **Update `getAllProducts()`**:
   - Instead of `WHERE p.category_id = ?`, it needs to find all descendant IDs and use `WHERE p.category_id IN (?, ?, ?)`. This ensures clicking "Stationary" shows "PVC ID Card" products.

### Phase 3: Dynamic Header (`inc/header.php`)

1. Run `getCategoryTree()`.
2. Create a dynamic, recursive HTML loop to generate the modern dropdowns.
3. Remove all hardcoded `<a href="wall-display.php">` links. Replace them with `<a href="products/index.php?category=[slug_or_id]">`.

### Phase 4: Frontend View Fixes

1. **Remove redundant files:** Delete `wall-display.php`, `photo-frames.php`, etc., from the root to enforce single-page routing for categories.
2. **Update `products/index.php` (Subcategory Landing View)**:
   - If a specific `category_id` is selected, check if it has children.
   - If it has children (e.g., user is on "Stationary"), show a grid of Subcategories (e.g., ID Cards, Letterheads) at the top of the page before the product grid.
   - If it rests at the deepest level, just show products.

## 4. Execution Steps (Checklist)

- [ ] Write SQL insert queries for the nested demo data requested by the user.
- [ ] Add the `getCategoryTree` and `getCategoryDescendants` PHP logic.
- [ ] Modify `getAllProducts()` to utilize the `IN()` clause.
- [ ] Rewrite the dropdown HTML in `inc/header.php`.
- [ ] Integrate the subcategory view loop in `products/index.php`.
- [ ] Clean up obsolete `.php` category files in the root.
