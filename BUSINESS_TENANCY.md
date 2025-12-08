# Business-Centric Multi-Tenancy Implementation

## Overview
FreighterX has been restructured to be business-focused rather than individual-focused. The system now supports:
- Business account registration (admin user creation)
- Team member invitations via email
- Tenant-specific categories and origin countries
- Role-based access control (admin, manager, user)

## Key Changes

### 1. Database Structure

#### New Tables
- **invitations**: Stores pending user invitations
  - `id`, `tenant_id`, `invited_by`, `email`, `token`, `role`, `accepted_at`, `expires_at`
  
- **origin_countries**: Tenant-specific origin countries
  - `id`, `tenant_id`, `name`, `code`, `is_active`

#### Updated Tables
- **categories**: Now tenant-specific
  - Added `tenant_id` foreign key
  - Composite unique constraint on `(tenant_id, slug)`
  
- **users**: Added role support
  - Added `role` column (admin, manager, user)

### 2. Models

#### Category
- Uses `BelongsToTenant` trait
- Automatically scoped to current tenant
- Relationship to `Tenant`

#### OriginCountry (New)
- Uses `BelongsToTenant` trait
- Stores country name and ISO code
- Active/inactive status

#### Invitation (New)
- Belongs to `Tenant` and `User` (inviter)
- Auto-generates unique token on creation
- 7-day expiration by default
- Helper methods: `isExpired()`, `isAccepted()`

#### User
- Added `role` to fillable
- First user created during registration is always `admin`

#### Tenant
- New relationships: `categories()`, `originCountries()`, `invitations()`

### 3. Controllers

#### InvitationController (New)
- `index()`: List invitations for current tenant
- `store()`: Create new invitation
- `show($token)`: Display invitation acceptance form
- `accept($token)`: Accept invitation and create user account
- `destroy()`: Delete invitation

#### TeamController (New)
- `index()`: List team members and pending invitations
- `updateRole()`: Change user role (admin only)
- `destroy()`: Remove team member (admin only)

### 4. Routes

#### Team Management
- `GET /team` - View team members
- `PATCH /team/{user}/role` - Update user role (admin only)
- `DELETE /team/{user}` - Remove user (admin only)

#### Invitations
- `GET /invitations` - List invitations (auth required)
- `POST /invitations` - Send invitation (auth required)
- `DELETE /invitations/{invitation}` - Delete invitation (auth required)
- `GET /invitations/{token}/accept` - Acceptance form (public)
- `POST /invitations/{token}/accept` - Accept invitation (public)

### 5. Views

#### team/index.blade.php
- Send invitation form (admin only)
- Pending invitations table
- Team members list with role management
- Remove team member functionality

#### invitations/accept.blade.php
- User registration form for invited users
- Pre-filled email (read-only)
- Name and password fields
- Automatically logs in user after acceptance

#### invitations/expired.blade.php
- Displayed when invitation link has expired

### 6. Registration Flow

#### Before
- Individual users could self-register
- First user created their own tenant

#### After
- Only business accounts can self-register (creates admin user)
- Additional users join via email invitation
- First user is always `admin` role
- Invited users get assigned role from invitation

### 7. Seeders

#### CategorySeeder
- Creates default categories for each tenant
- 11 standard categories (Electronics, Fashion, etc.)

#### OriginCountrySeeder (New)
- Creates default countries for each tenant
- 10 common countries (USA, UK, Canada, etc.)

#### DatabaseSeeder
- Creates test admin user with role

## User Roles

### Admin
- Full access to all features
- Can invite users
- Can change user roles
- Can remove team members
- Can manage categories and origin countries

### Manager
- Can manage orders and customers
- Cannot manage team members
- Cannot change system settings

### User
- Can view and create orders
- Can view customers
- Limited access to team features

## Security Features

1. **Invitation Tokens**: Unique, random 32-character tokens
2. **Expiration**: Invitations expire after 7 days
3. **One-time Use**: Invitations can only be accepted once
4. **Tenant Isolation**: All data strictly scoped to tenants
5. **Role Checks**: Controllers verify user permissions
6. **Cannot Delete Self**: Admins cannot delete their own account

## Next Steps

### Email Notifications (TODO)
- Configure mail driver in `.env`
- Create invitation email notification
- Add `Mail::send()` in `InvitationController@store`

### Additional Features (Future)
- Category management UI for admins
- Origin country management UI for admins
- User profile management
- Activity logs for team actions
- Permission system (more granular than roles)

## Testing

### Test Admin Login
- Email: `test@example.com`
- Password: `password`
- Role: `admin`

### Test Invitation Flow
1. Login as admin
2. Navigate to `/team`
3. Send invitation to email address
4. Copy invitation link from database or logs
5. Open link in incognito window
6. Complete registration
7. User is logged in automatically

## Database Verification

```bash
# Check tenants
php artisan tinker --execute="App\Models\Tenant::count()"

# Check categories per tenant
php artisan tinker --execute="App\Models\Category::where('tenant_id', 1)->count()"

# Check origin countries per tenant
php artisan tinker --execute="App\Models\OriginCountry::where('tenant_id', 1)->count()"

# Check pending invitations
php artisan tinker --execute="App\Models\Invitation::whereNull('accepted_at')->count()"
```

## Notes

- All existing multi-tenancy features remain intact
- QR code generation still works per tenant
- Status updates still functional
- Global scopes automatically filter all queries by tenant
- Categories and countries are isolated per tenant
- Each tenant maintains their own team
