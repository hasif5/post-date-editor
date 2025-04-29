# Changelog

All notable changes to the "Post Date Editor" plugin will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.4.3] - 2023-08-10
### Security
- Fixed security issue with nonce validation
- Added proper unslashing and sanitization of nonce values
- Improved input validation and sanitation

## [1.4.2] - 2023-08-01
### Security
- Added proper nonce verification for form handling
- Fixed security issues with admin notice display
- Improved overall plugin security

### Changed
- Updated WordPress compatibility to 6.8
- Reduced plugin tags to comply with WordPress.org guidelines

## [1.4.1] - 2023-07-15
### Fixed
- Fixed issue with admin page showing "link expired" error
- Removed incorrect nonce verification on page load

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