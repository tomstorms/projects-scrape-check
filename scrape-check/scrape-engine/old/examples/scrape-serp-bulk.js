var taURL = 'https://www.google.com.au/search?q=seo+brisbane';
var runHeadless = true;
var lang = 'en-AU';
var waitTime = (Math.floor(Math.random() * 6) + 1) * 1000; // wait max 6 sec

const puppeteer = require('puppeteer');

let scrape = async () => {

    const browser = await puppeteer.launch({headless: runHeadless, args: ['--lang='+lang]});
    const page = await browser.newPage();

    console.log('Preparing Page: ' + taURL);



    // Load First Page
    await page.goto(taURL, {waitUntil: 'networkidle2'});
    await page.waitFor(waitTime);


    var filename = new Date().getTime();

    console.log('Taking page screenshot...');
    await page.screenshot({path: 'serp-'+filename+'_page1.png', fullPage: true});

    console.log('Saving HTML...');
    let html = await page.content();

    const fs = require('fs');
    var ws = fs.createWriteStream('serp-'+filename+'_page1.html');
    ws.write(html);
    ws.end();


    console.log('finding elements');

    let paginationLinks = await page.evaluate(() => {
      const extractedElements = document.querySelectorAll('#nav .fl');
      const items = [];
      for (let element of extractedElements) {
        items.push(element.href);
      }
      return items;
    });

    console.log(paginationLinks);

    browser.close();


    for(i=0; i < paginationLinks.length; i++) {
        var paginationLink = paginationLinks[i];

        const browser = await puppeteer.launch({headless: runHeadless, args: ['--lang='+lang]});
        const page = await browser.newPage();

        console.log('Preparing Page: ' + paginationLink);



        // Load First Page
        await page.goto(paginationLink, {waitUntil: 'networkidle2'});
        await page.waitFor(waitTime);


        var filename = new Date().getTime();

        console.log('Taking page screenshot...');
        await page.screenshot({path: 'serp-'+filename+'_page'+(i+2)+'.png', fullPage: true});

        console.log('Saving HTML...');
        let html = await page.content();

        const fs = require('fs');
        var ws = fs.createWriteStream('serp-'+filename+'_page'+(i+2)+'.html');
        ws.write(html);
        ws.end();

        browser.close();

    }



    // let texts = await page.evaluate(() => {
    //     let elNav = document.getElementById('nav');
    //     let elements= Array.from(elNav.getElementsByClassName('fl'));
    //     console.log(elements);
    //     // return elLinks;
    // });

    // console.log(texts);


    // let list_length = await page.evaluate((sel) => {
    //     let elements = Array.from(document.querySelectorAll(sel));
    //     return elements.length;
    // }, '#nav a.fl');


    // console.log(list_length[0]);
    // console.log(list_length);

    // for(let i=0; i< list_length; i++){
    //     var href = await page.evaluate((l, sel) => {
    //         let elements= Array.from(document.querySelectorAll(sel));
    //         let anchor  = elements[l].getElementsByTagName('a')[0];
    //         if(anchor){
    //             return anchor.href;
    //         }else{
    //             return '';
    //         }
    //     }, i, '#nav a.fl');
    //     console.log('--------> ', href)
    // }


    console.log('All done:'); // Success!
};

scrape().then((value) => {
    console.log(value); // Success!
});