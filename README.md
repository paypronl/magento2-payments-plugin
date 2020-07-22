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
- PHP extension BC Math
- PHP extension Ctype
- PHP extension cUrl
- PHP extension DOM
- PHP extension GD
- PHP extension Hash
- PHP extension iconv
- PHP extension Intl
- PHP extension lib-libxml
- PHP extension Mbstring
- PHP extension OpenSSL
- PHP extension PDO MySQL
- PHP extension simpleXML
- PHP extension SOAP
- PHP extension XSL
- PHP extension Zip

## Installation

1. Unpack plugin archive under **magento2-dir**/app/code, full path should look like **magento2-dir**/app/code/Magento/PayProPaymentGateway

2. Run the following commands to install the module:

```shell
cd magento2-dir
sudo composer require paypro/paypro-php-v1
sudo bin/magento setup:upgrade
sudo bin/magento cache:clean
```

3. Restore permissions

```shell
sudo chown -R www-data:www-data magento2-dir/
sudo chmod -R 755 magento2-dir/
```

4. (Optional) Refresh indexes

```shell
sudo bin/magento indexer:reindex
```

### Support

Do you need help installing the PayPro plugin, please contact support@paypro.nl.

## FAQ

#### Where do I find my PayPro API key?

You can find your PayPro API key at [https://www.paypro.nl/api](https://www.paypro.nl/api) or in your dashboard at 'Webshop Koppelen'

#### When do I need to add a product ID?

When you use affiliate marketing or you want to use the mastercard or visa gateway, you have to add a product ID.

#### Where do I find my product ID?

You can find your product ID at 'Webshop Koppelen'.

## Contributing

If you want to contribute to this project you can fork the repository. Create a new branch, add your feature and create a pull request. We will look at your request and determine if we want to add it.

## Bugs

Did you find a bug and want to report it? Create a new issue where you clearly specify what the issue is and how it can be reproduced. Also make sure it is the actual plugin that creates the bug by disabling all unnecessary plugins.

## License

[MIT license](http://opensource.org/licenses/MIT)
