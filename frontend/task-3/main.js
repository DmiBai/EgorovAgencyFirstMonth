document.addEventListener("DOMContentLoaded", function () {
	//
	// main variables
	//http://localhost:63342/Task-3/

	let auth = document.getElementById("modal-auth");
	let cart = document.getElementById("modal-cart");
	let cartClose = document.getElementById("modal-cart-close");
	let authClose = document.getElementById("modal-auth-close");
	let cartButton = document.getElementById("toggle-shown-cart");
	let addProductButton = document.getElementById("add-product-in-cart");
	let deleteProductButton = document.getElementById("delete-product-in-cart");
	let exitButton = document.getElementById("exit-user");
	let login = document.getElementById("login");
	let password = document.getElementById("password");
	let productsAmount = document.getElementById("amount-products");
	let order = document.getElementById("makeOrder");
	const content = document.getElementById("content");
	let form = document.getElementById("form");

	const securityToken = "secret";
	let itemsInBasket = [];
	const getEmptyProduct = () => ({
		id: itemsInBasket.length + 1,
	});

	const bc = new BroadcastChannel('products');
	bc.addEventListener('message', function (e) {
		console.log(e);
		itemsInBasket = e.data;
		parseContent(itemsInBasket);
	});

	//
	// helpers
	//
	cartButton.addEventListener("click", function (event) {
		if (sessToken = getCookie('token')) {
			axios.post('check_token', {
				token: sessToken,
			}).then(response =>{
				let res = (response);
				console.log(res);
				if(res !== 'false'){
					parseContent(itemsInBasket);
					toggleClass(cart);
					exitButton.classList.add("show");
				} else {
					alert('invalid token');
				}
			});
		} else {
			toggleClass(auth);
			// auth.classList.add("show");
		}
	});

	form.addEventListener("submit", function (event){
		event.preventDefault();
		if ((login.value != '') && (password.value != '')) {
			setTimeout(() => {
				axios.post('get_token').then(response => {
					let res = (response.data);
					console.log(res);
					setCookie('token', res);
					cart.classList.add("show");
					auth.classList.remove("show");
					exitButton.classList.add("show");
					parseContent(itemsInBasket);
				});
			}, 1000);
		}
	});

	authClose.addEventListener("click", function(){
		toggleClass(auth);
	});

	cartClose.addEventListener("click", function(){
		toggleClass(cart);
	});

	exitButton.addEventListener("click", function(){
		eraseCookie("token");
		cart.classList.remove("show");
		toggleClass(exitButton);
		itemsInBasket = [];
		parseContent(itemsInBasket);
		bc.postMessage(itemsInBasket);
	});

	order.addEventListener("click", function(){
		itemsInBasket = [];
		productsAmount.innerHTML = '0';
		cart.classList.remove("show");
		bc.postMessage([]);
	});

	addProductButton.addEventListener("click", function (){
		if(!isCookieExpired()) {
			addProduct();
		}
		bc.postMessage(itemsInBasket);
	});

	deleteProductButton.addEventListener("click", function (){
		if(!isCookieExpired()) {
			removeProduct();
		}
		bc.postMessage(itemsInBasket);
	});

	const parseContent = (data = []) => {
		console.log('hello');
		setOrderPrice();
		productsAmount.innerHTML = itemsInBasket.length;

		if (data && data.length) {
			content.innerHTML = "";

			data.forEach((item, index) => {
				const div = document.createElement("div");
				div.classList.add("cart-body-content-items-item");
				div.innerHTML = `<div class="cart-body-content-items-item">
            <div class="cart-body-content-items-item-img">
                <img src="https://placehold.it/100x100" alt="">
            </div>
            <div class="cart-body-content-items-item-title">Название товара ${index}</div>
            <div class="cart-body-content-items-item-price">Цена: 10${index} руб.</div>
            <div class="cart-body-content-items-item-count">Количество: 1</div>
        	</div>`;
				content.appendChild(div);
			});
		} else {
			content.innerHTML = '';
		}
	};

	const addProduct = () => {
		const product = getEmptyProduct();
		itemsInBasket.push(1);
		parseContent(itemsInBasket);
	};

	const removeProduct = () => {
		itemsInBasket.pop();
		if (itemsInBasket.length > 0) {
			parseContent(itemsInBasket);
		} else {
			content.innerHTML = '';
			productsAmount.innerHTML = 0;
			parseContent(itemsInBasket);
		}
		sessionStorage.setItem('products', JSON.stringify(itemsInBasket));
	};

	const toggleClass = (el, className = "show") => {
		if (el.classList.contains(className)) {
			el.classList.remove(className);
		} else {
			el.classList.add(className);
		}
	};

	//
	// main logic here
	//
	function isCookieExpired() {
		console.log(getCookie('token'));
		if (getCookie('token') !== '') {
			return false;
		}
		exitButton.classList.remove("show");
		cart.classList.remove("show");
		alert("session expired");
	}

	function setCookie(name, value, time = 60) {
		let expires = "";
		if (time) {
			let date = new Date();
			date.setTime(date.getTime() + (time * 60 * 60));
			expires = "; expires=" + date.toUTCString();
		}
		document.cookie = name + "=" + (value || "") + expires + "; path=/";
	}

	function getCookie(name) {
		let nameEQ = name + "=";
		let ca = document.cookie.split(';');
		for (let i = 0; i < ca.length; i++) {
			let c = ca[i];
			while (c.charAt(0) == ' ') c = c.substring(1, c.length);
			if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
		}
		return null;
	}

	function eraseCookie(name) {
		document.cookie = name + '=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
	}

	function setOrderPrice() {
		let price;
		let orderPrice = document.getElementsByClassName("cart-body-total-title")[0];
		if (itemsInBasket.length > 0) {
			let myIndex = 0;
			price = 100 * itemsInBasket.length;
			itemsInBasket.forEach(item  => {
				price += myIndex;
				myIndex++;
			})

		} else {
			price = 0;
		}
		orderPrice.innerHTML = 'Итого: ' + price + 'руб.';
	}
});