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

    await page.addInitScript(() => {
        Object.defineProperty(navigator, 'webdriver', {
            get: () => undefined,
        });
    });

    return {
        context,
        page,
    };
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
        await page.waitForTimeout(RENDER_WAIT);
    }
}

module.exports = {
    TIMEOUT,
    MAX_RETRIES,
    RETRY_DELAY,
    sleep,
    isRetryable,
    createBrowser,
    createPage,
    waitForHydration,
};