=== Pagalo Card - WooCommerce Payment Gateway ===
Contributors: XicoOfficial, e-globus, wowprojectsco
Donate link: https://www.xicoofficial.com/producto/wp-pagalocard-woocommerce/
Tags: pagalocard, custom gateway, woocommerce payment gateway, pagalo card, woocommerce, págalo card, payment gateway
Requires at least: 3.8
Requires PHP: 5.2.4
Tested up to: 4.9
Stable tag: 1.2.2
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

This plugin allows your store to make payments via PagaloCard service.


== Description ==

= Pagalo Card - WooCommerce Payment Gateway =

This is a test pluging to check compatibility of your woocommerce store with PagaloCard. If the transaction is successful the order status will be changed to “processing”. If the payment charge failed the order status will be changed to “cancelled”. If something is wrong with the connection between your server and the PagaloCard server the order status will be changed to “on-hold”. After successful transaction the customer is redirected to the default WP thank you page.

This is a test only plugin, feel free to hack it and make it work for your store. There is also an already tested, fully functional and with more features plugin, at [XicoOfficial.com](https://www.xicoofficial.com/producto/wp-pagalocard-woocommerce/) that you could purchase to take advantage of the latest features of PagaloCard and improve the experience of its users.

= Support =

Use the wordpress support forum for any questions regarding the plugin, or if you want to improve it.

= Get Involved =

Looking to contribute code to this plugin? Go ahead and [fork the repository over at GitHub](https://github.com/xicoofficial/wp-pagalocard-woocommerce).
(submit pull requests to the latest "release-" tag).

== Usage ==

To start using the "Pagalo Card - WooCommerce Payment Gateway", first create an account at [PagaloCard.com](https://pagalocard.com/). They will provide you with your account "IdenEmpresa", "Token", "Private key" and "Public Key".

You are also free to create a sandbox account for testing proposes at: [PagaloCard.com](https://pagalocard.com/). At the time of developing this plugin, the sandbox account is a requirement before they authorize your main account.

After you have your Pagalo Card or sandbox account active:

1. Head to Woocommerce Settings and click on the Checkout tab.
2. On checkout options you should see the option "Pagalo Card", click on it.
3. Enable the payment gateway byt checking the checkbox that reads "Enable this payment gateway".
4. Fill the form with your account information.  Dont forguet to check the "Enable Test Mode" box if your account is from the sandbox.
5. Click on save changes and you should be ready to start accepting credit cards with Pagalo Card.

== Installation ==

Installing "Pagalo Card - WooCommerce Payment Gateway" can be done either by searching for "Pagalo Card - WooCommerce Payment Gateway" via the "Plugins > Add New" screen in your WordPress dashboard, or by using the following steps:
	
1. Download the plugin via WordPress.org.
1. Upload the ZIP file through the "Plugins > Add New > Upload" screen in your WordPress dashboard.
1. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= How do I contribute? =

We encourage everyone to contribute their ideas, thoughts and code snippets. This can be done by forking the [repository over at GitHub](https://github.com/xicoofficial/wp-pagalocard-woocommerce).

== Screenshots ==

1. The pagalocard payment gateway settings page showing the texts and description that can be customized.

2. The pagalocard payment gateway settings page showing the fields that need to be filled with each individual account information.

3. The checkout page with the pagalocard payment credit card form.

== Changelog ==

= 1.2.2 =
* - Add more detailed notices for both clients and websites admins.

= 1.2.1 =
* - Remove unnecesary messages when payment fails.

= 1.2.0 =
* - Add support for Woocommerce compatibility check.
* - Go back to WP API instead of Curl

= 1.1.1 =
* Go back to using Curl isntead of WP API due to problems with multicurrency(will keep testing and go back to WP API once its fully functional for multi-currency)
* Reorder the post fields on wordpress settings page to match the orther they are displayed on the PagaloCard platform.

= 1.1.0 =
* Add banner and icon images
* Add readme.txt file

= 1.0.0 =
* Integration with PagaloCard to acept credit card payments. 


== Upgrade Notice ==

= 1.2.0 =
* Add support for Woocommerce compatibility check.

= 1.1.0 =
* Get the plugin ready to upload to the WordPress repo

= 1.0.0 =
* Initial release. Yeah!

