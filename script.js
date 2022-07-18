window.onload = () => {
    const clientForm = document.getElementById("client");

    clientForm.addEventListener("submit", function (event) {
        event.preventDefault();

        const formDataClient = new FormData(this);

        fetch("DbAccess.php", {
            method: "post",
            body: formDataClient
        }).then(function (response){
            return response.text();
        }).then(function (text) {
            document.getElementById("toLoad").innerHTML = text;
        }).catch(function (error) {
            console.error(error);
        });
    })

    const trafficForm = document.getElementById("statistic");

    trafficForm.addEventListener("submit", function (event) {
        event.preventDefault();

        const formDataTraffic = new FormData(this);
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "DbAccess.php");
        xhr.responseType = 'json';
        xhr.send(formDataTraffic);

        xhr.onload = () => {
            document.getElementById("toLoad").innerHTML = xhr.response;
        }
    })

    const balanceForm = document.getElementById("balance");

    balanceForm.addEventListener("submit", function (event) {
        event.preventDefault();

        const formDataBalance = new FormData(this);
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "DbAccess.php");
        xhr.responseType = 'document';
        xhr.send(formDataBalance);

        xhr.onload = () => {
            document.getElementById("toLoad").innerHTML = xhr.responseXML.body.innerHTML;
        }
    })
}


