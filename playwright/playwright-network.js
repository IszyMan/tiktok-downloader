#!/usr/bin/env node

const fs = require('fs');

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

function isInteresting(url) {

    const lower = url.toLowerCase();

    return (
        lower.includes('/video/') ||
        lower.includes('playaddr') ||
        lower.includes('downloadaddr') ||
        lower.includes('.mp4') ||
        lower.includes('.m3u8') ||
        lower.includes('aweme') ||
        lower.includes('feed')
    );
}

async function inspectNetwork(url) {

    let lastError;

    for (let attempt = 1; attempt <= MAX_RETRIES; attempt++) {

        let browser;

        try {

            browser = await createBrowser();

            const { page } = await createPage(browser);

            const requests = [];
            const responses = [];

            page.on('request', request => {

                if (!isInteresting(request.url())) {
                    return;
                }

                requests.push({
                    method: request.method(),
                    resourceType: request.resourceType(),
                    url: request.url(),
                    headers: request.headers(),
                });

            });

            page.on('response', async response => {

                if (!isInteresting(response.url())) {
                    return;
                }

                responses.push({
                    status: response.status(),
                    url: response.url(),
                    headers: response.headers(),
                });

            });

            await page.goto(url, {
                waitUntil: 'domcontentloaded',
                timeout: TIMEOUT,
            });

            await waitForHydration(page);

            console.log('Page hydrated.');

            //
            // Try to start the video
            //

            try {

                await page.locator('video').click({
                    timeout: 5000,
                });

                console.log('Clicked video.');

            } catch {

                console.log('Video click skipped.');

            }

            //
            // Force playback
            //

            await page.evaluate(() => {

                const video = document.querySelector('video');

                if (video) {

                    video.muted = true;

                    video.play().catch(() => {});

                }

            });

            console.log('Playback requested.');

            //
            // Wait while network requests happen
            //

            await page.waitForTimeout(8000);

            fs.writeFileSync(
                'network.json',
                JSON.stringify(
                    {
                        requests,
                        responses,
                    },
                    null,
                    2
                )
            );

            console.log(
                `Captured ${requests.length} requests and ${responses.length} responses.`
            );

            return;

        } catch (error) {

            lastError = error;

            if (
                attempt === MAX_RETRIES ||
                !isRetryable(error)
            ) {
                throw error;
            }

            console.log(`Retry ${attempt}`);

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

        await inspectNetwork(url);

    } catch (error) {

        console.error(error);

        process.exit(1);

    }

}

main();