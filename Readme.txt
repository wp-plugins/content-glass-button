=== Plugin Name ===
Contributors: Rhizome Networks
Donate link: http://www.contentglass.com
Tags: Social Sharing
Version: 1.0.5.4
Requires at least: 3.1
Tested up to: 4.2.2
Stable tag: 1.0.6
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Content Glass is a social-tool that allow website users to share widgets with peers-group in association with page address, domain or TLD.

== Description ==

<h3>Overview</h3>
Content Glass is a pending patent State Oriented Sharing platform that provide a combination of 'Sharing tools', 'API' for developers and data 'services'. Content Glass platform is evolving and currently running in a beta stage.

This plugin install the CG-Button Social-Tool on your website. Currently that main social-tool experimented as part of beta stage.

CG-Button is in fact an instance of Content Glass application, developed with CG Web client API that present a small button widget on the screen. Clicking the button users are navigated to gallery of content Glass Widgets. These CG widgets can be shared with friends.

CG sharing is managed by layers. Each layer represent a 'state' for which widget is associated. A state can be simple code such as page-url but it can also be composed of multiple fields and properties. While CG Web client API provide a way to share widget over public layers, the CG-Button tool provide only widgets that can be shared by closed peers-groups! (The only exception is Experts-Widget that is part of <a href="http://experts.glass">Experts Glass</a> framework), and in the scope of one of three states - page-url (default for most cases, domain, TLD).

Sharing on public layers can be archived only when website owner develop its own CG application. Developing your own CG app provide much more flexibility of controlling the state-oriented-sharing scenario but is subject for more advanced discussion.

When visitor of your website share widget with closed peers-groups of friends, all the participants are able to see the widget when encountering the same state. In the case of CG app for website it means for example that if a widget was associated with page, the page URL is the context state for which widget is associated with and friends will be able to see the widget (after login for shared account) if visiting the same page. The principle of state-oriented-sharing is much broader then that and not limited to web apps as described below and in more details on our website.

The user experience that is theretofore achieved by installing the CG-Button WP plugin is that of letting visitors of your website a way for sharing various CG widgets with friends. And of course CG API can be used for creating your own widgets and declare then on your website so that your custom widget library will automatically be imported into the CG-Button widgets gallery.

Content Glass provide application framework that let various applications load in a similar manner by the core Web client API. The current plugin is equipped with two CG apps:

- General CG-Button : include multiple widgets of various kinds
- E-commerce CG-Button: include small selection of widgets + experts-glass framework

Future versions of this plugin will let you set your own Content Glass apps too.

Installing Content Glass module on your website provide the following advantages:

1. Exposing CG sharing tools to visitors of your website.
2. Creating your own widgets or even your own CG app and run on your website.
3. Having applications framework on your website that can be used for running
in-house of third party tools in the form of CG Widgets that extend your
website without you need to change anything in the code of your website.
4. Having the ability of dynamic state-oriented customization
of website presence.

<a href="http://www.content-glass.org" target="_blank">Sample Website</a>
<h3>State Oriented Sharing</h3>
State Oriented Sharing is a sharing paradigm in which content in the form of
widgets is shared in relation with defined state of objects and things - that
may include both: virtual, physical or even physiological objects and states.
For example a widget can be shared with peers-group as related with
specific URL. In this case the website is the "object" and the "url"
represent a state of the object. The same principle can be applied to the
physical world too, for example a widget can be shared as related with product
using the barcode as the state. A widget can be shared using location
properties as a state.
Content Glass platform provide multiple APIs for creating State Oriented
Sharing apps. In this case we are using the Web API to let you run your own CG
apps on your website or sharing-tools we provide for various
environments and cases.
<h3>Options for embedding the CG-Button</h3>
There are three main options for embedding CG-Button on our website:
1. Enabling the main CG-Button via settings page.
2. Using CG-Button widget
3. Embedding special HTML tag into yor posts or pages.
<pre> "&lt;div cg='{"type":"button", "label":"Content Glass", "draggable":true}'&gt;&lt;/div&gt;"
</pre>

<h3>Read more</h3>
The CG platform is developed by Rhizome Networks LTD and you can read
more about this platform by visiting <a href="http://www.contentglass.com">content-glass website</a>.

== Installation ==
Method 1:<br>
1. In your site click on Plugins.
2. Click "Add New"
3. In the search box write "CG Button" and press enter.
4. Click on the Install button in the CG Button plugin box.
5. Click "Ok" in th popup.
6. Click "Active plugin" link.


Method 2:<br>
1. Download the plugin zip from the wordpress plugin repository.
2. Extract the zip content into `/wp-content/plugins/content-glass-button`
   directory (you may be needed to create this directory).
3. Activate the plugin through the 'Plugins' menu in WordPress.

After Installation is completed:<br>
Go into the CG Button Settings and enter your APP Id.<br>
*Note: for trial you can use the shared APP ID: "5513c675ag580".<br>
If you don't have one then you can register at the [registration](http://developers.contentglass.com/user/register) page.<br>
After you register go to [Application creation page](http://developers.contentglass.com/admin/structure/entity-type/application/application/add?destination=my_applications)
and create a new application.

For more information you can enter [developers site](http://developers.contentglass.com/).<br>
For any questions you can send us email at: system@contentglass.com.
== Frequently Asked Questions ==

= How do i get an API_Key? =<br>
You enter the [registration](http://developers.contentglass.com/user/register) page and register.<br>
After registration go to [Developer page](http://developers.contentglass.com/my-developer-account-view) to see your
API Keys.

= How do i get an APP_ID? =<br>
You go to [Application creation page](http://developers.contentglass.com/admin/structure/entity-type/application/application/add?destination=my_applications)
and create a new application.

= How do i enable Button widget? =<br>
The button widget is enable already.   What you just need to do is to place the widget in one
of the areas in your site.  To do so go to Appearances/Widgets and drag CG Button box to the desired area.

== Screenshots ==
1. Content Glass button wordpress widgets.
2. Content Glass button floating button(can be any where on the screen).
3. Setting page.
4. Widgets page.
5. Review widget demo.

== Changelog ==

= 1.0.0 =
* Widget button addition.
* Floating button addition.
* Fix fatal error caused by file name: CG-Button-init.php

= 1.0.2 =
* Add API key security measure.
* Fix "Infinite" loading in media pages.

= 1.0.3 =
* Add setting link in the plugins page under the plugin row.
* Move the plugins settings to be under the settings sub-menu.
* Fix problem when plugin activate an error of #### unexpected char.
* Add "Rate this plugin" link.
* Fix plugin cleanup when deleted.

= 1.0.4 =
* Fix draggable button not be render on some browser.
* Add more explanations in the settings page.

= 1.0.5 =
* Fix authentication problem.

== Upgrade Notice ==
= 1.0.0 =
Add ability to add the button to the site.
= 1.0.1 =
Fix fatal error caused by file name: CG-Button-init.php
= 1.0.2 =
Add API key security measure.
Fix "Infinite" loading in media pages.
= 1.0.3 =
Add setting link in the plugins page under the plugin row.
Move the plugins settings to be under the settings sub-menu.
Fix problem when plugin activate an error of #### unexpected char.
Add "Rate this plugin" link.
Fix plugin cleanup when deleted.
= 1.0.4 =
Fix draggable button rendering on some browser.
Add more explanations in the settings page.
= 1.0.5 =
Fix authentication problem.