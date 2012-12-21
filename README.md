CakePHP-Cloudprint
==================

A CakePHP Plugin for Google's [Cloud Print Service][]

Cloudprint is based on Dean Sofer's (ProLoser) [Apis Plugin][], which is itself based on work by Neil Crookes.
It also uses Plank's [Pdfize][] plugin, more as a proof-of-concept than an actual implementation.

Do note that Google's OAuth2 requires HTTPS and will refuse HTTP connections.

Acceptable MIME types are:
application/pdf
image/jpeg
image/png

Configuration
-------------

`Config/database.php`
`'login' => ''` should contain your client ID obtained from [Google's APIs console][].
`'password' => ''` should contain the client secret.
`'callback' => ''` should contain the callback address registered with Google's APIs console.

Do note the plugin includes both a routes file and a bootstrap file, make sure those options are enabled when calling CakePlugin::load

Usage
-----

Clone the Apis plugin into your plugin directory, and also do similarly for this plugin. You can also clone Plank's Pdfize plugin, or remove the dependency from the Jobs controller if you only want to print images.
It should be noted that Ceeram's CakePdf plugin is much better but has not been integrated yet.

Import the Apis schema into your database. A more thorough integration with your User model might be a good idea. Authorization should be triggered by visiting any OAuth-protected page; prerequisite to this is (obviously) having a Google account and a printer registered with GCP.

At the moment, the best way to print something is to call requestAction. This will be rewritten for Cakephp 2.x to use Events.

It should go without saying, but the local php timezone must be set correctly or the plugin will not calculate token expirations correctly. In Google's Oauth2 implementation, tokens are only good for one hour, so that doesn't leave much margin for error with timestamps.
A caveat on performance: this plugin is pretty slow. For the most part this is not really avoidable. In order to submit a print job, the plugin needs to do 1-3 database calls and 2-3 requests to google's servers. This can be expected to take a while, and longer if you add in the step of rendering a PDF.
Some of the slow factor can be mitigated with a little integration, e.g. storing the name of a default printer with your user model. This is not likely to be a drop-in-and-forget-about-it implementation, but should be a functional base to extend as necessary.

Not Implemented
---------------
Views for anything and everything have been left to others' discretion. Printing long documents requires multi-part requests that I don't believe are handled correctly.

The plugin uses the first printer available that is not "print to google docs." Actually implementing a 'default printer' interface might be a terrific idea.

Tests
-----
The test fixture must be edited to contain a valid refresh token before the tests will work correctly. No, you can't print things using my account.

[apis plugin]: https://github.com/ProLoser/CakePHP-Api-Datasources
[google's apis console]: https://code.google.com/apis/console/
[pdfize]: https://github.com/plank/pdfize
[cloud print service]: https://developers.google.com/cloud-print/docs/appInterfaces

Errata
------
If you're having trouble with making calls to the Cloudprint Service, you can try using cUrl from the command line like so:
$ curl -v -H "X-CloudPrint-Proxy: api-prober" -H "Authorization: OAuth ya29.AHES6ZS8mUvZh1UzUE9dDkM_b2ZJTac6VTqhZTx62UWtzPTXKuG_3HQ" https://www.google.com/cloudprint/search

Or you can use google's Oauth Playground online:

<https://developers.google.com/oauthplayground/>