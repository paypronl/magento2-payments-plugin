![PayPro](https://paypro.nl/images/logo-ie.png)

# PayPro Gateways - Magento 2

[![Software License](https://img.shields.io/badge/License-MIT-yellow.svg)](LICENSE)

With this plugin you easily add all PayPro payment gateways to your Magento 2 webshop. Currently the plugin supports the following gateways:

- Afterpay
- Bancontact
- Banktransfer
- Direct Debit
- iDEAL
- Mastercard
- PayPal
- Sofort Digital
- Sofort Physical
- Visa

## Requirements

- PHP version 7.2 or greater
- Magento 2.3+

## Install the module using Composer
Magento 2 uses Composer to manage module dependencies. We can also use Composer to install the module.

1. Run the following command in your Magento directory:
   ```shell
   $ composer require paypro/magento2
   ```

2. Run the setup command of Magento:
   ```shell
   $ php bin/magento setup:upgrade
   > php bin/magento cache:clean
   ```

3. If you run Magento in production mode make sure to recompile the static files:
   ```shell
   $ php bin/magento setup:di:compile
   > php bin/magento setup:static-content:deploy
   ```

## Module setup
After installation we need to setup the module correctly to start processing payments.

1. First go to your Magento admin portal and go to **Stores** -> **Configuration** -> **Sales** -> **Payment Methods** -> **Other Payment Methods**

   Here you should see PayPro Gateway and PayPro iDEAL, as well as other PayPro payment methods.

2. We need to fill in an API key in the PayPro gateway settings. You can find your PayPro API key in your [Webshop Koppelen](https://www.paypro.nl/koppelen/webshops) or in [API Keys](https://www.paypro.nl/api/keys).

   If you want to use Affiliate Marketing or Mastercard and Visa you also have to supply your Product ID.

3. Enable **PayPro iDEAL** or any other PayPro payment method by setting **Enabled** to **Yes**.

4.  Make sure to click the **Save Config** button.

5. That's it! You should now see the enabled payment methods during the checkout process.

## Support

If you need help with installing the module, please contact [support@paypro.nl](mailto:support@paypro.nl).

## Contributing

If you want to contribute to this project you can fork the repository. Create a new branch, add your feature and create a pull request. We will look at your request and determine if we want to add it.

## Bugs

Did you find a bug and want to report it? Create a new issue where you clearly specify what the issue is and how it can be reproduced. Also make sure it is the actual plugin that creates the bug by disabling all unnecessary plugins.

## License

[MIT license](http://opensource.org/licenses/MIT)
