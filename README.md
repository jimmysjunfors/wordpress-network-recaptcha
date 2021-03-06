# WordPress Network reCAPTCHA V2

Add reCAPTCHA V2 to WordPress site admin logins on network installations, with hostname check.

Please note that this plugin is not needed for regular network installations. You only need this plugin if you have unique domains mapped to you network sites for wp-admin, which may require other modifications to your WordPress installation and web server, that are not covered by this plugin.

## Important

When generating V2 keys, make sure to uncheck "Verify the origin of reCAPTCHA solutions", since this plugin verifies the origin by hostname check.

This makes it possible to use only one set of keys for an unlimited amount of mapped unique domains for the network sites.

## Installation

1) Enter V2 sitekey, privatekey and optionally secure IPs whom will not receive recaptcha check at login
2) Upload to plugins folder and network activate, or add it to mu-plugins folder
3) Done, now every login at sites wp-admin will need to complete recaptcha check

### Note

Script is loaded at wp_authenticate, before any connection to database is initiated etc. to reduce server load for login tries with invalid recaptchas, hence the own DIE message, because you can't use wordpress admin login page error without initiating a database connection.

### Fun fact

At a large network with 2000+ sites, just this simple check with the early DIE, reduced server load and offloaded other security measures with a huge chunk.