if(process.client) {
    console.log('ENTRYPOINT', process.env.BROWSER_BASE_URL)
}
export const ENTRYPOINT = process.env.BROWSER_BASE_URL ? process.env.BROWSER_BASE_URL : "https://localhost";
