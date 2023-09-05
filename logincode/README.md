# Login Code
Verify new devices with a short code when logging in

## Reqiurements
SMF 2.0.x
PHP 7.1+

## Overview
New devices are treated as untrusted with this mod enabled until verified. A short passcode will be emailed to you, and once yoou enter the code, your device will be trusted and any subseqsuent logins on that device will no longer be interrupted.

The six-character alphanumeric code excludes characters that lookalike such as `1` (one) and `l` (lowercase L), `0` (zero) and `o` (lowercase O), etc.

You should **let your users know** that you've installed this mod so they don't freak out and cry foul or cause a ruckus.

# Settings
Settings are found in the admin panel: Administration Center » Configuration » Modifications (or `index.php?action=admin;area=modsettings`).

## License
[Zero-Clause BSD](http://opensource.org/licenses/0BSD)
