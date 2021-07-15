const input = document.querySelector('[type=text]'),
form = document.querySelector('form'),
resultElement = document.querySelector('#result'),
errorElement = document.querySelector('#error'),
submitButton = document.querySelector('[type=submit')

form.onsubmit = (e) => submitForm(e)
submitButton.onclick = (e) => submitForm(e)

const submitForm = async (e) => {
    e.preventDefault()
    const inputValue = input.value
    // I miss useState
    submitButton.disabled = true
    const fetchResult = await fetch(
        '../Controller/Calcul.php',
        {
            body: new FormData(form),
            method: 'post',
            headers: {'Content-Type':'application/json'}
        }
    )
    input.value = ''
    const result = fetchResult.json()
    submitButton.disabled = false
    errorElement.textContent = ''
    if (fetchResult.ok) {
        resultElement.innerHTML += `Your input : ${inputValue} <br/>`
        result.then(
            value => {
                value.forEach(element => {
                    resultElement.innerHTML += `${element} <br/>`
                })
                resultElement.innerHTML += '<br/>'
            }
        )
    } else {
        result.then(
            value => {
                value.forEach(element => {
                    if (element === 'error') {
                        errorElement.innerHTML = 'Invalid format.<br/>'
                    }
                })
            }
        )
    }
}