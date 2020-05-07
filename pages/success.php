<!DOCTYPE html>
<html>
  <head>
    <link 
      rel="stylesheet" 
      href="https://stripe-samples.github.io/developer-office-hours/demo.css" 
      type="text/css" 
    />
  </head>
  <body>
    <div id="main">
      <div id="checkout">
        <div id="payment-form">
          <h1>Commande réussite !</h1>
          <div>
            Description du produit : <span id="product-description"><span>
          </div>
          <a href="/">Relancer une demo</a>
        </div>
      </div>
    </div>
    <script>
      var url = new URL(document.URL);
      var sessionId = url.searchParams.get("session_id");

      var desc = document.getElementById("product-description");
      fetch(`/retriver?session_id=${sessionId}`)
        .then(response => {
          return response.json();
        }).then(json => {
          console.log(json);
          desc.innerText = json.display_items[0].custom.description;
        });
    </script>
  </body>
</html>
