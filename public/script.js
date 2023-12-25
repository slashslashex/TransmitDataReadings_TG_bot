import puppeteer from 'puppeteer';
import fs from 'fs';

const dataToPuppeteer = fs.readFileSync('dataToPuppeteer.json', 'utf8');
// Преобразование JSON-строки в объект
const jsonData = JSON.parse(dataToPuppeteer);
// const { hvs, gvs } = JSON.parse(dataToPuppeteer);
// Обращение к данным

const hvs = jsonData.hvs;
const gvs = jsonData.gvs;

(async () => {
    const browser = await puppeteer.launch();
    const page = await browser.newPage();

    await page.setViewport({ width: 3024, height: 3024 });

    await page.goto('https://new.erkc-kms.ru/peredacha-pokazanij');

    await page.type('input[name="form[schetvs]"]', '136056/13');

//     await page.waitForTimeout(10000);


    await page.mouse.click(1000, 1000);

    // await page.type('input[name="form[pokazaniya1]"]', hvs);
    // await page.type('input[name="form[pokazaniya2]"]', gvs);

    // Вставляем данные в поле с использованием Puppeteer API
    await page.$eval('input[name="form[pokazaniya1]"]', (input, value) => {
        input.value = value;
    }, hvs);
    await page.click('input[name="form[pokazaniya1]"]');
    await page.mouse.click(1000, 1000);

    await page.$eval('input[name="form[pokazaniya2]"]', (input, value) => {
        input.value = value;
    }, gvs);
    await page.click('input[name="form[pokazaniya2]"]');
    await page.mouse.click(1000, 1000);

    let HVS_prev = await page.evaluate(() => {
        const input = document.querySelector('input[name="form[pred1]"]');
        return input.value;
    });

    let GVS_prev = await page.evaluate(() => {
        const input = document.querySelector('input[name="form[pred2]"]');
        return input.value;
    });

    // Сохраняем данные в файл JSON
    const dataToPhp = JSON.stringify({ HVS_prev, GVS_prev });
    fs.writeFileSync('dataToPhp.json', dataToPhp);

    await page.click('button[type="submit"][name="form[submit]"]');

        await page.waitForTimeout(5000);

    await page.screenshot({ path: 'screenshot.jpg' });

    await browser.close();
})();
