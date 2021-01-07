const puppeteer = require('puppeteer');
const request = require('request');
const path = require("path");
const fs = require("fs");

const util = require('util');
const log_file = fs.createWriteStream(__dirname + '/debug.txt', {flags : 'w'});
const log_stdout = process.stdout;
console.log = function(d) { //
    log_file.write(getTimestamp() + ': ' + util.format(d) + '\n');
    log_stdout.write(getTimestamp() + ': ' + util.format(d) + '\n');
};

const serverEndpoint = 'http://scrapecheck.lndo.site/scrape-check/scrape-engine/scrape-endpoint.php';


async function runScrape(scrapeRequest) {

    var result = {};

    // ---------------------------------------------
    // Set Time Started

    result.timeStarted = getTimestamp();

    // ---------------------------------------------
    // Setup Puppeteer Browser

    console.log('Initialising Browser...');

    const browser = await puppeteer.launch({
        headless: scrapeRequest.puppeteer_data.headless, 
        args: [
            '--lang=' + scrapeRequest.puppeteer_data.lang, 
            '--no-sandbox',
            // '--proxy-server='+scrapeRequest.proxy,
        ]
    });


    // ---------------------------------------------
    // Setup Data Folder

    // Create scrap request if it doesnt exist
    var dataPath = path.resolve(__dirname, '..');
    scrapeRequest.scrape_data_path = 'data/' + scrapeRequest.url_id;
    scrapeRequest.scrape_data_fullpath = dataPath + '/' + scrapeRequest.scrape_data_path;

    if (!fs.existsSync(scrapeRequest.scrape_data_fullpath)){
        fs.mkdirSync(scrapeRequest.scrape_data_fullpath);
    }

    // Create session folder
    var pathTimestamp = getTimestamp();
    scrapeRequest.scrape_data_path = scrapeRequest.scrape_data_path + '/' + pathTimestamp;
    scrapeRequest.scrape_data_fullpath = scrapeRequest.scrape_data_fullpath + '/' + pathTimestamp;
    fs.mkdirSync(scrapeRequest.scrape_data_fullpath);


    // ---------------------------------------------
    // Setup Browser Page

    console.log('Opening tab for: ' + scrapeRequest.title);

    const page = await browser.newPage();
    await page.goto(scrapeRequest.url, { waitUntil: 'networkidle2' });
    await page.setViewport({
        width: 1200,
        height: 800
    });


    // ---------------------------------------------
    // Attempt Scraping on 1st Page
    
    var pageNo = 1;

    var scrapeResult = await doScrape(page, scrapeRequest, pageNo);
    result.scrape_results = scrapeResult;


    // ---------------------------------------------
    // Closing Browser

    console.log('Closing Browser...');

    await browser.close();


    // FINALISE ========================================

    // Set Time Completed
    result.queue_id = scrapeRequest.queue_id;
    result.url_id = scrapeRequest.url_id;
    result.data_path = scrapeRequest.scrape_data_path;
    result.data_path_full = scrapeRequest.scrape_data_fullpath;
    result.timeCompleted = getTimestamp();
    result.status = 'ok';

    // Post to API
    postToAPI(result);

}


async function doScrape(page, scrapeRequest, pageNo) {

    var returnObj = [];

    console.log('Preparting scraping...');

    if (scrapeRequest.platform_data.platform == 'tripadvisor') {
        // Scrape TripAdvisor
        returnObjData = await doScrapeTripAdvisor(page, scrapeRequest, pageNo);
        returnObj.push(returnObjData);

        // Manage Pagination if it exists
        if (scrapeRequest.platform_data.pagination !== 'undefined') {

            console.log('Checking if Pagination available...');

            if (await page.$(scrapeRequest.platform_data.pagination.start_element) !== null) {

                // Has a next button
                // Setup for pagination

                while(await page.$(scrapeRequest.platform_data.pagination.end_element) === null) {
                    // Loop while the next button is not disabled

                    // Pagination Enabled

                    if (scrapeRequest.platform_data.pagination.paginate_limit !== 'undefined' &&
                        pageNo >= scrapeRequest.platform_data.pagination.paginate_limit) {
                        // Pagination Limit is set and  not in range
                        // End

                        console.log('Reached Pagination Limit.');

                        return returnObj;
                    }

                    // Otherwise continue loop

                    pageNo++;

                    console.log('Navigating to next page...');

                    console.log('  Click: Next Page');

                    if (await page.$(scrapeRequest.platform_data.pagination.start_element) !== null) {
                        // Wait until event complete
                        await Promise.all([
                            page.click(scrapeRequest.platform_data.pagination.start_element),
                            page.waitForNavigation({ waitUntil: 'networkidle2' })
                        ]);
                    }
                    else {
                        console.log('    Failed');
                    }

                    // Continue Scraping
                    returnObjData = await doScrapeTripAdvisor(page, scrapeRequest, pageNo);
                    returnObj.push(returnObjData);

                }

            }

        }

    }
    else {
        console.log('Unknown Platform to Scrape');
    }

    console.log('Done Scraping.');

    return returnObj;

}


async function doScrapeTripAdvisor(page, scrapeRequest, pageNo) {

    console.log('Scraping Page '+pageNo+'...');

    var events = scrapeRequest.platform_data.events;
    var pagination = scrapeRequest.platform_data.pagination;

    var pageURL = page.url();
    var fileScreenshot = '';
    var fileScrapeHTML = '';

    for(var i=0; i<events.length; i++) {

        var eventType = events[i].type;
        var eventData = events[i].data;

        if (eventType == 'click') {

            // =============================================
            // CLICK EVENT

            console.log('  ' + events[i].log_description);

            var targetSuccessful = false;

            for(var j=0; j<eventData.length; j++) {

               var targetClassName = eventData[j];

                if (await page.$(targetClassName) !== null) {
                    console.log('    Ok (method '+(j+1)+')');
                    await page.click(targetClassName);

                    targetSuccessful = true;
                    break; // this method worked
                }
            }

            if (targetSuccessful !== true) {
                console.log('    Failed');
            }

        }
        else if (eventType == 'wait') {

            // =============================================
            // WAIT EVENT

            var waitTime = parseInt(eventData);
            if (isNaN(waitTime)) waitTime = 3000; // default to 3 secs

            await page.waitFor(waitTime);

        }
        else if (eventType == 'screenshot') {

            // =============================================
            // TAKE SCREENSHOT EVENT

            console.log('  Taking screenshot...');

            fileScreenshot = scrapeRequest.url_id+'_p'+pageNo+'.png';
            fileScreenshotPath = scrapeRequest.scrape_data_fullpath+'/'+fileScreenshot;
            await page.screenshot({path: fileScreenshotPath, fullPage: true});

        }
        else if (eventType == 'save_html') {

            // =============================================
            // TAKE SCREENSHOT EVENT

            console.log('  Saving HTML...');

            fileScrapeHTML = scrapeRequest.url_id+'_p'+pageNo+'.html';
            fileScrapeHTMLPath = scrapeRequest.scrape_data_fullpath+'/'+fileScrapeHTML;

            const html = await page.content();
            var ws = fs.createWriteStream(fileScrapeHTMLPath);
            ws.write(html);
            ws.end();

        }

    }


    var returnObj = new Object();
    returnObj['file_screenshot'] = fileScreenshot;
    returnObj['file_html'] = fileScrapeHTML;
    returnObj['page_url'] = pageURL;

    return returnObj;

}













let options = {
    url: serverEndpoint,
    headers: { 'User-Agent': 'ScrapeEngine/1.0' },
}

request.get(options, function (error, response, body) {

    console.log('Retrieving Queue Data...');

    if (error === null) {
        var data = JSON.parse(body);

        // console.log(data);

        if (data.length > 0) {

            if (data.status!='fail') {

                console.log('Processing Queue Data...');

                for(var i=0; i<data.length; i++) {

                    runScrape(data[i]);

                }

            }
            else {
                console.log('Invalid Request');
            }
        }
        else {
            console.log('Invalid response');
        }
    }
    else {

        console.log('Error connecting to endpoint');
        console.log(error);

    }
    
});


async function postToAPI(resultData) {

    console.log('Pushing response to server...');

    var jsonData = encodeURIComponent(JSON.stringify(resultData));

    let options = {
        url: serverEndpoint,
        headers: { 'User-Agent': 'ScrapeEngine/1.0' },
        formData: {
            data: jsonData
        },
        followAllRedirects: true,
    }

    request.post(options, function (error, response, body) {
        if (!error && response.statusCode == 200) {
            console.log('  Completed push.');
            console.log('successful post result');
            console.log(resultData);
            console.log(body)

            // console.log('All done.');
        }
    });
}






// =====================================================================================================================================

function getTimestamp() {

    // YYYYMMDD-HHMMSS
    var now = new Date();

    var year = now.getFullYear();
    var month = ("0" + (now.getMonth() + 1)).slice(-2);
    var day = ("0" + now.getDate()).slice(-2);

    var hour = now.getHours(); if (hour < 10) { hour = "0" + hour; }
    var minute = now.getMinutes(); if (minute < 10) { minute = "0" + minute; }
    var second = now.getSeconds(); if (second < 10) { second = "0" + second; }
    return year + month + day + '-' + hour + minute + second;

}


