<!DOCTYPE html>
<html>

<head>
  <link rel="stylesheet" href="https://stripe-samples.github.io/developer-office-hours/demo.css" type="text/css" />
</head>

<body>
  <div id="main">
    <div id="checkout">
      <div id="payment-form">
        <h1><?= $message; ?></h1>
        <button id="buy">Effectuer un achat</button>
      </div>
    </div>
  </div>
  <script src="https://js.stripe.com/v3/"></script>
  <script>
    async function getStripeKey() {
      let response = await fetch('/public-key');
      let data = await response.json()
      return data.key;
    }

    var key = 'pk_test_lj9iCXomiy6vFYN7fJmM4krL00bLFmX17k';
    var stripe = Stripe(key);
    var buyButton = document.getElementById('buy');

    buyButton.addEventListener('click', function(event) {
      event.preventDefault();
      stripe.redirectToCheckout({
        sessionId: sessionId
      });
    });

    var sessionId;
    fetch('/create-session', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({})
    }).then(response => {
      return response.json();
    }).then(json => {
      sessionId = json.id;
    });
  </script>
</body>

</html>