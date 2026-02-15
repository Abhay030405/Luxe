# Auth Module Documentation

## Overview
The Auth Module handles all user authentication operations including registration, login, and logout functionality. It provides secure access control and session management for the e-commerce platform using Laravel's built-in authentication system.

## Purpose
- User registration with validation
- Secure user login authentication
- Session-based authentication management
- Logout functionality with session cleanup
- Password hashing and security
- Guest/authenticated route protection

## Module Structure

```
Auth/
├── Controllers/
│   ├── AuthController.php         # Main authentication controller
│   └── AutoController.php         # (Utility - possible typo in name)
├── DTOs/
│   └── UserDTO.php                # User data transfer object
├── Events/
│   ├── UserLoggedIn.php           # Triggered on successful login
│   ├── UserLoggedOut.php          # Triggered on logout
│   └── UserRegistered.php         # Triggered on new registration
├── Listeners/
│   ├── SendWelcomeEmail.php       # Sends welcome email to new users
│   └── UpdateLoginTimestamp.php   # Updates last login time
├── Models/
│   └── User.php                   # User model (extends App\Models\User)
├── Policies/
│   └── UserPolicy.php             # Authorization policies
├── Repositories/
│   ├── Contracts/
│   │   └── UserRepositoryInterface.php
│   └── UserRepository.php         # Data access layer
├── Requests/
│   ├── LoginRequest.php           # Login validation
│   └── RegisterRequest.php        # Registration validation
├── Services/
│   └── AuthService.php            # Business logic for auth operations
└── Routes.php                     # Authentication route definitions
```

## Routes

### Guest Routes (accessible only when NOT logged in)

#### Registration
- **GET** `/register` → `register` - Show registration form
- **POST** `/register` → `register.store` - Process registration

#### Login
- **GET** `/login` → `login` - Show login form
- **POST** `/login` → `login.store` - Process login

### Authenticated Routes (accessible only when logged in)

#### Logout
- **POST** `/logout` → `logout` - Process logout

## Database Tables

### Primary Table: users
**Migration:** `0001_01_01_000000_create_users_table.php`

**Columns:**
- `id` (bigint, primary key) - Unique user identifier
- `name` (string) - User's full name
- `email` (string, unique) - User's email address (used for login)
- `email_verified_at` (timestamp, nullable) - Email verification timestamp
- `password` (string) - Hashed password
- `is_admin` (boolean, default: false) - Admin flag
- `remember_token` (string, nullable) - "Remember me" token
- `created_at` (timestamp) - Account creation time
- `updated_at` (timestamp) - Last update time

### Related Tables:
- **user_profiles** - Extended user information (phone, bio, DOB, gender)
- **addresses** - User shipping/billing addresses
- **orders** - User's order history
- **cart_items** - User's shopping cart

### Indexes:
- Unique index on `email`
- Index on `is_admin` for admin queries

## Features & Functionality

### 1. User Registration

**Validation Rules:**
- **Name:** Required, string, max 255 characters
- **Email:** Required, valid email format, unique in users table
- **Password:** Required, minimum 8 characters, must be confirmed

**Process Flow:**
1. User fills registration form
2. Data validated via RegisterRequest
3. Password automatically hashed using bcrypt
4. User record created in database
5. User automatically logged in
6. `UserRegistered` event fired
7. Welcome email sent (optional)
8. Redirected to dashboard

**Security Features:**
- Password complexity enforcement
- Password confirmation required
- Email uniqueness validation
- Protection against mass assignment
- CSRF token validation

### 2. User Login

**Validation Rules:**
- **Email:** Required, must be valid email format
- **Password:** Required
- **Remember:** Optional boolean

**Process Flow:**
1. User enters credentials
2. Data validated via LoginRequest
3. Credentials checked against database
4. Password verified using Hash::check()
5. Session created on success
6. "Remember me" token generated if requested
7. `UserLoggedIn` event fired
8. Last login timestamp updated
9. Redirected to intended page or dashboard

**Security Features:**
- Rate limiting to prevent brute force attacks (60 attempts per minute)
- Failed login attempt tracking
- Secure session management
- CSRF protection
- Remember me token security

### 3. User Logout

**Process Flow:**
1. User clicks logout
2. Session invalidated
3. Remember token removed
4. `UserLoggedOut` event fired
5. User redirected to home page

**Security Features:**
- Session regeneration to prevent fixation
- Complete credential cleanup
- CSRF token validation

### 4. Middleware Protection

**Guest Middleware:**
- Redirects authenticated users away from login/register pages
- Applied to: `/login`, `/register`
- Redirect destination: `/dashboard`

**Auth Middleware:**
- Redirects unauthenticated users to login page
- Applied to: most application routes
- Stores intended URL for redirect after login

## Output

### Registration Form (`/register`)
**Displays:**
- Name input field
- Email input field
- Password input field (with show/hide toggle)
- Password confirmation field
- Terms & conditions checkbox
- "Register" submit button
- Link to login page
- Validation error messages

**Success:**
- User redirected to `/dashboard`
- Welcome message displayed
- User session established

### Login Form (`/login`)
**Displays:**
- Email input field
- Password input field (with show/hide toggle)
- "Remember me" checkbox
- "Login" submit button
- "Forgot password?" link
- Link to registration page
- Validation error messages

**Success:**
- User redirected to intended page or `/dashboard`
- Success message displayed
- User session established

**Failure:**
- Error message: "These credentials do not match our records."
- Form fields retain entered email
- Password field cleared for security

### Logout
**Result:**
- User session terminated
- Redirect to home page (`/`)
- Success message: "You have been logged out successfully."

## How to Use

### For End Users

#### Creating an Account

1. **Navigate to Registration:**
   ```
   Click "Sign up" button in navigation
   Or visit: /register
   ```

2. **Fill Registration Form:**
   - Enter full name
   - Enter email address
   - Create password (minimum 8 characters)
   - Confirm password
   - Agree to terms & conditions
   - Click "Register"

3. **Automatic Login:**
   - Upon successful registration, you're automatically logged in
   - Redirected to dashboard page
   - Welcome message displayed

#### Logging In

1. **Navigate to Login:**
   ```
   Click "Login" button in navigation
   Or visit: /login
   ```

2. **Enter Credentials:**
   - Enter registered email
   - Enter password
   - Optional: Check "Remember me" for persistent login
   - Click "Login"

3. **Access Account:**
   - Redirected to dashboard or previously intended page
   - Full access to authenticated features

#### Logging Out

1. **Click Logout:**
   ```
   Click user dropdown in navigation
   Click "Logout" button
   ```

2. **Session Ends:**
   - Redirected to home page
   - Logout confirmation message displayed

### For Developers

#### Protecting Routes

**Using Middleware in Routes:**
```php
// Protect single route
Route::get('/profile', [ProfileController::class, 'show'])
    ->middleware('auth');

// Protect route group
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/profile', [ProfileController::class, 'show']);
});

// Guest only routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm']);
});
```

#### Checking Authentication in Controllers

```php
// Check if user is authenticated
if (Auth::check()) {
    // User is logged in
}

// Get authenticated user
$user = Auth::user();

// Get user ID
$userId = Auth::id();

// Check if user is guest
if (Auth::guest()) {
    // User is not logged in
}
```

#### Checking Authentication in Blade Views

```blade
@auth
    <!-- Content for authenticated users -->
    <p>Welcome, {{ Auth::user()->name }}!</p>
@endauth

@guest
    <!-- Content for guests -->
    <a href="{{ route('login') }}">Login</a>
@endguest

{{-- Specific user checks --}}
@if(Auth::check())
    <p>You are logged in!</p>
@endif
```

#### Manual Authentication

```php
use Illuminate\Support\Facades\Auth;

// Attempt to authenticate
if (Auth::attempt(['email' => $email, 'password' => $password], $remember)) {
    // Success
    return redirect()->intended('/dashboard');
}

// Login specific user
Auth::login($user, $remember = false);

// Logout current user
Auth::logout();
```

#### Password Hashing

```php
use Illuminate\Support\Facades\Hash;

// Hash password
$hashedPassword = Hash::make('password123');

// Verify password
if (Hash::check('password123', $hashedPassword)) {
    // Password is correct
}
```

## Events & Listeners

### UserRegistered Event
**Triggered:** When new user successfully registers

**Listeners:**
- `SendWelcomeEmail` - Sends welcome email to new user
- Creates audit log entry
- Notifies admin of new registration (optional)

**Usage:**
```php
event(new UserRegistered($user));
```

### UserLoggedIn Event
**Triggered:** When user successfully logs in

**Listeners:**
- `UpdateLoginTimestamp` - Updates user's last_login_at field
- Creates login audit log
- Tracks login IP address

**Usage:**
```php
event(new UserLoggedIn($user));
```

### UserLoggedOut Event
**Triggered:** When user logs out

**Listeners:**
- Creates logout audit log
- Clears user sessions
- Updates activity tracking

**Usage:**
```php
event(new UserLoggedOut($user));
```

## Security Best Practices

### Implemented Security Measures

1. **Password Security:**
   - Bcrypt hashing algorithm
   - Minimum 8 character requirement
   - Password confirmation on registration
   - Never stored in plain text

2. **Session Security:**
   - HTTP-only session cookies
   - Session regeneration on login/logout
   - CSRF token protection
   - Secure session configuration

3. **Rate Limiting:**
   - 60 login attempts per minute per IP
   - Prevents brute force attacks
   - Automatic lockout on excessive attempts

4. **Email Security:**
   - Email uniqueness validation
   - Email format validation
   - Case-insensitive email matching

5. **Remember Me Token:**
   - Encrypted token storage
   - Automatic token rotation
   - Secure token generation

### Configuration

**Session Settings** (`config/session.php`):
```php
'lifetime' => 120,              // Session lifetime in minutes
'secure' => env('SESSION_SECURE_COOKIE', false),  // HTTPS only
'http_only' => true,            // Prevent JavaScript access
'same_site' => 'lax',          // CSRF protection
```

**Authentication Settings** (`config/auth.php`):
```php
'guards' => [
    'web' => [
        'driver' => 'session',
        'provider' => 'users',
    ],
],

'providers' => [
    'users' => [
        'driver' => 'eloquent',
        'model' => App\Models\User::class,
    ],
],
```

## Validation Rules

### Registration Request
```php
'name' => 'required|string|max:255',
'email' => 'required|string|email|max:255|unique:users',
'password' => 'required|string|min:8|confirmed',
```

### Login Request
```php
'email' => 'required|email',
'password' => 'required',
'remember' => 'boolean',
```

## Error Handling

### Common Error Messages

**Registration Errors:**
- "The email has already been taken." - Email already exists
- "The password must be at least 8 characters." - Password too short
- "The password confirmation does not match." - Passwords don't match

**Login Errors:**
- "These credentials do not match our records." - Invalid email/password
- "Too many login attempts. Please try again later." - Rate limit exceeded
- "The email field is required." - Missing email
- "The password field is required." - Missing password

## Testing

### Feature Tests
Located in `tests/Feature/Auth/`:

```bash
# Run all auth tests
php artisan test tests/Feature/Auth

# Run specific test
php artisan test --filter=LoginTest
```

### Test Coverage:
- User registration with valid data
- Registration with duplicate email fails
- User login with valid credentials
- Login with invalid credentials fails
- User logout functionality
- Remember me functionality
- Rate limiting enforcement
- CSRF protection validation

## Integration with Other Modules

### Dependencies:
- **User Module** - Profile management after authentication
- **Cart Module** - Cart persistence for authenticated users
- **Order Module** - Order history and checkout
- **Admin Module** - Admin access control

### Used By:
- All authenticated routes across the application
- Middleware protection system
- Authorization policies
- User-specific features

## Common Issues & Solutions

### Issue: "CSRF Token Mismatch"
**Solution:** Ensure form includes `@csrf` directive and session is active

### Issue: "Rate Limit Exceeded"
**Solution:** Wait 1 minute before attempting login again

### Issue: "Redirect Loop"
**Solution:** Check middleware configuration and intended URL handling

### Issue: "Session Not Persisting"
**Solution:** Verify session configuration and ensure cookies are enabled

## Future Enhancements
- Two-factor authentication (2FA)
- Social login (Google, Facebook, GitHub)
- Email verification requirement
- Password reset functionality
- Account lockout after failed attempts
- Magic link authentication
- Biometric authentication support
- OAuth2 API authentication

---

**Module Version:** 1.0  
**Last Updated:** February 2026  
**Maintained By:** Development Team
