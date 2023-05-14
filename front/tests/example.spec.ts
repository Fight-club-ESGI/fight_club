import { test, expect } from '@playwright/test';

test('has title', async ({ page }) => {
    await page.goto('https://fight-club-eta.vercel.app/');

    // Expect a title "to contain" a substring.
    await expect(page).toHaveTitle(/Thunderous Knockout Fighting/);
});

test('get started link', async ({ page }) => {
    await page.goto('https://playwright.dev/');

    // Click the get started link.
    await page.getByRole('link', { name: 'Get started' }).click();

    // Expects the URL to contain intro.
    await expect(page).toHaveURL(/.*intro/);
});
