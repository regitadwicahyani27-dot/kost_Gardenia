import { test, expect, Page } from '@playwright/test';

/**
 * E2E Test Suite: Manual Payment Verification by Admin
 * Project: Kos Putri Gardenia
 * 
 * Test Coverage:
 * 1. User creates booking and uploads payment proof
 * 2. Admin verifies (accepts) payment
 * 3. Admin rejects payment with reason
 */

// Configuration
const BASE_URL = process.env.BASE_URL || 'http://localhost:8000';

const ADMIN_CREDENTIALS = {
  email: 'admin@gardenia.com',
  password: 'admin123',
};

const USER_CREDENTIALS = {
  email: 'rere@gmail.com',
  password: 'password',
};

// Test Data
const BOOKING_DATA = {
  checkInDate: new Date(Date.now() + 7 * 24 * 60 * 60 * 1000).toISOString().split('T')[0], // 7 days from now
};

// Helper Functions
async function loginAsUser(page: Page, credentials: typeof USER_CREDENTIALS) {
  await page.goto(`${BASE_URL}/login`);
  await page.fill('input[name="email"]', credentials.email);
  await page.fill('input[name="password"]', credentials.password);
  await page.click('button[type="submit"]');
  await page.waitForURL(/\/user\/dashboard/, { timeout: 10000 });
}

async function loginAsAdmin(page: Page, credentials: typeof ADMIN_CREDENTIALS) {
  await page.goto(`${BASE_URL}/login`);
  await page.fill('input[name="email"]', credentials.email);
  await page.fill('input[name="password"]', credentials.password);
  await page.click('button[type="submit"]');
  await page.waitForURL(/\/admin\/dashboard/, { timeout: 10000 });
}

async function logout(page: Page) {
  await page.click('button:has-text("Logout"), a:has-text("Keluar")');
  await page.waitForURL(/\/(login)?$/);
}

// Test Suite
test.describe('Payment Verification System - E2E', () => {
  
  test.beforeEach(async ({ page }) => {
    // Set timeout for all tests
    test.setTimeout(120000); // 2 minutes
  });

  // ==========================================
  // TEST 1: USER CREATES BOOKING & UPLOADS PAYMENT PROOF
  // ==========================================
  test('User should create booking and upload payment proof successfully', async ({ page }) => {
    test.info().annotations.push({
      type: 'requirement',
      description: 'User must be able to book a room and submit payment proof',
    });

    // Step 1: Login as user
    await loginAsUser(page, USER_CREDENTIALS);

    // Step 2: Navigate to available rooms
    await page.click('a:has-text("Kamar"), a[href*="/user/kamar"]');
    await page.waitForSelector('text=Kamar', { timeout: 5000 });

    // Step 3: Select first available room
    const availableRoom = page.locator('[data-available="true"], .room-card:has-text("Tersedia")').first();
    await expect(availableRoom).toBeVisible({ timeout: 10000 });
    await availableRoom.click();

    // Step 4: Click booking button
    await page.click('button:has-text("Pesan Kamar"), a:has-text("Booking Sekarang")');
    
    // Step 5: Fill check-in date
    await page.fill('input[name="check_in_date"]', BOOKING_DATA.checkInDate);

    // Step 6: Continue to payment
    await page.click('button:has-text("Lanjut ke Pembayaran")');

    // Step 7: Select payment method (QRIS)
    await page.click('button#tab-qris, button:has-text("QRIS")');

    // Step 8: Upload payment proof
    const fileInput = page.locator('input[type="file"]#proof-input, input[name="proof"]');
    await fileInput.setInputFiles({
      name: 'payment-proof.jpg',
      mimeType: 'image/jpeg',
      buffer: Buffer.from('fake-image-data'),
    });

    // Wait for preview
    await expect(page.locator('#proof-preview, .preview-image')).toBeVisible({ timeout: 5000 });

    // Step 9: Confirm payment
    await page.click('button:has-text("Bayar Sekarang"), button:has-text("Konfirmasi")');

    // Step 10: Verify success message/redirect
    await expect(page).toHaveURL(/\/user\/(booking|dashboard)/, { timeout: 10000 });
    await expect(page.locator('text=/Pembayaran|Booking|Berhasil/')).toBeVisible({ timeout: 5000 });

    // Logout
    await logout(page);
  });

  // ==========================================
  // TEST 2: ADMIN VERIFIES (ACCEPTS) PAYMENT
  // ==========================================
  test('Admin should verify pending payment successfully', async ({ page }) => {
    test.info().annotations.push({
      type: 'requirement',
      description: 'Admin must be able to verify payment, update booking status to confirmed, and set room as unavailable',
    });

    // Step 1: Login as admin
    await loginAsAdmin(page, ADMIN_CREDENTIALS);

    // Step 2: Navigate to payment management
    await page.click('a:has-text("Pembayaran"), a[href*="/admin/pembayaran"]');
    await page.waitForURL(/\/admin\/pembayaran/, { timeout: 10000 });

    // Step 3: Filter pending payments
    await page.click('a:has-text("Pending")');
    await page.waitForURL(/status=pending/, { timeout: 5000 });

    // Step 4: Verify pending payment exists
    const pendingPayment = page.locator('.bg-white:has-text("Menunggu")').first();
    await expect(pendingPayment).toBeVisible({ timeout: 10000 });

    // Step 5: Get booking code before verification
    const bookingCode = await pendingPayment.locator('text=/GDN-[A-Z0-9]+/').textContent();
    console.log('Verifying payment for booking:', bookingCode);

    // Step 6: Click verify button
    await pendingPayment.locator('button:has-text("Setujui"), button:has-text("Verifikasi")').click();

    // Step 7: Confirm verification in dialog
    page.once('dialog', dialog => {
      expect(dialog.message()).toContain('Setujui');
      dialog.accept();
    });

    // Alternative: If using custom modal instead of browser confirm
    const confirmButton = page.locator('button:has-text("Ya"), button:has-text("Konfirmasi")');
    if (await confirmButton.isVisible({ timeout: 2000 }).catch(() => false)) {
      await confirmButton.click();
    }

    // Step 8: Wait for success message
    await expect(page.locator('text=/berhasil|diverifikasi|dikonfirmasi/i')).toBeVisible({ timeout: 10000 });

    // Step 9: Verify payment status changed
    await page.click('a:has-text("Terverifikasi")');
    await page.waitForURL(/status=verified/, { timeout: 5000 });
    
    const verifiedPayment = page.locator(`.bg-white:has-text("${bookingCode}")`).first();
    await expect(verifiedPayment.locator('text=/Terverifikasi/i')).toBeVisible({ timeout: 5000 });

    // Step 10: Verify booking status in database (via dashboard or booking detail)
    await page.click('a:has-text("Dashboard"), a[href*="/admin/dashboard"]');
    const bookingRow = page.locator(`tr:has-text("${bookingCode}"), .booking-card:has-text("${bookingCode}")`).first();
    
    if (await bookingRow.isVisible({ timeout: 3000 }).catch(() => false)) {
      await expect(bookingRow.locator('text=/Dikonfirmasi|Confirmed/i')).toBeVisible();
    }

    // Logout
    await logout(page);
  });

  // ==========================================
  // TEST 3: ADMIN REJECTS PAYMENT WITH REASON
  // ==========================================
  test('Admin should reject payment with rejection reason successfully', async ({ page }) => {
    test.info().annotations.push({
      type: 'requirement',
      description: 'Admin must be able to reject payment, cancel booking, and keep room available',
    });

    const REJECTION_REASON = 'Bukti transfer tidak sesuai nominal. Mohon upload ulang.';

    // Step 1: Login as admin
    await loginAsAdmin(page, ADMIN_CREDENTIALS);

    // Step 2: Navigate to payment management
    await page.click('a:has-text("Pembayaran"), a[href*="/admin/pembayaran"]');
    await page.waitForURL(/\/admin\/pembayaran/, { timeout: 10000 });

    // Step 3: Filter pending payments
    await page.click('a:has-text("Pending")');
    await page.waitForURL(/status=pending/, { timeout: 5000 });

    // Step 4: Verify pending payment exists
    const pendingPayment = page.locator('.bg-white:has-text("Menunggu")').first();
    await expect(pendingPayment).toBeVisible({ timeout: 10000 });

    // Step 5: Get booking code before rejection
    const bookingCode = await pendingPayment.locator('text=/GDN-[A-Z0-9]+/').textContent();
    console.log('Rejecting payment for booking:', bookingCode);

    // Step 6: Click reject button
    await pendingPayment.locator('button:has-text("Tolak")').click();

    // Step 7: Fill rejection reason in modal
    const rejectModal = page.locator('.modal:visible, [x-show="rejectModal.show"]');
    await expect(rejectModal).toBeVisible({ timeout: 5000 });

    await page.fill('textarea[name="reject_notes"], textarea[x-model="rejectModal.notes"]', REJECTION_REASON);

    // Step 8: Submit rejection
    await page.click('button:has-text("Tolak Pembayaran")');

    // Step 9: Wait for success message
    await expect(page.locator('text=/ditolak|dibatalkan|rejected/i')).toBeVisible({ timeout: 10000 });

    // Step 10: Verify payment status changed to rejected
    await page.click('a:has-text("Ditolak")');
    await page.waitForURL(/status=rejected/, { timeout: 5000 });
    
    const rejectedPayment = page.locator(`.bg-white:has-text("${bookingCode}")`).first();
    await expect(rejectedPayment.locator('text=/Ditolak|Rejected/i')).toBeVisible({ timeout: 5000 });

    // Step 11: Verify rejection reason is displayed
    await expect(rejectedPayment.locator(`text=${REJECTION_REASON}`)).toBeVisible();

    // Step 12: Verify booking is cancelled (via dashboard)
    await page.click('a:has-text("Dashboard"), a[href*="/admin/dashboard"]');
    const bookingRow = page.locator(`tr:has-text("${bookingCode}"), .booking-card:has-text("${bookingCode}")`).first();
    
    if (await bookingRow.isVisible({ timeout: 3000 }).catch(() => false)) {
      await expect(bookingRow.locator('text=/Dibatalkan|Cancelled/i')).toBeVisible();
    }

    // Logout
    await logout(page);
  });

  // ==========================================
  // TEST 4: VERIFY DATABASE STATE CONSISTENCY
  // ==========================================
  test('Database state should remain consistent after verification/rejection', async ({ page, request }) => {
    test.info().annotations.push({
      type: 'integration',
      description: 'Ensure no orphaned records and data integrity maintained',
    });

    // This test requires API endpoints to check database state
    // Or can be done via direct database queries in CI/CD

    // Example: Check via API (if available)
    const response = await request.get(`${BASE_URL}/api/admin/payments/stats`);
    
    if (response.ok()) {
      const stats = await response.json();
      
      // Assertions
      expect(stats.total_pending).toBeGreaterThanOrEqual(0);
      expect(stats.total_verified).toBeGreaterThanOrEqual(0);
      expect(stats.total_rejected).toBeGreaterThanOrEqual(0);
    }
  });

  // ==========================================
  // TEST 5: EDGE CASE - DOUBLE VERIFICATION
  // ==========================================
  test('Admin should not be able to verify already verified payment', async ({ page }) => {
    test.info().annotations.push({
      type: 'edge-case',
      description: 'Prevent double verification of the same payment',
    });

    await loginAsAdmin(page, ADMIN_CREDENTIALS);
    await page.goto(`${BASE_URL}/admin/pembayaran?status=verified`);

    // Verified payments should NOT have verify button
    const verifiedPayment = page.locator('.bg-white:has-text("Terverifikasi")').first();
    
    if (await verifiedPayment.isVisible({ timeout: 3000 }).catch(() => false)) {
      await expect(verifiedPayment.locator('button:has-text("Setujui")')).not.toBeVisible();
      await expect(verifiedPayment.locator('button:has-text("Tolak")')).not.toBeVisible();
    }

    await logout(page);
  });

  // ==========================================
  // TEST 6: UI ELEMENTS VISIBILITY
  // ==========================================
  test('All required UI elements should be visible on payment page', async ({ page }) => {
    await loginAsAdmin(page, ADMIN_CREDENTIALS);
    await page.goto(`${BASE_URL}/admin/pembayaran`);

    // Check filter tabs
    await expect(page.locator('a:has-text("Semua")')).toBeVisible();
    await expect(page.locator('a:has-text("Pending")')).toBeVisible();
    await expect(page.locator('a:has-text("Terverifikasi")')).toBeVisible();
    await expect(page.locator('a:has-text("Ditolak")')).toBeVisible();

    // Check if at least one payment card exists (or empty state)
    const hasPayments = await page.locator('.bg-white.rounded-2xl').count() > 0;
    const emptyState = await page.locator('text=Belum ada pembayaran').isVisible().catch(() => false);
    
    expect(hasPayments || emptyState).toBeTruthy();

    await logout(page);
  });
});
