# User Module Documentation

## Overview
The User Module manages user profiles, personal information, password management, and address book functionality. It provides authenticated users with tools to maintain their account details, update personal information, change passwords, and manage multiple shipping/billing addresses.

## Purpose
- Manage user profile information (name, email, phone, bio, DOB, gender)
- Update user passwords with validation
- Manage multiple addresses (shipping and billing)
- Set default addresses for quick checkout
- Provide address CRUD operations
- Validate address data
- Link addresses to orders

## Module Structure

```
User/
├── Controllers/
│   ├── AddressController.php          # Address CRUD operations
│   ├── ProfileController.php          # Profile viewing and editing
│   └── UserController.php             # Additional user operations
├── DTOs/
│   ├── AddressDTO.php                 # Address data transfer object
│   └── UserProfileDTO.php             # User profile data transfer object
├── Events/
│   ├── PasswordChanged.php            # Triggered on password update
│   ├── ProfileUpdated.php             # Triggered on profile update
│   └── AddressUpdated.php             # Triggered on address change
├── Listeners/
│   └── SendPasswordChangeNotification.php  # Email on password change
├── Models/
│   └── (Uses main User model from app/Models/)
├── Policies/
│   └── AddressPolicy.php              # Authorization for address operations
├── Repositories/
│   ├── Contracts/
│   │   ├── AddressRepositoryInterface.php
│   │   └── UserRepositoryInterface.php
│   ├── AddressRepository.php          # Address data access layer
│   └── UserRepository.php             # User data access layer
├── Requests/
│   ├── StoreAddressRequest.php        # Validation for creating address
│   ├── UpdateAddressRequest.php       # Validation for updating address
│   ├── UpdatePasswordRequest.php      # Validation for password change
│   └── UpdateProfileRequest.php       # Validation for profile update
├── Services/
│   ├── AddressService.php             # Address business logic
│   └── UserService.php                # User profile business logic
└── Routes.php                         # User module route definitions
```

## Routes

All user module routes require authentication (`auth` middleware).

### Profile Routes

Prefix: `/profile`, Name prefix: `profile.`

- **GET** `/profile` → `profile.show` - View user profile
- **GET** `/profile/edit` → `profile.edit` - Edit profile form
- **PUT** `/profile/update` → `profile.update` - Update profile information
- **GET** `/profile/password` → `profile.password.edit` - Change password form
- **PUT** `/profile/password` → `profile.password.update` - Update password

### Address Routes

Prefix: `/addresses`, Name prefix: `addresses.`

- **GET** `/addresses` → `addresses.index` - List all user addresses
- **GET** `/addresses/create` → `addresses.create` - Create address form
- **POST** `/addresses` → `addresses.store` - Save new address
- **GET** `/addresses/{id}/edit` → `addresses.edit` - Edit address form
- **PUT** `/addresses/{id}` → `addresses.update` - Update address
- **DELETE** `/addresses/{id}` → `addresses.destroy` - Delete address
- **POST** `/addresses/{id}/set-default` → `addresses.set-default` - Set as default address

## Database Tables

### Primary Table: user_profiles
**Migration:** `2026_02_10_025622_create_user_profiles_table.php`

**Columns:**
- `id` (bigint, primary key) - Profile identifier
- `user_id` (bigint, foreign key, unique) - Owner user (references users.id)
- `phone` (string, nullable) - Contact phone number
- `bio` (text, nullable) - User biography
- `date_of_birth` (date, nullable) - Date of birth
- `gender` (enum, nullable) - Gender: `male`, `female`, `other`, `prefer_not_to_say`
- `avatar` (string, nullable) - Profile picture path
- `created_at` (timestamp)
- `updated_at` (timestamp)

**Relationships:**
- One-to-one with users table
- Automatically created when user registers

**Indexes:**
- Unique index on `user_id`

### Secondary Table: addresses
**Migration:** `2026_02_10_025632_create_addresses_table.php`

**Columns:**
- `id` (bigint, primary key) - Address identifier
- `user_id` (bigint, foreign key) - Address owner (references users.id)
- `type` (enum) - Address type: `shipping`, `billing`, `both`
- `full_name` (string) - Recipient name
- `phone` (string) - Contact phone
- `address_line_1` (string) - Street address line 1
- `address_line_2` (string, nullable) - Street address line 2
- `city` (string) - City name
- `state` (string) - State/Province
- `postal_code` (string) - ZIP/Postal code
- `country` (string, default: 'India') - Country name
- `is_default` (boolean, default: false) - Default address flag
- `created_at` (timestamp)
- `updated_at` (timestamp)

**Indexes:**
- Index on `user_id` for user address lookups
- Index on `is_default` for finding default addresses

**Constraints:**
- Foreign key cascade on user deletion (deletes addresses)
- Only one default address per user per type

### Related Tables:
- **users** - Core user information (email, password, name)
- **orders** - Orders use addresses for shipping/billing

## Features & Functionality

### 1. View Profile

**Route:** `GET /profile`

**Process:**
1. Fetch authenticated user
2. Eager load user_profile relationship
3. Display profile information

**Output:**
- User avatar (if set)
- Full name
- Email address
- Phone number
- Date of birth
- Gender
- Bio/About section
- "Edit Profile" button
- "Change Password" link
- Account creation date
- Recent activity summary

### 2. Edit Profile

**Routes:**
- Display form: `GET /profile/edit`
- Submit update: `PUT /profile/update`

**Validation Rules:**
```php
'name' => 'required|string|max:255',
'email' => 'required|email|unique:users,email,' . auth()->id(),
'phone' => 'nullable|string|max:15',
'bio' => 'nullable|string|max:500',
'date_of_birth' => 'nullable|date|before:today',
'gender' => 'nullable|in:male,female,other,prefer_not_to_say',
'avatar' => 'nullable|image|max:2048' // 2MB max
```

**Process:**
1. Display profile edit form with current data
2. User updates fields
3. Validate submitted data
4. **Update user record:**
   ```php
   $user->update(['name' => $name, 'email' => $email]);
   ```
5. **Update user_profile record:**
   ```php
   $user->profile->update([
       'phone' => $phone,
       'bio' => $bio,
       'date_of_birth' => $dob,
       'gender' => $gender,
   ]);
   ```
6. Handle avatar upload (if provided)
7. Fire `ProfileUpdated` event
8. Redirect back with success message

**File Upload (Avatar):**
```php
if ($request->hasFile('avatar')) {
    // Delete old avatar
    Storage::delete($user->profile->avatar);
    
    // Store new avatar
    $path = $request->file('avatar')->store('avatars', 'public');
    $user->profile->update(['avatar' => $path]);
}
```

**Response:**
- Success: Redirect to `/profile` with message "Profile updated successfully"
- Validation Error: Redirect back with errors and old input

### 3. Change Password

**Routes:**
- Display form: `GET /profile/password`
- Submit update: `PUT /profile/password`

**Validation Rules:**
```php
'current_password' => 'required|string',
'password' => 'required|string|min:8|confirmed',
'password_confirmation' => 'required'
```

**Process:**
1. Display password change form
2. User enters current and new password
3. **Validate current password:**
   ```php
   if (!Hash::check($currentPassword, auth()->user()->password)) {
       return back()->withErrors(['current_password' => 'Current password is incorrect']);
   }
   ```
4. **Update password:**
   ```php
   auth()->user()->update([
       'password' => Hash::make($newPassword)
   ]);
   ```
5. Fire `PasswordChanged` event
6. Send notification email
7. Redirect with success message

**Security Features:**
- Requires current password verification
- Password must be at least 8 characters
- Password confirmation required
- Password automatically hashed
- Email notification sent on change
- All other sessions logged out (optional)

**Response:**
- Success: "Password changed successfully"
- Error: "Current password is incorrect"

### 4. List Addresses

**Route:** `GET /addresses`

**Process:**
1. Fetch all addresses for authenticated user
2. Separate by type (shipping, billing, both)
3. Identify default address
4. Display address list

**Output:**
- Table/grid of addresses showing:
  - Address type badge (Shipping/Billing)
  - Recipient name
  - Full address
  - Phone number
  - "Default" badge if is_default = true
  - Action buttons (Edit, Delete, Set as Default)
- "Add New Address" button
- Empty state if no addresses
- Separate sections for shipping and billing addresses (optional)

**Example Display:**
```
Shipping Addresses                    [ Add New Address ]

┌─────────────────────────────────────┐
│ John Doe (Default)         [Edit] [✕]│
│ 123 Main Street                     │
│ Mumbai, MH 400001                   │
│ India                               │
│ Phone: +91 98765 43210              │
└─────────────────────────────────────┘

┌─────────────────────────────────────┐
│ John Doe              [Set Default] │
│ 456 Park Avenue        [Edit] [✕]   │
│ Delhi, DL 110001                    │
│ India                               │
│ Phone: +91 98765 43210              │
└─────────────────────────────────────┘
```

### 5. Create Address

**Routes:**
- Display form: `GET /addresses/create`
- Submit: `POST /addresses`

**Validation Rules:**
```php
'type' => 'required|in:shipping,billing,both',
'full_name' => 'required|string|max:255',
'phone' => 'required|string|max:15',
'address_line_1' => 'required|string|max:255',
'address_line_2' => 'nullable|string|max:255',
'city' => 'required|string|max:100',
'state' => 'required|string|max:100',
'postal_code' => 'required|string|max:10',
'country' => 'required|string|max:100',
'is_default' => 'boolean'
```

**Process:**
1. Display address creation form
2. User fills in address details
3. Validate submitted data
4. **If is_default = true:**
   - Unset other default addresses of same type
   ```php
   Address::where('user_id', auth()->id())
       ->where('type', $type)
       ->where('is_default', true)
       ->update(['is_default' => false]);
   ```
5. **Create address:**
   ```php
   Address::create([
       'user_id' => auth()->id(),
       'type' => $type,
       'full_name' => $fullName,
       // ... other fields
   ]);
   ```
6. Fire `AddressCreated` event
7. Redirect to addresses list with success message

**Response:**
- Success: Redirect to `/addresses` with message "Address added successfully"
- Validation Error: Redirect back with errors and old input

### 6. Edit Address

**Routes:**
- Display form: `GET /addresses/{id}/edit`
- Submit update: `PUT /addresses/{id}`

**Process:**
1. Find address by ID
2. Authorize user owns this address
3. Display edit form with current data
4. User updates fields
5. Validate submitted data
6. Update address record
7. Handle default address logic (if is_default changed)
8. Fire `AddressUpdated` event
9. Redirect back with success message

**Authorization:**
- User can only edit their own addresses
- AddressPolicy checks ownership

**Response:**
- Success: Redirect to `/addresses` with message "Address updated successfully"
- Error: 403 Forbidden if not owner

### 7. Delete Address

**Route:** `DELETE /addresses/{id}`

**Process:**
1. Find address by ID
2. Authorize user owns this address
3. **Check if address in use:**
   - Check if any pending/processing orders use this address
   - If yes: Prevent deletion with error
4. **Delete address:**
   ```php
   $address->delete();
   ```
5. Fire `AddressDeleted` event
6. Redirect back with success message

**Business Rules:**
- Cannot delete address used in active orders
- Can delete if only used in delivered/cancelled orders
- If deleting default address, optionally set another as default

**Response:**
- Success: "Address deleted successfully"
- Error: "Cannot delete address used in active orders"

### 8. Set Default Address

**Route:** `POST /addresses/{id}/set-default`

**Process:**
1. Find address by ID
2. Authorize user owns this address
3. Get address type
4. **Unset other default addresses:**
   ```php
   Address::where('user_id', auth()->id())
       ->where('type', $address->type)
       ->where('id', '!=', $address->id)
       ->update(['is_default' => false]);
   ```
5. **Set this address as default:**
   ```php
   $address->update(['is_default' => true]);
   ```
6. Redirect back with success message

**Response:**
- Success: "Default address updated"
- AJAX: JSON response for dynamic updates

## Output

### Profile Page (`/profile`)

```
┌─────────────────────────────────────────┐
│        [Avatar Image]                   │
│                                         │
│    John Doe                    [Edit]   │
│    john.doe@example.com                 │
│    +91 98765 43210                      │
│                                         │
│    📅 Date of Birth: January 1, 1990    │
│    👤 Gender: Male                      │
│                                         │
│    About Me:                            │
│    Software developer passionate about  │
│    building great products.             │
│                                         │
│    Member since: February 2026          │
│                                         │
│    [Change Password]                    │
└─────────────────────────────────────────┘
```

### Edit Profile Form (`/profile/edit`)

```
Edit Profile

Name:           [John Doe                    ]
Email:          [john.doe@example.com        ]
Phone:          [+91 98765 43210             ]
Date of Birth:  [01/01/1990                  ]
Gender:         [Male ▾]

Avatar:         [Choose File] No file chosen
                Current: [thumbnail]

Bio:
┌────────────────────────────────────────┐
│ Software developer passionate about... │
│                                        │
└────────────────────────────────────────┘

[ Save Changes ]  [ Cancel ]
```

### Change Password Form (`/profile/password`)

```
Change Password

Current Password:    [****************]

New Password:        [****************]
                     (Minimum 8 characters)

Confirm Password:    [****************]

[ Update Password ]  [ Cancel ]
```

## How to Use

### For End Users

#### Viewing Profile

1. **Navigate to Profile:**
   ```
   My Account → Profile
   Or visit: /profile
   ```

2. **View Information:**
   - See all profile details
   - Click "Edit" to make changes
   - Click "Change Password" to update password

#### Editing Profile

1. **Access Edit Form:**
   ```
   Profile → Edit button
   ```

2. **Update Information:**
   - Modify any fields
   - Upload new avatar (optional)
   - Click "Save Changes"

3. **Validation:**
   - See errors if any field invalid
   - Fix and resubmit

#### Changing Password

1. **Access Password Form:**
   ```
   Profile → Change Password
   ```

2. **Enter Passwords:**
   - Current password
   - New password (min 8 chars)
   - Confirm new password

3. **Submit:**
   - Click "Update Password"
   - Receive confirmation email
   - See success message

#### Managing Addresses

1. **View Addresses:**
   ```
   My Account → Addresses
   Or visit: /addresses
   ```

2. **Add New Address:**
   - Click "Add New Address"
   - Fill form with address details
   - Check "Set as default" if needed
   - Click "Save"

3. **Edit Address:**
   - Click "Edit" on any address
   - Update fields
   - Save changes

4. **Set Default:**
   - Click "Set as Default" button
   - Address used automatically in checkout

5. **Delete Address:**
   - Click delete icon
   - Confirm deletion
   - Address removed (if not in use)

### For Developers

#### Accessing User Profile

```php
use App\Models\User;

$user = auth()->user();

// Access profile relationship
$phone = $user->profile->phone;
$bio = $user->profile->bio;
$dob = $user->profile->date_of_birth;
$gender = $user->profile->gender;

// Get avatar URL
$avatarUrl = $user->profile->avatar 
    ? Storage::url($user->profile->avatar)
    : asset('images/default-avatar.png');
```

#### Using UserService

```php
use App\Modules\User\Services\UserService;

$userService = app(UserService::class);

// Update profile
$userService->updateProfile(auth()->id(), [
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'phone' => '+91 98765 43210',
    'bio' => 'About me...',
]);

// Change password
$userService->changePassword(
    userId: auth()->id(),
    currentPassword: $currentPassword,
    newPassword: $newPassword
);

// Get user profile DTO
$profileDTO = $userService->getUserProfile(auth()->id());
```

#### Managing Addresses

```php
use App\Modules\User\Services\AddressService;

$addressService = app(AddressService::class);

// Get user addresses
$addresses = $addressService->getUserAddresses(auth()->id());

// Get default shipping address
$defaultShipping = $addressService->getDefaultAddress(
    auth()->id(), 
    'shipping'
);

// Create address
$address = $addressService->createAddress([
    'user_id' => auth()->id(),
    'type' => 'shipping',
    'full_name' => 'John Doe',
    'phone' => '+91 98765 43210',
    'address_line_1' => '123 Main St',
    'city' => 'Mumbai',
    'state' => 'Maharashtra',
    'postal_code' => '400001',
    'country' => 'India',
    'is_default' => true,
]);

// Set default address
$addressService->setAsDefault($addressId);

// Delete address
$addressService->deleteAddress($addressId);
```

#### Using AddressDTO

```php
$addressDTO = $addressService->getAddress($addressId);

echo $addressDTO->fullName;
echo $addressDTO->phone;
echo $addressDTO->fullAddress;     // Formatted full address
echo $addressDTO->city;
echo $addressDTO->state;
echo $addressDTO->postalCode;
echo $addressDTO->isDefault;
```

## Events & Listeners

### ProfileUpdated Event
**Triggered:** When user updates profile information

**Data:**
- user (User model)
- changedFields (array of field names)

**Listeners:**
- Update search index
- Sync with CRM
- Log profile changes

### PasswordChanged Event
**Triggered:** When user changes password

**Data:**
- user (User model)
- changedAt (timestamp)

**Listeners:**
- `SendPasswordChangeNotification` - Sends security email
- Log security event
- Invalidate other sessions (optional)

### AddressCreated / AddressUpdated / AddressDeleted Events
**Triggered:** On address CRUD operations

**Data:**
- address (Address model)
- user (User model)

**Listeners:**
- Validate shipping coverage
- Update address suggestions
- Sync with external systems

## Security & Validation

### Authorization
- Users can only access their own profile
- Users can only manage their own addresses
- AddressPolicy enforces ownership

### Password Security
- Current password verification required
- Minimum 8 characters
- Password confirmation required
- Passwords hashed with bcrypt
- Security notification email sent

### Data Validation
- Email uniqueness checked
- Phone format validated
- Date of birth must be in past
- Address fields required
- Postal code format validated

### File Upload Security
- Avatar limited to images only
- Maximum 2MB file size
- Stored in secure location
- Old avatar deleted on update

## Performance Optimization

### Database Queries
- Eager load user.profile relationship
- Index on user_id and is_default
- Cache user profile data (optional)

### Avatar Optimization
- Resize images on upload
- Generate thumbnails
- Serve via CDN (optional)
- Use lazy loading

## Testing

### Feature Tests
```bash
# Run user tests
php artisan test tests/Feature/User
```

**Test Coverage:**
- View profile
- Update profile
- Change password
- Create address
- Update address
- Delete address
- Set default address
- Authorization checks

## Integration with Other Modules

### Dependencies:
- **Auth Module** - User authentication
- **Storage** - Avatar file storage

### Used By:
- **Order Module** - Uses addresses for shipping
- **Checkout** - Selects default addresses

## Common Issues & Solutions

### Issue: "Email already taken"
**Cause:** Trying to update email to one already in use
**Solution:** Exclude current user from uniqueness check

### Issue: "Cannot delete address"
**Cause:** Address used in active orders
**Solution:** Only allow deletion if no pending orders use it

### Issue: "Default address not updating"
**Cause:** Not unsetting other defaults
**Solution:** Unset old defaults before setting new one

### Issue: "Avatar not displaying"
**Cause:** Incorrect storage path or public link
**Solution:** Run `php artisan storage:link` and use Storage::url()

## Future Enhancements
- Two-factor authentication
- OAuth social login profiles
- Email verification
- Phone number verification
- Address autocomplete (Google Places API)
- Address validation service
- Multiple profile pictures/gallery
- Profile visibility settings
- Account deletion/deactivation
- Export personal data (GDPR)
- Activity log
- Login history
- Security settings dashboard

---

**Module Version:** 1.0  
**Last Updated:** February 2026  
**Maintained By:** Development Team
