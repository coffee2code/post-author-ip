# Changelog

## _(in-progress)_
* New: Enable plugin functionality for all public post types by default
    * New: Add `get_post_types()` for retrieving post types
    * New: Add filter `c2c_stealth_publish_post_types` to filter post types
* New: Add `is_protected_meta()` to protect the meta key from being exposed as a custom field
* New: Improve configurability and accessibility of meta key name
  * New: Add `get_meta_key_name()` as getter for meta_key name
  * New: Add filter `c2c_post_author_ip_meta_key` for customizing meta key name
* New: Add `rest_pre_insert()` to add meta key as first-class object property prior to REST-initiated update
* New: Add HTML5 compliance by omitting `type` attribute for `style` tag when the theme supports 'html5'
* Change: Reduce width of 'Author IP' column
* Change: Remove duplicate hook registration
* Change: Note compatibility through WP 5.4+
* Change: Drop compatibility for version of WP older than 4.9
* Change: Tweak formatting of CSS styles
* Change: Update links to coffee2code.com to be HTTPS
* Unit tests:
    * New: Add tests for `add_admin_css()`, `admin_css()`, `add_post_column()`, `enqueue_block_editor_assets()`, `include_column()`, `register_meta()`, `show_post_author_ip()`
    * New: Add test for default hooks
    * Change: Use `get_meta_key_name()` to set default meta key used by tests
    * Change: Remove unnecessary unregistering of hooks in `tearDown()`
    * Change: Use HTTPS for link to WP SVN repository in bin script for configuring unit tests

## 1.2.1 _(2020-01-06)_
* New: Unit tests: Add test to verify plugin hooks `plugins_loaded` action to initialize itself
* Change: Note compatibility through WP 5.3+
* Change: Update JS dependencies
* Change: Update copyright date (2020)

## 1.2 _(2019-06-21)_
* New: Add support for new block editor (aka Gutenberg)
* New: Add CHANGELOG.md file and move all but most recent changelog entries into it
* New: Add .gitignore file
* Change: Update `register_meta()` with a proper auth_callback, `register_post_meta()` when possible, initialize on `init`
* Unit tests:
    * Change: Update unit test install script and bootstrap to use latest WP unit test repo
    * Fix: Fix unit tests related to post meta
* Change: Note compatibility through WP 5.2+
* Change: Add link to plugin's page in Plugin Directory to README.md
* Change: Split paragraph in README.md's "Support" section into two
* Fix: Correct typo in GitHub URL

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
