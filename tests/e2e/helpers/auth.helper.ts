import { Page } from '@playwright/test';

/**
 * Authentication Helper for Playwright Tests
 */

export interface Credentials {
  email: string;
  password: string;
}

export const CREDENTIALS = {
  ADMIN: {
    email: 'admin@gardenia.com',
    password: 'admin123',
  },
  USER_RERE: {
    email: 'rere@gmail.com',
    password: 'password',
  },
  USER_CIHUY: {
    email: 'cihuy@gmail.com',
    password: 'password',
  },
} as const;

/**
 * Login as any user
 */
export async function login(page: Page, credentials: Credentials, expectedUrl: RegExp = /\/dashboard/) {
  await page.goto('/login');
  
  // Wait for login form
  await page.waitForSelector('input[name="email"]', { timeout: 10000 });
  
  // Fill credentials
  await page.fill('input[name="email"]', credentials.email);
  await page.fill('input[name="password"]', credentials.password);
  
  // Submit
  await page.click('button[type="submit"]');
  
  // Wait for redirect
  await page.waitForURL(expectedUrl, { timeout: 15000 });
}

/**
 * Login as admin
 */
export async function loginAsAdmin(page: Page) {
  await login(page, CREDENTIALS.ADMIN, /\/admin\/dashboard/);
}

/**
 * Login as regular user
 */
export async function loginAsUser(page: Page, user: 'RERE' | 'CIHUY' = 'RERE') {
  const creds = user === 'RERE' ? CREDENTIALS.USER_RERE : CREDENTIALS.USER_CIHUY;
  await login(page, creds, /\/user\/dashboard/);
}

/**
 * Logout
 */
export async function logout(page: Page) {
  // Try multiple selectors for logout button
  const logoutSelectors = [
    'button:has-text("Logout")',
    'a:has-text("Logout")',
    'button:has-text("Keluar")',
    'a:has-text("Keluar")',
    'form[action*="logout"] button',
  ];

  for (const selector of logoutSelectors) {
    try {
      const element = page.locator(selector).first();
      if (await element.isVisible({ timeout: 2000 })) {
        await element.click();
        await page.waitForURL(/\/(login)?$/, { timeout: 5000 });
        return;
      }
    } catch {
      continue;
    }
  }

  // Fallback: navigate to logout route directly
  await page.goto('/logout');
  await page.waitForURL(/\/(login)?$/, { timeout: 5000 });
}
