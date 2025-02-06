fetch("https://swapi.dev/api/people/1").then(function(response) {
    response.json()
        .then(function(data) {
            console.log("JSON", data)
        })
}).catch(function(response) {
    console.error("Error ", response)
});