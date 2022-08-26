document.addEventListener("DOMContentLoaded", function () {
    const url = "https://httpbin.org/anything";

    const form = document.getElementById("form");
    // поля:
    const cardNumber = form.elements.cardNumber;
    const cardName = form.elements.cardName;
    const cardMonth = form.elements.cardMonth;
    const cardYear = form.elements.cardYear;
    const cardCvv = form.elements.cardCvv;

    const numberInput = document.getElementById('cardNumber');
    numberInput.addEventListener('input', () => {
        numberInput.setCustomValidity('');
        numberInput.checkValidity();
    });

    const nameInput = document.getElementById('cardName');
    nameInput.addEventListener('input', () => {
        nameInput.setCustomValidity('');
        nameInput.checkValidity();
    });

    const cvvInput = document.getElementById('cardCvv');
    cvvInput.addEventListener('input', () => {
        cvvInput.setCustomValidity('');
        cvvInput.checkValidity();
    });

    if (form) {
        form.addEventListener("submit", async function (event) {
        event.preventDefault();
        let result = await start();

        async function start() {
            let formData = {
                'cardNumber': cardNumber.value,
                'cardName': cardName.value,
                'cardMonth': cardMonth.value,
                'cardYear': cardYear.value,
                'cardCvv': cardCvv.value,
            };
                if (!lunarAlgorithm(cardNumber.value)){
                    numberInput.setCustomValidity('Card number is incorrect');
                    numberInput.checkValidity();

                } else if(!ownerNameCheck(cardName.value)){
                    nameInput.setCustomValidity('Owner name is incorrect');
                    nameInput.checkValidity();

                } else if (!cvvCheck(cardCvv.value)){
                    cvvInput.setCustomValidity('Cvv number is incorrect');
                    cvvInput.checkValidity();

                } else {
                    let response = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(formData)
                    })
                        .then(response => response.json())
                        .then(data => {
                            console.log(data.data);
                            let cardInfo = JSON.parse(data.data);
                            sessionStorage.setItem('cardData', JSON.stringify(formData));
                            location = "./success.html";
                        });
            }
        }
        });
    }
});

function lunarAlgorithm(value) {
    let regExp = /^\d+$/;
    if(!regExp.test(value)){
        return false;
    }

    value = value.replace(/\D/g, '');

    let nCheck = 0;
    let bEven = false;

    for (let n = value.length - 1; n >= 0; n--) {
        let nDigit = parseInt(value.charAt(n), 10);

        if (bEven && (nDigit *= 2) > 9) {
            nDigit -= 9;
        }

        nCheck += nDigit;
        bEven = !bEven;
    }

    return (nCheck % 10) === 0;
}

function ownerNameCheck(value){
    let regExp = /([a-z]+\s[a-z]+)/i;
    return !!regExp.test(value);
}

function cvvCheck(value){
    let regExp = /([0-9]){3}/i;
    return !!regExp.test(value);
}
//4 1 1 0 9  3 2 1 5  1 6  2 6  0 4 4
//8 1 2 0 18 3 4 1 10 1 12 2 12 0 8 4
//8 1 2 0 9  3 4 1 1  1 3  2 3  0 8 4
//11 12 6 4 5 12
//50