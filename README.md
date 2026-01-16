# CookieGuard

Prevent cookie incidents by ensuring Magento never attempts to set non-essential cookies when the request is already near the cookie limit, regardless of third-party tag behaviour.


Design Principles


Never interfere with critical cookies:

session

form key

private content version

Skip only non-critical frontend cookies

Fail safe, not silent

Minimal overhead
