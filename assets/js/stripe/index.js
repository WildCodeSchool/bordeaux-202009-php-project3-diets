
function Get(yourUrl) {
    var Httpreq = new XMLHttpRequest(); // a new request
    Httpreq.open("GET", yourUrl, false);
    Httpreq.send(null);
    return Httpreq.responseText;
}

var datas = JSON.parse(Get('/panier/jsonStripe'));
var public_key = datas["publickey"];
var account = datas['account'];


    var stripe = Stripe(public_key, {
    stripeAccount: account
});
    console.log(stripe);
    var checkoutButton = document.getElementById('checkout-button');

    checkoutButton.addEventListener('click', function() {
    // Create a new Checkout Session using the server-side endpoint you
    // created in step 3.
    fetch('/paiement/creation-session/', {
        method: 'POST',
    })
        .then(function(response) {
            return response.json();
        })
        .then(function(session) {
            return stripe.redirectToCheckout({ sessionId: session.id });
        })
        .then(function(result) {
            // If `redirectToCheckout` fails due to a browser or network
            // error, you should display the localized error message to your
            // customer using `error.message`.
            if (result.error) {
                alert(result.error.message);
            }
        })
        .catch(function(error) {
            console.error('Error:', error);
        });
});
