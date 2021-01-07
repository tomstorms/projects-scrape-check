 const browser = await puppeteer.launch({ headless: false });

    const page = await browser.newPage();

    await page.goto('https://tokenmarket.net/blockchain/');

    // Gather assets page urls for all the blockchains
    const assetUrls = await page.$$eval('.table-assets > tbody > tr .col-actions a:first-child', assetLinks => assetLinks.map(link => link.href));

    const results = [];

    // Visit each assets page one by one
    for (let assetsUrl of assetUrls) {
        await page.goto(assetsUrl);

        // Now collect all the ICO urls.
        const icoUrls = await page.$$eval('#page-wrapper > main > div.container > div > table > tbody > tr > td:nth-child(2) a', links => links.map(link => link.href));

        // Visit each ICO one by one and collect the data.
        for (let icoUrl of icoUrls) {
            await page.goto(icoUrl);

            const icoImgUrl = await page.$eval('#asset-logo-wrapper img', img => img.src);
            const icoName = await page.$eval('h1', h1 => h1.innerText.trim());
            // TODO: Gather all the needed info like description etc here.

            results.push([{
                icoName,
                icoUrl,
                icoImgUrl
            }]);
        }
    }

    // Results are ready
    console.log(results);