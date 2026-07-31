#!/usr/bin/env node

const {
    MAX_RETRIES,
    RETRY_DELAY,
    sleep,
    isRetryable,
    createBrowser,
    createPage,
    waitForHydration,
} = require('./browser');

async function testVideo(tiktokUrl) {

    let lastError;

    for (let attempt = 1; attempt <= MAX_RETRIES; attempt++) {

        let browser;

        try {

            browser = await createBrowser();

            const { context, page } = await createPage(browser);

            // Open the TikTok page first
            await page.goto(tiktokUrl, {
                waitUntil: 'domcontentloaded',
            });

            await waitForHydration(page);

            const html = await page.content();

            require('fs').writeFileSync('debug.html', html);

            console.log('Saved debug.html');

            // Read the hydration JSON already on the page
            const universal = await page.$eval(
                '#__UNIVERSAL_DATA_FOR_REHYDRATION__',
                el => JSON.parse(el.textContent)
            );

            const video =
                universal?.__DEFAULT_SCOPE__?.['webapp.video-detail']
                    ?.itemInfo?.itemStruct?.video;

            if (!video) {
                throw new Error('Video metadata not found.');
            }

            const downloadUrl = video.downloadAddr;

            console.log('Download URL found.');

            // IMPORTANT:
            // Use the SAME browser context instead of page.goto()
            const response = await context.request.get(downloadUrl);

            console.log(JSON.stringify({
                status: response.status(),
                ok: response.ok(),
                contentType: response.headers()['content-type'],
            }));

            return;

        } catch (error) {

            lastError = error;

            if (
                attempt === MAX_RETRIES ||
                !isRetryable(error)
            ) {
                throw error;
            }

            console.error(`Retry ${attempt}`);

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
        console.error('Missing TikTok URL');
        process.exit(1);
    }

    try {

        await testVideo(url);

    } catch (e) {

        console.error(e);

        process.exit(1);

    }
}

main();