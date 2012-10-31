CakePHP-Cloudprint
==================

A CakePHP Plugin for Google's Cloud Print Service

Cloudprint is based on Dean Sofer's (ProLoser) [Apis Plugin][], which is itself based on work by Neil Crookes. Neil contributed the [HttpSocketOauth][] extension to the core module HttpSocket, and I have largely removed this dependency as it did not support OAuth2.

Do note that Google's OAuth2 requires HTTPS and will refuse HTTP connections.

Configuration
-------------

`config/database.php`
`'login' => ''` should contain your client ID obtained from [Google's APIs console][].
`'password' => ''` should contain the client secret.

`config/cloudprint.php`
`$config['Apis']['Cloudprint']['callback']` this should be set to the callback URI registered with Google. It needs to match exactly.

`config/boostrap.php`
This should be appended to your main bootstrap file. Consider this a bug; it shouldn't be necessary.

`config/routes.php`
This should be appended to your main routes file. The callback route is necessary, but the authorization route is not.

Usage
-----

Clone the Apis plugin into your plugin directory, and also do similarly for this plugin. HttpSocketOauth is probably not necessary. Probably.

Import `tables.sql` into your database. A more thorough integration with your User model might be a good idea. Users must visit the `authorize()` function in order to allow access; prerequisite to this is (obviously) having a Google account and a printer registered with GCP.

At the moment, the best way to print something is to call requestAction. This will be rewritten for Cakephp 2.x to use Events. Consider this implementation incomplete, use at your own risk, and file a bug report for any issues you may find.

[apis plugin]: https://github.com/ProLoser/CakePHP-Api-Datasources
[httpsocketoauth]: https://github.com/ProLoser/http_socket_oauth
[google's apis console]: https://code.google.com/apis/console/
