const api_url = "https://jsonplaceholder.typicode.com/posts";
const btn_carica = document.getElementById("btn_carica");
const tbl_post = document.getElementById("tbl_post");
btn_carica.addEventListener("click", RiempiTabella);

function RiempiTabella()
{
    console.log("riempi tabella");
    ScaricaPost(api_url)
    /*.then(post => {
        console.log("post", post);
        var row = tbl_post.insertRow(1);
        post.forEach(element => 
        { 
            var cell1 = row.insertCell(0);
            var cell2 = row.insertCell(1);
            var cell3 = row.insertCell(2);
            var cell4 = row.insertCell(3);
            cell1.innerHTML = element.userId;
            cell2.innerHTML = element.id;
            cell3.innerHTML = element.title;
            cell4.innerHTML = element.body;    
        });
    });*/
}

async function PrelevaDati(api_url)
{
    
    try 
    {    
        // 1. Esegue la richiesta fetch
        const risposta = await fetch(api_url);

        // 2. Verifica se la risposta è andata a buon fine
        if (!risposta.ok) 
        {
        throw new Error(`Errore HTTP: ${risposta.status}`);
        }

        // 3. Converte la risposta in formato JSON
        const dati = await risposta.json();

        // 4. Utilizza i dati ricevuti
        return dati;
    } 
    catch (errore) 
    {
        // Gestione di eventuali errori di rete
        console.error("Si è verificato un errore:", errore);
    }
    /*console.log("prelevo dati");
    let x = await fetch(api_url);
    let y = await x.json();
    console.log(y);
    return y;
    fetch(api_url)
    .then(function(response) {
    return response.json();
    })
    .then(function(api_url) {
    return api_url;
    });*/

    //document.getElementById("demo").innerHTML = JSON.stringify(y, null, 2);
}

function PrelevaDati2(api_url)
{    
    return fetch(api_url)
        .then(response => {
            if (!response.ok) 
            {
                throw new Error(`Errore HTTP: ${response.status}`);
            }
            return response.json();
            })
        .catch(error => {
            console.error("Si è verificato un errore:", error);
        });
}

async function ScaricaPost()
{
    const risposta = await fetch(api_url);
    const DatiPost = await risposta.json();
    console.log(DatiPost[0]);
    for (const i in DatiPost)
    {
        console.log(DatiPost[i]["title"]);
        tbl_post.innerHTML += `<tr>
            <td>${DatiPost[i]["userId"]}</td>
            <td>${DatiPost[i]["id"]}</td>
            <td>${DatiPost[i]["title"]}</td>
            <td>${DatiPost[i]["body"]}</td>
        </tr>`;
        /*let row = tbl_post.insertRow(-1);
        row.insertCell(0).textContent = DatiPost[i]["userId"];
        row.insertCell(1).textContent = DatiPost[i]["id"];
        row.insertCell(2).textContent = DatiPost[i]["title"];
        row.insertCell(3).textContent = DatiPost[i]["body"];*/
    }
}