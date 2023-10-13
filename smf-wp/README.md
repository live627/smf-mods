# Wordpress Bridge
## Introduction:
Bridge logins between WordPress and SMF.

- Setup this mod at Administration Center » Wordpress Bridge.
- Logins are synchronized with your WordPress site once users log into the forum.
  - The included WP plugin will redirect users to the forum if they try to register or login to the blog site.
    - The single file `smf-wp-auth.php` goes into WP's plugins directory
    - It should be activated within the SMF site.
 - The bridge will automatically create new users to try to keep everything in sync.

Requires PHP 7.4 or newer to run
