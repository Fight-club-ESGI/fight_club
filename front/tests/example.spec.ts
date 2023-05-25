import { test, expect } from '@playwright/test';

test('has title', async ({ page }) => {
    // await page.goto("https://fightclub-antoinepollet.vercel.app/");

    // await page.goto("http://localhost:5173/session/login");
    await page.evaluate(() => window.localStorage.clear());

    // const email = await page.getByLabel('E-mail');
    // await email.fill('pollet.antoine.alexis@gmail.com');
    // const emailValue = await page.inputValue('input#signin-email')

    // await expect(emailValue).toBe('pollet.antoine.alexis@gmail.com');

    // const password = await page.getByLabel('Password');
    // await password.fill('password');
    // const passwordValue = await page.inputValue('input#signin-password');

    // await expect(passwordValue).toBe('password');

    // await page.getByTestId('signin-button').click();

    // await page.waitForURL('http://localhost:5173/');
    // // Alternatively, you can wait until the page reaches a state where all cookies are set.
    // await expect(page.getByText('pollet.antoine.alexis@gmail.com')).toBeVisible();

    // Wait for navigation or any asynchronous actions to complete
    // Assert that the user is redirected to the home page
    // await page.click('[data-testid="bets-link"]');
    // await page.getByText('pollet.antoine.alexis@gmail.com').hover();
    // await page.getByText('Wallet').click();
    // await page.getByRole('link', { name: 'Events' }).click()
    // await page.getByRole('button', { name: 'bets' }).click()

    // await expect(page.getByTestId('dialog-bet')).toBeVisible();
    // Expect a title "to contain" a substring.
    await expect(page).toHaveTitle(/Thunderous Knockout Fighting/);
});
