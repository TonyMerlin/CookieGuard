# CookieGuard

Prevent cookie incidents by ensuring Magento never attempts to set non-essential cookies when the request is already near the cookie limit, regardless of third-party tag behaviour.


Magento’s message “Unable to send the cookie. Maximum number of cookies would be exceeded.” and this doesn't tell us which setcookie() call was blocked. 


Design Principles


Never interfere with critical cookies:

session

form key

private content version

Skip only non-critical frontend cookies

Fail safe, not silent

Minimal overhead
-------------------------

#Cookie Classification


Always Allowed (Critical)

PHPSESSID

form_key

private_content_version

mage-cache-sessid

admin (backend only)

Skipped When Near Limit (Non-Critical)

mage-messages

frontend customer-data storage cookies

any third-party cookie set via Magento APIs

-------------

#Install

bin/magento module:enable Merlin_CookieGuard 
bin/magento setup:upgrade
bin/magento cache:flush
