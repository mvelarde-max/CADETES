// OBTENER UBICACION DEL USUARIO

navigator.geolocation.getCurrentPosition(

    async(position) => {

        const lat = position.coords.latitude;

        const lon = position.coords.longitude;

        // API GRATIS
        const url = `https://wttr.in/${lat},${lon}?format=j1`;

        const response = await fetch(url);

        const data = await response.json();

        const clima =
        data.current_condition[0];

        document.getElementById("weather").innerHTML = `

            <h3>Clima Actual</h3>

            <p>${clima.temp_C}°C</p>

            <p>${clima.weatherDesc[0].value}</p>

            <p>Humedad: ${clima.humidity}%</p>

        `;
    },

    () => {

        document.getElementById("weather").innerHTML =

        "No se pudo obtener ubicación.";
    }
);