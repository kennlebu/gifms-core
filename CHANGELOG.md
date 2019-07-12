# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [1.3.beta.2] - 2019-07-12
### Added
- Searching supplier by tax pin in admin.

### Changed
- PM Journal only shows allocations belonging to the PM's PIDs.
- Mobile payments now log submission.
- Fixed repeated VAT/Income tax text on PV
- Withdrawal charges are now added onto the total of invoices and mobile payments
  where Mobile Money is the selected payment mode.

## [1.3.beta.1] - 2019-06-30
### Added
- Marking payments as paid.
- Recalling and canceling leave requests.
- Requesting bank signatories for MPESA.
- Unpaid invoices summary API.

### Changed
- Unused fields in API responses have been removed in some models to
  reduce the amount of data transmitted.
- Fixed the wrong payment amount paid to vendor being shown on the PV.
- Tax amounts are now whole numbers.
- Finance staff no longer see directors as line managers for their leave.
- Fixed Mobile Payment going to the wrong status on submission for
  accountant approval.
- Fixed mobile payments and their totals not showing in reports.

## [1.2.beta.3] - 2019-05-29
### Changed
- Mobile money process flow has been changed. Bank confirmation of the
  names is done before submission for approval.
- Leave request can be edited after approval. It's resesnt back to the
  line manager for approval.
- PMs can only see their projects in admin section for projects.

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
