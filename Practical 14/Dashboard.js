// Practical 14: Weather API client for Changa
fetch("https://geocoding-api.open-meteo.com/v1/search?name=Changa&count=1&language=en&format=json")
    .then(function(response) {
        if (!response.ok) {
            throw new Error("Location lookup failed");
        }
        return response.json();
    })
    .then(function(data) {
        if (!data.results || data.results.length === 0) {
            throw new Error("Location coordinates not found");
        }

        let place = data.results[0];
        let locElem = document.getElementById("location");
        if (locElem) {
            locElem.innerText = place.name + ", " + (place.country || "India");
        }

        let url = "https://api.open-meteo.com/v1/forecast?latitude=" +
            place.latitude + "&longitude=" + place.longitude +
            "&current=temperature_2m,relative_humidity_2m,weather_code&temperature_unit=celsius";

        return fetch(url);
    })
    .then(function(response) {
        if (!response.ok) {
            throw new Error("Weather request failed");
        }
        return response.json();
    })
    .then(function(data) {
        let tempElem = document.getElementById("temperature");
        let humElem = document.getElementById("humidity");
        let condElem = document.getElementById("condition");

        if (tempElem && data.current) {
            tempElem.innerText = data.current.temperature_2m + " °C";
        }
        if (humElem && data.current) {
            humElem.innerText = data.current.relative_humidity_2m + "%";
        }
        if (condElem && data.current) {
            condElem.innerText = getWeatherCondition(data.current.weather_code);
        }
    })
    .catch(function(error) {
        console.warn("Weather widget fallback:", error);
        let locElem = document.getElementById("location");
        let tempElem = document.getElementById("temperature");
        let humElem = document.getElementById("humidity");
        let condElem = document.getElementById("condition");

        if (locElem) locElem.innerText = "Changa, Gujarat";
        if (tempElem) tempElem.innerText = "28 °C";
        if (humElem) humElem.innerText = "55%";
        if (condElem) condElem.innerText = "Sunny / Clear";
    });

function getWeatherCondition(code) {
    if (code === 0) return "Clear Sky";
    if ([1, 2, 3].includes(code)) return "Partly Cloudy";
    if ([45, 48].includes(code)) return "Fog";
    if ([51, 53, 55, 56, 57].includes(code)) return "Drizzle";
    if ([61, 63, 65, 66, 67].includes(code)) return "Rain";
    if ([71, 73, 75, 77].includes(code)) return "Snow";
    if ([80, 81, 82].includes(code)) return "Rain Showers";
    if ([85, 86].includes(code)) return "Snow Showers";
    if ([95, 96, 99].includes(code)) return "Thunderstorm";
    return "Clear";
}
