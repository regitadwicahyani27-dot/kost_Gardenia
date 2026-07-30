import { Page, expect } from '@playwright/test';

/**
 * Payment Helper for Playwright Tests
 */

export interface BookingData {
  checkInDate: string;
  paymentMethod?: 'qris' | 'bca';
}

/**
 * Create a booking with payment proof
 */
export async function createBookingWithPayment(
  page: Page,
  bookingData: BookingData = {
    checkInDate: new Date(Date.now() + 7 * 24 * 60 * 60 * 1000).toISOString().split('T')[0],
    paymentMethod: 'qris',
  }
): Promise<string | null> {
  
  // Navigate to rooms
  await page.click('a:has-text("Kamar"), a[href*="/user/kamar"]');
  await page.waitForLoadState('networkidle');

  // Select first available room
  const availableRoom = page.locator('.room-card:has-text("Tersedia"), [data-available="true"]').first();
  
  if (!await availableRoom.isVisible({ timeout: 5000 }).catch(() => false)) {
    console.warn('No available rooms found');
    return null;
  }

  await availableRoom.click();
  await page.waitForLoadState('networkidle');

  // Click booking button
  await page.click('button:has-text("Pesan"), button:has-text("Booking")');

  // Fill check-in date
  await page.fill('input[name="check_in_date"]', bookingData.checkInDate);

  // Continue to payment
  await page.click('button:has-text("Lanjut")');

  // Select payment method
  if (bookingData.paymentMethod === 'bca') {
    await page.click('button#tab-bank, button:has-text("BCA")');
  } else {
    await page.click('button#tab-qris, button:has-text("QRIS")');
  }

  // Upload fake proof
  const fileInput = page.locator('input[type="file"]#proof-input, input[name="proof"]');
  await fileInput.setInputFiles({
    name: 'payment-proof.jpg',
    mimeType: 'image/jpeg',
    buffer: Buffer.from('R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7', 'base64'), // 1x1 transparent GIF
  });

  // Wait for preview
  await page.waitForSelector('#proof-preview, .preview-image', { timeout: 5000 });

  // Confirm
  await page.click('button:has-text("Bayar"), button:has-text("Konfirmasi")');

  // Wait for success
  await page.waitForURL(/\/user\/(booking|dashboard)/, { timeout: 15000 });

  // Extract booking code if visible
  try {
    const bookingCodeText = await page.locator('text=/GDN-[A-Z0-9]{8}/').first().textContent({ timeout: 5000 });
    return bookingCodeText;
  } catch {
    return null;
  }
}

/**
 * Get payment details from admin panel
 */
export async function getPaymentDetails(page: Page, bookingCode: string) {
  await page.goto('/admin/pembayaran');
  
  const paymentCard = page.locator(`.bg-white:has-text("${bookingCode}")`).first();
  
  if (!await paymentCard.isVisible({ timeout: 5000 })) {
    return null;
  }

  const status = await paymentCard.locator('[class*="rounded-full"]').first().textContent();
  const amount = await paymentCard.locator('text=/Rp [0-9.,]+/').first().textContent();
  
  return {
    bookingCode,
    status: status?.trim(),
    amount: amount?.trim(),
  };
}

/**
 * Verify payment with confirmation dialog handling
 */
export async function verifyPayment(page: Page, bookingCode: string) {
  await page.goto('/admin/pembayaran?status=pending');
  
  const paymentCard = page.locator(`.bg-white:has-text("${bookingCode}")`).first();
  await expect(paymentCard).toBeVisible({ timeout: 10000 });

  // Handle confirmation dialog
  page.once('dialog', dialog => {
    console.log('Dialog:', dialog.message());
    dialog.accept();
  });

  await paymentCard.locator('button:has-text("Setujui"), button:has-text("Verifikasi")').click();

  // Wait for success
  await expect(page.locator('text=/berhasil|diverifikasi/i')).toBeVisible({ timeout: 10000 });
}

/**
 * Reject payment with reason
 */
export async function rejectPayment(page: Page, bookingCode: string, reason: string) {
  await page.goto('/admin/pembayaran?status=pending');
  
  const paymentCard = page.locator(`.bg-white:has-text("${bookingCode}")`).first();
  await expect(paymentCard).toBeVisible({ timeout: 10000 });

  // Click reject
  await paymentCard.locator('button:has-text("Tolak")').click();

  // Fill reason in modal
  await page.fill('textarea[name="reject_notes"]', reason);

  // Submit
  await page.click('button:has-text("Tolak Pembayaran")');

  // Wait for success
  await expect(page.locator('text=/ditolak|dibatalkan/i')).toBeVisible({ timeout: 10000 });
}
