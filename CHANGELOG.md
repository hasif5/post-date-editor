# Changelog

All notable changes to the Post Date Editor plugin will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.4.1] - 2023-04-30
### Fixed
- Issue with admin page showing "link expired" error
- Removed incorrect nonce verification on page load that was preventing the admin page from loading

## [1.4.0] - 2023-04-29
### Added
- Advanced search functionality with title-based search
- Post preview card with detailed information
- Live search results with AJAX implementation
- Select2 integration for enhanced search experience
- Improved UI with tabs for different search methods
- Post preview with featured image, excerpt, and meta information
- Better error handling and success notifications

### Changed
- Restructured admin interface for better user experience
- Enhanced date picker interface
- Improved AJAX response handling

### Security
- Added nonce verification for all AJAX requests
- Improved capability checking
- Enhanced input sanitization and validation
- Fixed timezone issues with date handling
- Added proper data unslashing

## [1.3.0] - 2023-03-15
### Added
- Support for page post type
- Basic error handling
- Admin notices for success/error feedback

## [1.2.0] - 2023-02-10
### Added
- AJAX-based post fetching
- Date validation
- GMT date conversion support

## [1.1.0] - 2023-01-20
### Added
- Basic CSS styling
- JavaScript enhancements
- Admin menu integration

## [1.0.0] - 2023-01-05
### Added
- Initial release
- Basic post date editing functionality
- Simple admin interface
- Post ID based search 