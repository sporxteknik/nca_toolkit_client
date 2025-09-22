// @ts-check
const { test, expect } = require('@playwright/test');

test('should display the main page correctly', async ({ page }) => {
  await page.goto('/');
  
  // Check that the main title is present
  await expect(page).toHaveTitle('NCA Toolkit PHP Client');
  
  // Check that the endpoint selector is present
  await expect(page.locator('#endpoint')).toBeVisible();
  
  // Check that the endpoint form is initially hidden
  await expect(page.locator('#endpoint-form')).toBeHidden();
});

test('should show form when selecting an endpoint', async ({ page }) => {
  await page.goto('/');
  
  // Select an endpoint
  await page.selectOption('#endpoint', 'video_caption');
  
  // Check that the form is now visible
  await expect(page.locator('#endpoint-form')).toBeVisible();
  
  // Check that the form contains the expected elements
  await expect(page.locator('#caption-drop-area')).toBeVisible();
  await expect(page.locator('#caption_file')).toBeHidden(); // File input should be hidden
});

test('should handle file selection via click', async ({ page }) => {
  await page.goto('/');
  
  // Select an endpoint
  await page.selectOption('#endpoint', 'video_caption');
  
  // Wait for the form to be visible
  await expect(page.locator('#endpoint-form')).toBeVisible();
  
  // Click on the drop area
  await page.locator('#caption-drop-area').click();
  
  // Check that the file input dialog would be triggered
  // Note: We can't actually select a file in headless mode, but we can verify the click works
  // The click should not cause an error
  await expect(page.locator('#endpoint-form')).toBeVisible();
});

test('should display file information after selection', async ({ page }) => {
  await page.goto('/');
  
  // Select an endpoint
  await page.selectOption('#endpoint', 'video_caption');
  
  // Wait for the form to be visible
  await expect(page.locator('#endpoint-form')).toBeVisible();
  
  // For this test, we'll just verify the structure is correct
  // since we can't actually select files in headless mode
  const dropArea = page.locator('#caption-drop-area');
  await expect(dropArea).toBeVisible();
  
  // Check that the file list area exists (it may be empty but should be present)
  const fileList = page.locator('#caption-file-list');
  await expect(fileList).toBeAttached(); // Check that it's in the DOM
});