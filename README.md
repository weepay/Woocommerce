# weepay Opencart Payment Module 
![](https://service.weepay.co/form/normal.svg)

weepay opencart is the simple and lightweight implementation of [weepay.co](https://www.weepay.co) payment service for Opencart. It's licensed under LGPL v3.0 license, therefore feel free to use it in any project or modify the source code.

# Getting Started


  ### Features
  
  - Supported version  2.0.0.0 - 2.2.0.0;
  - Other versions [Opencart 1.5.x](https://www.weepay.co)  [Opencart 2.3.x](https://www.weepay.co) [Opencart 3.0.x](https://www.weepay.co)
  - Supported One Page Checkout


## Installation
* Backup your webstore and database
* Download the source [Opencart 2.0.x Last version releases](https://github.com/weepay/Opencart-2.0/releases/), just copy all the files in the zip to your OpenCart directory.
* Click Extensions tab and Payments subtab in your OpenCart admin panel.
* Find weepay extension and install the module. Then click Edit.
* Get your api keys from weepay merchant [backend](https://www.pos.weepay.co/)
* Select "Enabled" to activate weepay plugin for your OpenCart.
* Select Form type "popup" or "responsive" to display form on checkout page.
* Select Checkout Type "onepage" or "normal" to display form on checkout page.
* Define alignment number for the payment sort order.(etc 1,2,3...)
* User on checkout page will find weepay payment extension in payment methods.
* In order details on admin interface, find "weepay Payment Module" tab in "Order History" section.

#### Notice :
* PHP 5.6 and later.
* cURL
* Opencart 2.0 and later.

### Bug report

If you found a repeatable bug, and troubleshooting tips didn't help, then be sure to [search existing issues](https://github.com/weepay/OpenCart-2.0/issues) first. Include steps to consistently reproduce the problem, actual vs. expected results, screenshots, and your OpenCart version and Payment module version number. Disable all extensions to verify the issue is a core bug.
