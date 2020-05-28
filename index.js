
const APIEndpoint = "https://appdsapi-6aa0.kxcdn.com/content.php?lang=de&json=1&search_text=berlin&currencyiso=EUR";
const selectPrice = document.querySelector("#inputGroupSelect04");
selectPrice.addEventListener("change", async (event) => {
    const selectPriceValue = event.target.value;
    const prices = [...document.querySelectorAll(".price")];
    const data = await fetch(APIEndpoint);
    const jsonData = await data.json();
    const thumbnails = jsonData["content"];
    prices.forEach(price => {
        const thumbnail = thumbnails.find(thumbnail => thumbnail["id"] === price.id);
        console.log(thumbnail.id);
        price.innerText = thumbnail[selectPriceValue] + " €";
    });

});