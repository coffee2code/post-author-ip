# Changelog

## _(in-progress)_
* New: Add CHANGELOG.md file and move all but most recent changelog entries into it
* Change: Note compatibility through WP 5.2+

## 1.1 _(2019-02-20)_
* New: Add new filter `c2c_post_author_ip_allowed` for per-post control of whether post author IP address should be saved
* New: Add 'Hooks' section to readme with full documentation and examples for hooks
* New: Add CHANGELOG.md and move all but most recent changelog entries into it
* New: Add inline documentation for hooks
* New: Add back-compatibility for PHPUnit older than 6
* New: Add unit test for `c2c_show_post_author_ip_column` filter
* Change: Register hooks on `plugins_loaded` at an earlier priority
* Change: Cast return value of `c2c_show_post_author_ip_column` hook as boolean
* Change: Make `include_column()` public instead of private
* Change: Merge `do_init()` into `init()`
* Change: Note compatibility through WP 5.1+
* Change: Update copyright date (2019)
* Change: Update License URI to be HTTPS

## 1.0 _(2018-01-24)_
* Initial public release
