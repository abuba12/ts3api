fetch("/v1/clients.php").then(res=>res.json()).then((res)=>{
    const main = document.querySelector("main")
    res.forEach(client => {
        const figure = document.createElement("figure")
        const avatar = document.createElement("img")
        avatar.src = `https://api.dicebear.com/7.x/bottts/svg?seed=${client["id"]}`

        const caption = document.createElement("figcaption")
        const minutes = Math.floor(client["uptime"]/60)
        const hours = Math.floor(minutes/60)
        caption.innerText = `${client["name"]} ist online seit ${hours ? `${hours} Stunde${hours>1?"n":""} und ` : ""} ${minutes%60} Minuten`

        figure.appendChild(avatar)
        figure.appendChild(caption)
        main.appendChild(figure)
    });
})
