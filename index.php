<?php

require 'vendor/autoload.php';

use Stripe\Stripe;
use Slim\Http\Request;
use Slim\Http\Response;

$dotenv = Dotenv\Dotenv::create(realpath('./'));
$dotenv->load();

$app = new Slim\App;

Stripe::setApiKey(getenv('STRIPE_SECRET_KEY'));

$app->get('/', function (Request $request, Response $response, $args) {
  $response->getBody()->write(file_get_contents("./pages/index.php"));
  return $response;
});

$app->get('/success', function (Request $request, Response $response, $args) {
  $response->getBody()->write(file_get_contents("./pages/success.php"));
  return $response;
});

$app->get('/public-key', function (Request $request, Response $response, $args) {
  return $response->withJson(['key' => getenv('STRIPE_PUBLIC_KEY')]);
});

$app->get('/retriver', function (Request $request, Response $response, $args) {
  try {
    $session = \Stripe\Checkout\Session::retrieve($_REQUEST['session_id']);
  } catch (Exception $e) {
    return 'Erreur';
  }

  return $response->withJson($session);
});

$app->get('/cancel', function (Request $request, Response $response, $args) {
  $response->getBody()->write(file_get_contents("./pages/cancel.php"));
  return $response;
});

$app->post('/create-session', function (Request $request, Response $response) use ($app) {
  $params = json_decode($request->getBody());
  try {
    $session = \Stripe\Checkout\Session::create([
      'payment_method_types' => ['card', 'ideal'],
      'line_items' => [[
        'name' => 'Photo',
        'description' => 'Fun fun photo',
        'images' => ['https://picsum.photos/280/320?random=4'],
        'amount' => 500,
        'currency' => 'eur',
        'quantity' => 1,
      ]],
      'success_url' => 'http://localhost:7272/success?session_id={CHECKOUT_SESSION_ID}',
      'cancel_url' => 'http://localhost:7272/cancel',
    ]);
  } catch (Exception $e) {
    return 'Erreur';
  }

  return $response->withJson($session);
});

$app->post('/webhook', function (Request $request, Response $response) use ($app) {
  $endpoint_secret = getenv('STRIPE_WEBHOOK_SECRET');
  $payload = $request->getBody();
  $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
  $event = null;

  try {
    $event = \Stripe\Webhook::constructEvent($payload, $sig_header, $endpoint_secret);
  } catch (\UnexpectedValueException $e) {
    http_response_code(400);
    exit();
  } catch (\Stripe\Exception\SignatureVerificationException $e) {
    http_response_code(400);
    exit();
  }

  if ($event->type == 'checkout.session.completed') {
    $session = $event->data->object;
    // handle_checkout_session($session);
  }

  return $response->withJson(['message' => 'success']);
});

$app->run();
