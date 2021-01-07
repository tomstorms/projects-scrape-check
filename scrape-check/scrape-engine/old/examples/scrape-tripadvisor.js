var taURL = 'https://www.tripadvisor.com/Hotel_Review-g6929645-d650741-Reviews-Coco_Grove_Beach_Resort-Tubod_San_Juan_Siquijor_Island_Visayas.html';
var runHeadless = true;

const puppeteer = require('puppeteer');

let scrape = async () => {
    const browser = await puppeteer.launch({headless: runHeadless});
    const page = await browser.newPage();

    console.log('Preparing Page: ' + taURL);

    await page.goto(taURL, {waitUntil: 'networkidle2'});

	// await page.addScriptTag({ url: 'https://code.jquery.com/jquery-3.2.1.min.js' });

    console.log('Opening all reviews...');


	// Reveal all Reviews
	await page.click('.review-container .taLnk');

	await page.waitFor(1000);

	var milliseconds = new Date().getTime();

    console.log('Taking page screenshot...');


  	await page.screenshot({path: 'tripadvisor-'+milliseconds+'.png', fullPage: true});


	let html = await page.content();

	// let bodyHTML = await page.evaluate(() => document.innerHTML);


    console.log('Saving HTML...');


	const fs = require('fs');
	var ws = fs.createWriteStream('tripadvisor-'+milliseconds+'.html');
	ws.write(html);
	ws.end();
	var ws2 = fs.createWriteStream('finishedFlag');
	ws2.end();


  //   const result = await page.evaluate(() => {


		// const $ = window.$; //otherwise the transpiler will rename it and won't work

  //   	console.log('Evaluating...');




		// let htmlOverview = $('#OVERVIEW').html();
		// let htmlReviews = $('.listContainer').find('.review-container').html();

  //       // let title = document.querySelector('#OVERVIEW').innerHTML;
  //       // let htmlOverview = document.getElementById('OVERVIEW').innerHTML;
  //       // let title2 = document.querySelector('#OVERVIEW').html;
  //       // let title3 = document.querySelector('#OVERVIEW');
  //       // let price = document.querySelector('.price_color').innerText;

  //   	console.log('Done.');


  //       return {
  //       	htmlOverview,
  //           htmlReviews,
  //       }

  //   });



    browser.close();
    return true;
};

scrape().then((value) => {
    console.log('All done:'); // Success!
});