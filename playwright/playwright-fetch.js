#!/usr/bin/env node

const { chromium } = require('playwright');

const TIMEOUT = 60000;
const RENDER_WAIT = 5000;
const HYDRATION_TIMEOUT = 15000;

const MAX_RETRIES = 2;
const RETRY_DELAY = 2000;

function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

function isRetryable(error) {
    if (!(error instanceof Error)) {
        return false;
    }

    const message = error.message.toLowerCase();

    return (
        message.includes('timeout') ||
        message.includes('connection') ||
        message.includes('network') ||
        message.includes('reset')
    );
}

async function createBrowser() {
    return chromium.launch({
        headless: true,

        args: [
            '--disable-blink-features=AutomationControlled',
            '--disable-dev-shm-usage',
            '--no-sandbox',
        ],
    });
}

async function createPage(browser) {
    const context = await browser.newContext({
        userAgent:
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36',

        viewport: {
            width: 1366,
            height: 768,
        },

        locale: 'en-US',

        timezoneId: 'Africa/Lagos',

        extraHTTPHeaders: {
            'Accept-Language': 'en-US,en;q=0.9',
        },
    });

    const page = await context.newPage();

    page.setDefaultTimeout(TIMEOUT);
    page.setDefaultNavigationTimeout(TIMEOUT);

    // Remove one of Playwright's obvious automation flags.
    await page.addInitScript(() => {
        Object.defineProperty(navigator, 'webdriver', {
            get: () => undefined,
        });
    });

    return page;
}

async function waitForHydration(page) {
    try {
        await page.waitForFunction(
            () =>
                document.querySelector(
                    '#__UNIVERSAL_DATA_FOR_REHYDRATION__'
                ),
            {
                timeout: HYDRATION_TIMEOUT,
            }
        );
    } catch {
        // Hydration script didn't appear.
        // Give TikTok a little extra time before returning.
        await page.waitForTimeout(RENDER_WAIT);
    }
}

function detectChallenge(html) {
    const lower = html.toLowerCase();

    return (
        lower.includes('captcha') ||
        lower.includes('verify you are human') ||
        lower.includes('security check')
    );
}

async function fetchPage(url) {
    let lastError;

    for (let attempt = 1; attempt <= MAX_RETRIES; attempt++) {
        let browser;

        try {
            browser = await createBrowser();

            const page = await createPage(browser);

            await page.goto(url, {
                waitUntil: 'domcontentloaded',
                timeout: TIMEOUT,
            });

            await waitForHydration(page);

            const html = await page.content();


            return html;
            
        } catch (error) {
            lastError = error;

            if (
                attempt === MAX_RETRIES ||
                !isRetryable(error)
            ) {
                throw error;
            }

            console.error(
                `Attempt ${attempt} failed. Retrying in ${RETRY_DELAY}ms...`
            );

            await sleep(RETRY_DELAY);
        } finally {
            if (browser) {
                await browser.close();
            }
        }
    }

    throw lastError;
}

async function main() {
    const url = process.argv[2];

    if (!url) {
        console.error('No TikTok URL provided.');
        process.exit(1);
    }

    try {
        const html = await fetchPage(url);

        process.stdout.write(html);

        process.exit(0);
    } catch (error) {
        console.error(
            error instanceof Error
                ? error.message
                : String(error)
        );

        process.exit(1);
    }
}

main();