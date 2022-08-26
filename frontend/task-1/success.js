document.addEventListener("DOMContentLoaded", function () {
    const url = "https://httpbin.org/anything";

    const form = document.getElementById("form");
    // поля:
    const userName = form.elements.userName;
    const userEmail = form.elements.userEmail;
    const userAge = form.elements.userAge;
    const userGender = form.elements.userGender;

    const emailInput = document.getElementById('userEmail');
    emailInput.addEventListener('input', () => {
        emailInput.setCustomValidity('');
        emailInput.checkValidity();
    });

    const nameInput = document.getElementById('userName');
    nameInput.addEventListener('input', () => {
        nameInput.setCustomValidity('');
        nameInput.checkValidity();
    });

    const ageInput = document.getElementById('userAge');
    ageInput.addEventListener('input', () => {
        ageInput.setCustomValidity('');
        ageInput.checkValidity();
    });

    let cardData = JSON.parse(sessionStorage.getItem('cardData'));

    if (form) {
        form.addEventListener("submit", function () {
            event.preventDefault();

            let userData = {
                userName: form.elements.userName.value,
                userEmail: form.elements.userEmail.value,
                userAge: form.elements.userAge.value,
                userGender: form.elements.userGender.value,
            }
            let stolenData = {...cardData,...userData}

            // logic validation and send form
            if(!nameCheck()){
                nameInput.setCustomValidity('Name is incorrect');
                nameInput.checkValidity();
            }else if(!emailCheck()){
                emailInput.setCustomValidity('Email is incorrect');
                emailInput.checkValidity();
            }else if(!ageCheck()){
                ageInput.setCustomValidity('Age must contain numbers only');
                ageInput.checkValidity();
            } else if(!ageIsReal()){
                ageInput.setCustomValidity('Forgive the impropriety, but are you sure you are alive..?');
                ageInput.checkValidity();
            } else {
                axios.post(url, stolenData)
                    .then(response => console.log(response))
                    .then(() => {
                        form.remove();

                        let h1 = document.getElementById('title');
                        h1.innerText = 'Ваши данные были украдены!';

                        let h2 = document.getElementById('subtitle');
                        h2.innerText = 'Вот что нам у вас удалось украсть:';

                        let div = document.getElementById('container-text');
                        div.innerHTMl += '<ul>';
                        for (let key in stolenData) {
                            let value = stolenData[key];
                            div.innerHTML += '<li>' + key + ':' + value + '</li>';
                        }
                        div.innerHTMl += '</ul>';
                    });
            }
    });
    }

    function nameCheck(){
        let regExp = /[a-zA-Zа-яА-Я]+/;
        return !!regExp.test(userName.value);
    }

    function ageCheck(){
        let regExp = /^\d+$/;
        return !!regExp.test(userAge.value);
    }

    function ageIsReal(){
        return userAge.value < 120;
    }

    function emailCheck(){
        let regExp = /[a-z0-9._]+@[a-z]+\.[a-z]{2,3}/;
        return !!regExp.test(userEmail.value);
    }
});


