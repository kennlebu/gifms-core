# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [1.2.beta.2] - 2019-05-09

### Changed
- Only approved LPOs can be tagged to invoices
- Fixed user that cancels an LPO not being saved

### Removed
- Remove unique db requirement for emails. It was messing with validation
  on soft deleted rows.

### Added
- Supplier transactions when viewing a supplier.

## [1.2.beta.1] - 2019-04-23

### Added
- PM Journal and filters to get reports by programs, objectives or projects.

### Changed
- Duplicate voucher numbers won't return a result in the audit search.
- Invoices and Deliveries will only show approved LPOs for attachment.

### Removed
- Unimplemented functions in Mobile Payment Allocation.

## [1.2.beta.1] - 2019-04-15
### Added
- This CHANGELOG file to hopefully serve as an evolving record of the
  changes to this project.
- Good examples and basic guidelines, including proper date formatting.
- Counter-examples: "What makes unicorns cry?"
