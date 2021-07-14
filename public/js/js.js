const input = document.querySelector('[type=text]'),
form = document.querySelector('form'),
resultElement = document.querySelector('#result'),
errorElement = document.querySelector('#error'),
submitButton = document.querySelector('[type=submit')

form.onsubmit = (e) => submitForm(e)

const submitForm = async (e) => {
    e.preventDefault()
    // I miss useState
    submitButton.disabled = true
    const fetchResult = await fetch(
        '../Controller/Calcul.php',
        {
            body: new FormData(form),
            method: 'post'
        }
    )
    const result = fetchResult.json()
    submitButton.disabled = false
    if (fetchResult.ok) {
        result.then(
            value => {
                value.forEach(element => {
                    resultElement.textContent += element + '\n'
                })
            }
        )
    } else {
        result.then(
            value => {
                value.forEach(element => {
                    if (element === 'error') {
                        errorElement.textContent = 'Invalid format.\n'
                        return
                    }
                })
            }
        )
    }
}