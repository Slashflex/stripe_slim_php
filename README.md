## Stripe checkout session with slim php framework

### Requirements
- Stripe account setup in test mode
- Composer / PHP installed

### Install
```sh
composer install
```
Rename the file ```.exemple.env```
```sh
mv .exemple.env .env
```
And add your own ```key``` on each variable
Also you have to add you own public key in ```pages/index.php``` on line ```25```

### Start
```sh
composer start
```

Now you can open ```localhost:7272``` in you web browser and test it out# stripe_slim_php
