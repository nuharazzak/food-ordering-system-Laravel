# FoodHub — Laravel Food Ordering System

A portfolio food ordering web application built with Laravel 11, Tailwind CSS, and MySQL.
Demonstrates a realistic end-to-end ordering flow with PayHere Sandbox payment integration.

---

## PayHere Sandbox Payment Integration

> ⚠️ **This is a portfolio/demo project. No real payments are processed.**
> PayHere Sandbox is used exclusively. Your card details are never stored.

### What Is PayHere?

[PayHere](https://www.payhere.lk/) is a Sri Lankan payment gateway. This project integrates
the **PayHere Sandbox** environment to demonstrate realistic online payment flows without
processing real money.

---

### Required `.env` Variables

Add these to your `.env` file (get credentials from [sandbox.payhere.lk](https://sandbox.payhere.lk)):

```env
PAYHERE_MERCHANT_ID=your_sandbox_merchant_id
PAYHERE_MERCHANT_SECRET=your_sandbox_merchant_secret
PAYHERE_SANDBOX=true
```

> **Never commit real credentials to Git.** `.env` is already in `.gitignore`.

---

### How to Configure Merchant ID and Merchant Secret

1. Sign up at [PayHere Sandbox](https://sandbox.payhere.lk)
2. Go to **Merchant → Settings → Domains & Credentials**
3. Copy your **Merchant ID** and **Merchant Secret**
4. Paste them into your `.env` (see above)
5. Add your site domain (or `localhost`) to the allowed domains list

---

### How to Run the Application

```bash
# Install dependencies
composer install
npm install

# Set up environment
cp .env.example .env
php artisan key:generate

# Run database migrations
php artisan migrate

# (Optional) Seed the database
php artisan db:seed

# Start the dev server
php artisan serve
npm run dev
```

Visit: `http://localhost:8000`

---

### How to Test Cash on Delivery

1. Browse the menu and add items to cart
2. Go to Cart / Checkout
3. Fill in delivery details
4. Select **Cash on Delivery**
5. Click **Confirm & Place Order**
6. ✅ Order is created with `payment_method = cash_on_delivery`, `payment_status = pending`

---

### How to Test PayHere Sandbox

1. Browse the menu and add items to cart
2. Go to Cart / Checkout
3. Fill in delivery details
4. Select **Pay Online**
5. Click **Confirm & Place Order**
6. You are redirected to the PayHere Sandbox checkout page
7. Use [PayHere sandbox test cards](https://support.payhere.lk/api-&-mobile-sdk/payhere-checkout#3-test-in-the-sandbox-environment):
   - **Card:** `4916217501611292`
   - **Expiry:** Any future date
   - **CVV:** Any 3 digits
8. Complete the payment
9. PayHere redirects you back to `/payment/success`

---

### How the Notification/Callback Works

```
Customer → PayHere Sandbox → Payment processed
                ↓
    PayHere POST /payment/notify   ← Server-to-server (no browser)
                ↓
    PaymentController::notify()
        1. Find order by order_number
        2. Verify MD5 checksum/hash
        3. Verify amount & currency
        4. Map PayHere status code → payment_status
        5. Update orders.payment_status in database
```

The `notify_url` must be **publicly accessible** (HTTPS preferred). For local testing, use
a tunnel such as [ngrok](https://ngrok.com/):

```bash
ngrok http 8000
# Then set APP_URL=https://your-ngrok-url.ngrok.io in .env
```

---

### Why Payment Status Is Updated Server-Side

The `/payment/success` return URL is triggered by the **browser** redirecting after payment.
It is trivially easy to fake. Therefore, FoodHub **never** marks an order as paid based on
the return URL alone.

Payment status is updated **only** through the `/payment/notify` endpoint, which is called
directly from PayHere's servers. This endpoint verifies:
- The MD5 checksum (prevents tampered requests)
- The payment amount (prevents amount manipulation)
- The currency (must be LKR)

---

### Switching from Sandbox to Production

To switch to live PayHere payments:

1. Register a live account at [payhere.lk](https://www.payhere.lk/)
2. Update your `.env`:

```env
PAYHERE_MERCHANT_ID=your_live_merchant_id
PAYHERE_MERCHANT_SECRET=your_live_merchant_secret
PAYHERE_SANDBOX=false
```

No code changes needed. The checkout URL switches automatically based on `PAYHERE_SANDBOX`.

---

### Payment Flow Summary

```
Customer
  → Cart → Checkout → Select "Pay Online"
  → POST /checkout (OrderController)
  → Order created (payment_status = pending)
  → GET /payment/checkout/{order} (PaymentController)
  → Auto-submitting form → PayHere Sandbox
  → Customer completes payment
  → PayHere POST /payment/notify (server-to-server)
  → Checksum verified → order updated (payment_status = paid)
  → Customer redirected → GET /payment/success
  → Success page reads DB status (does NOT trust URL)
```

---

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>



<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

In addition, [Laracasts](https://laracasts.com) contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

You can also watch bite-sized lessons with real-world projects on [Laravel Learn](https://laravel.com/learn), where you will be guided through building a Laravel application from scratch while learning PHP fundamentals.

## Agentic Development

Laravel's predictable structure and conventions make it ideal for AI coding agents like Claude Code, Cursor, and GitHub Copilot. Install [Laravel Boost](https://laravel.com/docs/ai) to supercharge your AI workflow:

```bash
composer require laravel/boost --dev

php artisan boost:install
```

Boost provides your agent 15+ tools and skills that help agents build Laravel applications while following best practices.

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
