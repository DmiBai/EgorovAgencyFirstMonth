console.log('hi');
//send cur page context
let page = 2;
console.log(globalData);

window.history.go(1);
(function($) {
    $(document).ready(function() {
        $(document).on('click', '#btn', function () {
            ajaxQuery('/wp-json/rest-api-theme/v1/pages', page++);
        });

        window.onpopstate = function(event) {
            console.log("location: " + document.location + ", state: " + JSON.stringify(event.state));
        };

        function ajaxQuery(url, sendData){
            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'html',
                data: { number : sendData },
                success: function (response) {
                    let result = JSON.parse(response);
                    console.log(result);

                    if(result === 'none'){
                        let button = document.getElementById('btn');
                        button.remove();
                    } else {
                        let div = document.createElement('div');
                        div.innerHTML = result;
                        document.body.append(div);

                        let button = document.getElementById('btn');
                        button.remove();
                        document.body.append(button);

                        let pageNum = '/page/' + (page - 1);
                        window.history.pushState({'page':page - 1}, '', pageNum);
                    }

                },
                error: function (response) {
                    let button = document.getElementById('btn');
                    button.remove();
                }
            });
        }
    });
})( jQuery );
