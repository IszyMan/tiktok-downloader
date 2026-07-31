#!/usr/bin/env node

const {
    TIMEOUT,
    MAX_RETRIES,
    RETRY_DELAY,
    sleep,
    isRetryable,
    createBrowser,
    createPage,
    waitForHydration,
} = require('./browser');

async function fetchPage(url) {
    let lastError;

    for (let attempt = 1; attempt <= MAX_RETRIES; attempt++) {
        let browser;

        try {
            browser = await createBrowser();

            const { page } = await createPage(browser);

            await page.goto(url, {
                waitUntil: 'domcontentloaded',
                timeout: TIMEOUT,
            });

            await waitForHydration(page);

            return await page.content();

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