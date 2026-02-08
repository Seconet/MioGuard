=== MioGuard for Contact Form 7 ===
Contributors: seconet
Tags: contact form 7, spam protection, honeypot, rate limit, cf7
Requires at least: 6.0
Tested up to: 6.9
Requires PHP: 7.4
Stable tag: 1.0.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Spam and bot protection for Contact Form 7 forms with honeypot and rate limiting.


== Description ==
**MioGuard for Contact Form 7** is a lightweight and easy-to-use plugin that protects Contact Form 7 forms from spam and bot submissions using an IP-based rate limit and a customizable honeypot field.

This plugin is **not affiliated with or endorsed by the Contact Form 7 team**.

**Key features:**
- Rate-limit form submissions by IP (configurable from the admin panel; default 5 minutes)
- Customizable honeypot field to catch bots
- Optional "Protected by CF7 Simple Guard" badge
- Fully localized (English + Italian included)
- No extra database tables, uses WordPress transients with automatic expiration
- Works with Contact Form 7 forms using Gutenberg or Classic editor
- Compatible with WordPress 6.0+ and PHP 7.4+

**How it works:**
- The plugin blocks repeat submissions from the same IP within the configured interval.
- The honeypot field prevents automated bots from sending forms.
- Optionally, you can show a small badge at the end of the form to indicate protection.

**Localization**
- English (`en_US`) and Italian (`it_IT`) included.
- Other languages can be added via `.po` / `.mo` files in `/languages`.

== Installation ==

1. Upload the `mioguard-for-contact-form-7` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to **Settings →  MioGuard for Contact Form 7 ** to configure:
   - Honeypot field name
   - Rate-limit interval (1–1440 minutes)
   - Enable/disable the badge

4. Add the honeypot field to your forms (example):
