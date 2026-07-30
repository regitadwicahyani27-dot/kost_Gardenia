# 🎭 Playwright E2E Testing - Complete Setup Guide

**Project:** Kos Putri Gardenia  
**Feature:** Manual Payment Verification System  
**Framework:** Playwright + TypeScript

---

## 📋 Quick Start (5 Minutes)

### Step 1: Install Dependencies

```bash
# Install Node.js packages
npm install

# Install Playwright browsers
npm run test:install
```

### Step 2: Prepare Database

```bash
# Refresh database with test data
php refresh-db.php

# Or manually
php artisan migrate:fresh --seed
```

### Step 3: Start Laravel Server

```bash
# Start server on port 8000
php artisan serve --port=8000

# Or use Laragon (already running)
```

### Step 4: Run Tests

```bash
# Run all tests
npm run test:e2e

# Run with browser visible
npm run test:e2e:headed

# Run in debug mode
npm run test:e2e:debug
```

### Step 5: View Report

```bash
npm run test:e2e:report
```

---

## 🎯 What Gets Tested?

### 1. User Flow ✅

```
User Login
  ↓
Browse Available Rooms
  ↓
Create Booking
  ↓
Upload Payment Proof (DP: Rp 250,000)
  ↓
Payment Status: PENDING
```

### 2. Admin Verification (Accept) ✅

```
Admin Login
  ↓
Navigate to /admin/pembayaran
  ↓
View Pending Payments
  ↓
Click "Verifikasi" Button
  ↓
Confirm Verification
  ↓
✅ Payment Status: VERIFIED
✅ Booking Status: CONFIRMED
✅ Room Available: FALSE
```

### 3. Admin Rejection ✅

```
Admin Login
  ↓
Navigate to /admin/pembayaran
  ↓
View Pending Payments
  ↓
Click "Tolak" Button
  ↓
Enter Rejection Reason
  ↓
Confirm Rejection
  ↓
❌ Payment Status: REJECTED
❌ Booking Status: CANCELLED
✅ Room Available: TRUE (stays available)
```

---

## 📁 File Structure

```
gardenia-kosla122/
├── tests/
│   └── e2e/
│       ├── payment-verification.spec.ts     # Main test suite
│       ├── helpers/
│       │   ├── auth.helper.ts               # Login/logout utilities
│       │   └── payment.helper.ts            # Payment flow utilities
│       ├── reports/                         # Test reports (auto-generated)
│       └── README.md                        # E2E documentation
├── playwright.config.ts                     # Playwright configuration
├── package.json                             # NPM scripts
├── RUN_E2E_TESTS.bat                        # Quick test runner (Windows)
└── PLAYWRIGHT_SETUP_GUIDE.md                # This file
```

---

## 🚀 NPM Scripts

| Script | Command | Description |
|--------|---------|-------------|
| `test:e2e` | `playwright test` | Run all tests (headless) |
| `test:e2e:headed` | `playwright test --headed` | Run with browser visible |
| `test:e2e:debug` | `playwright test --debug` | Step-by-step debugging |
| `test:e2e:ui` | `playwright test --ui` | Interactive UI mode |
| `test:e2e:report` | `playwright show-report` | Open HTML report |
| `test:install` | `playwright install` | Install browsers |

---

## 🧪 Test Scenarios

### Scenario 1: Happy Path - Payment Verification

```typescript
test('Admin verifies payment successfully', async ({ page }) => {
  // User creates booking (prerequisite)
  await loginAsUser(page);
  const bookingCode = await createBookingWithPayment(page);
  await logout(page);
  
  // Admin verifies
  await loginAsAdmin(page);
  await page.goto('/admin/pembayaran?status=pending');
  await page.click(`button:has-text("Verifikasi")`);
  
  // Assert
  await expect(page.locator('text=berhasil')).toBeVisible();
  await expect(page.locator(`text=${bookingCode}`)).toHaveText(/Terverifikasi/);
});
```

### Scenario 2: Rejection Flow

```typescript
test('Admin rejects payment with reason', async ({ page }) => {
  await loginAsAdmin(page);
  await page.goto('/admin/pembayaran?status=pending');
  
  // Click reject
  await page.click('button:has-text("Tolak")');
  
  // Fill reason
  await page.fill('textarea[name="reject_notes"]', 'Invalid proof');
  
  // Submit
  await page.click('button:has-text("Tolak Pembayaran")');
  
  // Assert
  await expect(page.locator('text=ditolak')).toBeVisible();
});
```

---

## 🛠️ Helper Functions

### Authentication

```typescript
import { loginAsAdmin, loginAsUser, logout } from './helpers/auth.helper';

// Login as admin
await loginAsAdmin(page);

// Login as user (rere or cihuy)
await loginAsUser(page, 'RERE');

// Logout
await logout(page);
```

### Payment Operations

```typescript
import { 
  createBookingWithPayment, 
  verifyPayment, 
  rejectPayment 
} from './helpers/payment.helper';

// Create booking with payment
const bookingCode = await createBookingWithPayment(page, {
  checkInDate: '2026-08-15',
  paymentMethod: 'qris',
});

// Verify payment
await verifyPayment(page, bookingCode);

// Reject payment
await rejectPayment(page, bookingCode, 'Bukti tidak jelas');
```

---

## 🐛 Debugging Tips

### 1. Use Debug Mode

```bash
npm run test:e2e:debug
```

This opens Playwright Inspector where you can:
- Step through each action
- Inspect elements
- View console logs
- Take screenshots

### 2. Use UI Mode

```bash
npm run test:e2e:ui
```

Interactive mode with:
- Test explorer
- Timeline viewer
- Network inspector
- Console output

### 3. Add console.log

```typescript
test('debug test', async ({ page }) => {
  console.log('Current URL:', page.url());
  
  const text = await page.locator('h1').textContent();
  console.log('Page title:', text);
});
```

### 4. Take Screenshots

```typescript
await page.screenshot({ path: 'debug-screenshot.png' });
```

### 5. Wait for Network

```typescript
await page.waitForLoadState('networkidle');
```

---

## 📊 Viewing Test Reports

### HTML Report (Recommended)

```bash
npm run test:e2e:report
```

Report includes:
- ✅ Test results (pass/fail)
- ⏱️ Execution time
- 📸 Screenshots on failure
- 🎥 Video recordings
- 📝 Trace viewer

### JSON Report

```json
// tests/e2e/reports/results.json
{
  "suites": [...],
  "tests": [...],
  "stats": {
    "passed": 5,
    "failed": 0,
    "skipped": 0
  }
}
```

---

## 🔄 CI/CD Integration

### GitHub Actions

```yaml
- name: Run E2E Tests
  run: |
    npm ci
    npm run test:install:deps
    php artisan serve &
    sleep 5
    npm run test:e2e
```

### GitLab CI

```yaml
e2e-tests:
  script:
    - npm ci
    - npm run test:install
    - php artisan serve &
    - sleep 5
    - npm run test:e2e
```

---

## ⚙️ Configuration

### playwright.config.ts

```typescript
export default defineConfig({
  testDir: './tests/e2e',
  timeout: 120 * 1000,        // 2 minutes per test
  workers: 1,                  // Sequential execution
  retries: 2,                  // Retry failed tests
  
  use: {
    baseURL: 'http://localhost:8000',
    screenshot: 'only-on-failure',
    video: 'retain-on-failure',
    trace: 'on-first-retry',
  },
});
```

---

## 🎓 Best Practices

### ✅ DO

- Use `data-testid` for stable selectors
- Wait for elements properly (`waitForSelector`)
- Use helpers for common tasks
- Clean up after tests (logout, reset state)
- Run tests in CI/CD pipeline

### ❌ DON'T

- Use hardcoded waits (`page.waitForTimeout(5000)`)
- Rely on XPath selectors
- Test implementation details
- Leave test data in production DB
- Commit sensitive credentials

---

## 🆘 Troubleshooting

### Issue: Tests Timeout

**Cause:** Laravel server not responding or slow queries

**Solution:**
```typescript
// Increase timeout
test.setTimeout(180000); // 3 minutes

// Or in config
timeout: 180 * 1000
```

### Issue: Element Not Found

**Cause:** Selector changed or element not visible yet

**Solution:**
```typescript
// Wait for element
await page.waitForSelector('[data-testid="verify-btn"]');

// Or use auto-waiting
await expect(page.locator('[data-testid="verify-btn"]')).toBeVisible();
```

### Issue: Database Conflicts

**Cause:** Multiple tests modifying same data

**Solution:**
```typescript
// Run sequentially
workers: 1 // in playwright.config.ts

// Or use unique test data
const uniqueEmail = `user-${Date.now()}@test.com`;
```

---

## 📞 Support

**Need Help?**
- 📖 Read: `tests/e2e/README.md`
- 🎥 Watch: [Playwright Documentation](https://playwright.dev)
- 💬 Ask: developer@gardenia-kos.com

---

## ✅ Verification Checklist

Before considering tests complete:

- [ ] All tests pass locally
- [ ] Tests pass in CI/CD
- [ ] No flaky tests (>95% success rate)
- [ ] Test coverage meets requirements
- [ ] Documentation is up-to-date
- [ ] Screenshots/videos on failure
- [ ] Performance is acceptable (<5min for full suite)

---

**Last Updated:** 30 Juli 2026  
**Version:** 1.0.0  
**Status:** ✅ Production Ready  
**Author:** Kiro AI - Senior Full-Stack Developer & QA Engineer

---

🎉 **Happy Testing!** 🎭
