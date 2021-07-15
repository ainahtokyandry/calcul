(() => {
    const div = document.querySelector('div')
    const url = /\[((https):\/\/(w{3}\.)?)(?<!www)(\w+-?)*\.([a-z]{2,4})\/([a-zA-Z]{1,})?.?[a-zA-Z]{1,}\]/
    const mail = /\[[a-z]{1,}@[a-z]{1,}.[a-z]{1,}\]/
    const pseudo = /\[@[a-z]{1,}\]/
    const data = fetch(
        '../Controller/Readme.php',
        {
            method: 'post',
            headers: {'Content-Type': 'application/json'}
        }
    ).then(value => value.json()).then(value => {
        value.forEach((element, key) => {
            const urls = url.exec(element[0])
            const mails = mail.exec(element[0])
            const pseudos = pseudo.exec(element[0])
            if (pseudos) {
                pseudos[0] = pseudos[0].substr(1, pseudos[0].length-2)
                element[0] = element[0].replace(pseudo, `<span>${pseudos[0]}</span>`)
            }
            if (mails) {
                mails[0] = mails[0].substr(1, mails[0].length-2)
                element[0] = element[0].replace(mail, `<a href='mailto:${mails[0]}'>${mails[0]}</a>`)
            }
            if (urls) {
                urls[0] = urls[0].substr(1, urls[0].length-2)
                element[0] = element[0].replace(url, `<a href='${urls[0]}'>${urls[0]}</a>`)
            }
            div.innerHTML += `${element[0]} ${key%2 === 0 ? '<br/>' : ''}`
        });
    })
})()