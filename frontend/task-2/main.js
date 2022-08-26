// FAKE api : https://dummyjson.com/docs

document.addEventListener("DOMContentLoaded", function () {
	const url = "https://dummyjson.com";

	const cryptKey = 'MegaSecccRetkeey';
	const iv   = CryptoJS.enc.Utf8.parse('1234567812345678');

	const form = document.getElementById("form");
	// поля:
	const methodElem = form.elements.method;
	const idElem = form.elements.idProduct;
	const titleElem = form.elements.title;
	const descriptionElem = form.elements.description;
	const thumbnailElem = form.elements.thumbnail;
	const imagesElem = form.elements.images;

	let currentMethod = "GET";

	// axios.interceptors.request.use(request => {
	// 	console.log('Starting Request', JSON.stringify(request, null, 2))
	// 	return request
	// })

	const parseContent = (data = []) => {
		const content = document.getElementById("content");


		if (data && data.length) {
			content.innerHTML = "";

			data.forEach((item) => {
				const div = document.createElement("div");
				div.classList.add("item");
				div.setAttribute("id", "div" + item.id);
				div.innerHTML = `
            <div class="title" id="title${item.id}">
                <h3>${item.title}</h3>
            </div>
            <div class="content" id="content${item.id}">`;
				if (item.thumbnail) {
					div.innerHTML += `<img src="${item.thumbnail}"></img>`;
				}
				div.innerHTML += `<p id="desc${item.id}">${item.description || ""}</p></div>`;

				div.innerHTML += `<div class="price" id="price${item.id}">${item.price || 0}</div><br/>`;
				content.appendChild(div);
			});
		}
	};

	axios.get("https://dummyjson.com/products").then(res => {
		console.log(res); // Результат ответа от сервера
		parseContent(res.data.products);
	});
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////
	if (form) {
		form.addEventListener("submit", function (event) {
			event.preventDefault();
			let thumbnailLoaded = 0;

			if(isFilled()) {

				let thumbnailBase64 = "";
				if(thumbnailElem.value !== '') {
					let thumbnailReader = new FileReader();
					thumbnailReader.readAsDataURL(thumbnailElem.files[0]);
					thumbnailReader.onloadend = function () {
						thumbnailBase64 = thumbnailReader.result;
						thumbnailLoaded = 1;

					}
				} else {
					thumbnailLoaded = 1;
				}

				let imagesBase64 = [];
				if(imagesElem.value !== '') {
					Array.from(imagesElem.files).forEach(file => {
						let imagesReader = new FileReader();
						imagesReader.readAsDataURL(file);
						imagesReader.onloadend = function () {
							imagesBase64.push(imagesReader.result);
						}
					});
				}

				switch (currentMethod) {
					case "GET": {
						axios.get("https://dummyjson.com/products/" + idElem.value).then(res => {
							console.log(res);
							console.log(encrypt(thumbnailBase64));
							//console.log(decrypt(encrypt(thumbnailBase64)));
							//to get base64 uncomment upper line
							console.log(decrypt(encrypt("CRYPT_PROOFS")));
						});
						break;
					}

					case "PUT": {
						axios.put("https://dummyjson.com/products/" + idElem.value, {
							title: encrypt(titleElem.value),
							description: encrypt(descriptionElem.value),
							thumbnail: encrypt(thumbnailBase64),
							images: encryptArray(imagesBase64),
						}, {
							headers:{
								'Content-Type': 'application/json'
							}
						}).then(res => {
							console.log(res);
							let title = document.getElementById("title" + idElem.value);
							let description = document.getElementById("desc" + idElem.value);
							title.innerHTML = "<h3>" + titleElem.value + "</h3>";
							description.innerText = descriptionElem.value;
						});
						break;
					}

					case "DELETE": {
						axios.delete("https://dummyjson.com/products/" + idElem.value)
							.then(res => {
								console.log(res);
								let div = document.getElementById("div" + idElem.value);
								if(div) {
									div.remove();
								}
							});
						break;
					}

					case "POST": {
						axios.post("https://dummyjson.com/products/add", {
							title: encrypt(titleElem.value),
							description: encrypt(descriptionElem.value),
							thumbnail: encrypt(thumbnailBase64),
							images: encryptArray(imagesBase64),
						}).then(res => {
							console.log(res);
						});
						break;
					}
				}
			} else {
				alert('Incorrect form filling for this type of request');
			}
		});
	}

	if (methodElem) {
		methodElem.addEventListener("change", function () {
			currentMethod = methodElem.value;
		});
	}

	function isFilled() {
		if ((currentMethod === "DELETE") || (currentMethod === "GET")) {
			if (idElem.value === '') {
				return false;
			}
		} else if (currentMethod === "PUT") {
			if(isEmpty()){
				return false;
			}
		}
		else {
			if(containsEmpty()){
				return false;
			}
		}
		return true;
	}

	function containsEmpty(){
		return (idElem.value === '') || (descriptionElem.value === '')
			|| (titleElem.value === '') || (thumbnailElem.value === '')
			|| (imagesElem.value === '');
	}

	function isEmpty(){
		return (descriptionElem.value === '') && (titleElem.value === '')
			&& (thumbnailElem.value === '') && (imagesElem.value === '');
	}

	function encrypt(value){
		return CryptoJS.AES.encrypt(value, cryptKey,
		{ iv: iv, mode:CryptoJS.mode.CBC}).toString();
	}

	function decrypt(value){
		return CryptoJS.AES.decrypt(value, cryptKey,
			{ iv: iv, mode:CryptoJS.mode.CBC}).toString(CryptoJS.enc.Utf8);
	}

	function encryptArray(values){
		let resArray = [];
		values.forEach(item =>{
			resArray.push(CryptoJS.AES.encrypt(item, cryptKey,
				{ iv: iv, mode:CryptoJS.mode.CBC}).toString());
		})
		return resArray;
	}

	function decryptArray(values){
		let resArray = [];
		values.forEach(item =>{
			resArray.push(CryptoJS.AES.decrypt(item, cryptKey,
				{ iv: iv, mode:CryptoJS.mode.CBC}).toString(CryptoJS.enc.Utf8));
		})
		return resArray
	}

});



