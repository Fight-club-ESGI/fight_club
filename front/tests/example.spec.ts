import { test, expect } from '@playwright/test';

test('has title', async ({ page }) => {
    // await page.goto("https://fightclub-antoinepollet.vercel.app/");

    await page.goto("http://localhost:5173");
    await page.evaluate(() => window.localStorage.clear());

    await page.getByText('Sign in').click()

    await page.fill('#signin-email', 'antoine@gmail.com');
    await page.getByLabel('Password').fill('password');
    await page.click('[data-testid="signin-button"]');

    // Wait for navigation or any asynchronous actions to complete
    await page.reload()

    // Assert that the user is redirected to the home page
    await page.getByText('Bets').click()
    await page.getByRole('button', { name: 'bets' }).click()

    await expect(page.getByTestId('dialog-bet')).toBeVisible();

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
