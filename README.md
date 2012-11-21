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

`config/database.php`
`'login' => ''` should contain your client ID obtained from [Google's APIs console][].
`'password' => ''` should contain the client secret.

`config/cloudprint.php`
`$config['Apis']['Cloudprint']['callback']` this should be set to the callback URI registered with Google. It needs to match exactly.

`config/routes.php`
This should be appended to your main routes file. The callback route is necessary, but the authorization route is not.

Usage
-----

Clone the Apis plugin into your plugin directory, and also do similarly for this plugin. You can/should also clone Plank's Pdfize plugin, or remove the dependency from the Jobs controller if you only want to print images.

Import `tables.sql` into your database. A more thorough integration with your User model might be a good idea. Users must visit the `authorize()` function in order to allow access; prerequisite to this is (obviously) having a Google account and a printer registered with GCP.

At the moment, the best way to print something is to call requestAction. This will be rewritten for Cakephp 2.x to use Events.

It should go without saying, but the local php timezone must be set correctly or the plugin will not calculate token expirations correctly. In Google's Oauth2 implementation, tokens are only good for one hour, so that doesn't leave much margin for error with timestamps.
A caveat on performance: this plugin is **SLOW!!** For the most part this is not really avoidable. In order to submit a print job, the plugin needs to do 1-3 database calls and 2-3 requests to google's servers. This can be expected to take a while, and longer if you add in the step of rendering a PDF.
Some of the slow factor can be mitigated with a little integration, e.g. storing the name of a default printer with your user model. This is not likely to be a drop-in-and-forget-about-it implementation.

Not Implemented
---------------
Views for anything and everything have been left to others' discretion. Error methods are also generally lacking. Printing long documents requires multi-part requests that I don't believe are handled correctly.

The plugin currently uses the *first* printer that is returned. This is entirely likely to be 'Print to Google Drive'. Changing this to the second printer might be a good idea. Actually implementing a 'default printer' interface might be a terrific idea.

Tests
-----
The tests for models/job and models/printer need to be edited with a valid access token before they will pass. The tests in models/token pass but produce error output that I don't care to fix.

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