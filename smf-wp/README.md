# Wordpress Bridge
[![MIT license](http://img.shields.io/badge/license-MIT-009999.svg)](http://opensource.org/licenses/MIT)
[![GitHub issues](https://img.shields.io/github/issues/live627/smf-wp.svg)](https://github.com/live627/smf-wp/issues)
[![Latest Version](https://img.shields.io/github/release/live627/smf-wp.svg)](https://github.com/live627/smf-wp/releases)
[![Total Downloads](https://img.shields.io/github/downloads/live627/smf-wp/total.svg)](https://github.com/live627/smf-wp/releases)
[![Support](https://supporter.60devs.com/api/b/axlsj1o8o0amepfrr5eqlcjza)](https://supporter.60devs.com/give/axlsj1o8o0amepfrr5eqlcjza)

## Introduction:
Bridge logins between WordPress and SMF.

- Setup this mod at Administration Center » Wordpress Bridge.
- Logins are synchronized with your WordPress site once users log into the forum.
  - The inccluded WP plugin will redirect users to the forum if they try to register or login to the blog site.
    - The single file `smf-wp-auth.php` goes into WP's plugins directory
    - It should be activated within the SMF site.
 - The bridge will automatically create new users to try to keep everything in sync.

Requires PHP 7.4 or newer to run

Ask about any questions and please donate if you can.
