const input = document.querySelector('[type=text]'),
form = document.querySelector('form'),
resultElement = document.querySelector('#result'),
errorElement = document.querySelector('#error')

form.onsubmit = async (e) => {
    e.preventDefault()
    const fetchResult = await fetch(
        '../Controller/Calcul.php',
        {
            body: new FormData(form),
            method: 'post'
        }
    )
    const result = fetchResult.json()
    if (fetchResult.ok) {
        result.then(
            value => {
                value.forEach(element => {
                    resultElement.innerText += element + '\n'
                })
            }
        )
        errorElement.textContent = ''
    } else {
        result.then(
            value => {
                value.forEach(element => {
                    if (element === 'error') {
                        errorElement.innerText = 'Invalid format.\n'
                        return
                    }
                    errorElement.textContent += element + '\n'
                })
            }
        )
    }
}