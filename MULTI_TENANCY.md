# Multi-Tenancy Implementation

## Overview
FreighterX now supports multi-tenancy, allowing multiple businesses to use the same application with complete data isolation.

## Features

### 1. **Tenant Model**
Each business is represented by a `Tenant` record with:
- Name
- Unique slug
- Email & phone
- Address
- Logo (optional)
- Active status

### 2. **Automatic Data Scoping**
Using the `BelongsToTenant` trait, all tenant-related data is automatically:
- **Filtered**: Only show data for the current user's tenant
- **Created**: Automatically assign `tenant_id` when creating records

### 3. **Models with Tenant Scoping**
The following models use tenant scoping:
- `Customer` - customer records belong to a tenant
- `Order` - orders belong to a tenant

### 4. **User Association**
- Each `User` belongs to a `Tenant`
- Users can only see/manage data within their tenant
- The `tenant_id` is set during user registration

## Registration Flow

When a new user registers:
1. They provide their **business name** and **personal details**
2. A new `Tenant` is automatically created for their business
3. The user is assigned to that tenant
4. They can immediately start creating orders and customers

## Database Schema

### Tenants Table
```php
- id
- name (Business name)
- slug (Unique identifier)
- email
- phone
- address
- logo
- is_active
- timestamps
```

### Foreign Keys
All tenant-scoped tables have:
```php
- tenant_id (foreign key to tenants table)
```

## Code Examples

### Creating a Record (Automatic Tenant Assignment)
```php
// The tenant_id is automatically set
$customer = Customer::create([
    'name' => 'John Doe',
    'email' => 'john@example.com',
]);
// customer->tenant_id = auth()->user()->tenant_id (automatic)
```

### Querying Records (Automatic Filtering)
```php
// Only returns customers for current user's tenant
$customers = Customer::all();
```

### Bypassing Tenant Scope (Admin/Super Admin)
```php
// If you need to query across all tenants
Customer::withoutGlobalScope('tenant')->get();
```

## Files Modified

### Models
- `app/Models/Tenant.php` - New tenant model
- `app/Models/User.php` - Added `tenant_id` and relationship
- `app/Models/Customer.php` - Added `BelongsToTenant` trait
- `app/Models/Order.php` - Added `BelongsToTenant` trait

### Migrations
- `create_tenants_table.php` - Tenant table
- `add_tenant_id_to_users_table.php` - User tenant relationship
- `add_tenant_id_to_customers_table.php` - Customer tenant relationship
- `add_tenant_id_to_orders_table.php` - Order tenant relationship

### Traits
- `app/Traits/BelongsToTenant.php` - Automatic scoping logic

### Actions
- `app/Actions/Fortify/CreateNewUser.php` - Creates tenant on registration

### Controllers
- `app/Http/Controllers/OrderController.php` - Simplified (no manual filtering)
- `app/Http/Controllers/DashboardController.php` - Simplified (no manual filtering)

### Views
- `resources/views/livewire/auth/register.blade.php` - Added business name field

### Seeders
- `database/seeders/TenantSeeder.php` - Seeds sample tenants
- `database/seeders/DatabaseSeeder.php` - Updated to use tenants
- `database/seeders/OrderSeeder.php` - Updated to assign tenant_id

## Security Considerations

1. **Data Isolation**: Users can ONLY access data from their own tenant
2. **Automatic Assignment**: All records are automatically tagged with the correct tenant_id
3. **Global Scopes**: Query filters are applied at the database level
4. **No Manual Filtering**: Controllers don't need to manually filter by tenant

## Testing

### Test Accounts
After seeding, you have:
- **Tenant 1**: Demo Logistics Ltd
  - Email: test@example.com
  - Password: password

### Creating Test Data
```bash
php artisan db:seed --class=OrderSeeder
```

## Future Enhancements

- [ ] Tenant settings page
- [ ] Logo upload for tenants
- [ ] Invite users to join existing tenant
- [ ] Super admin panel to manage all tenants
- [ ] Tenant-specific customization (colors, branding)
- [ ] Billing/subscription management per tenant
