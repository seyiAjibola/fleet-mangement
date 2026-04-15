# Feature Guide

This document explains the main modules in simple terms.

## 1. Login And Access

- The app opens on the login page.
- After login, the user is sent to the admin area.
- Only authenticated users can access the admin area.
- The app supports two user roles:
  - `admin`
  - `staff`
- Admin users can access all admin modules.
- Staff users can access the operational modules but only see records they created.
- Staff users cannot access:
  - User management
  - Reports
- Both admin and staff users can access:
  - Compliance dashboard
  - Compliance notifications

## 2. Dashboard

The dashboard gives a quick operational summary.

It shows:

- Total users
- Total suppliers
- Total vehicles
- Total drivers
- Total bookings
- Pending bookings
- Available vehicles

It also shows charts for:

- Booking status as a pie chart
- Vehicle status as a bar chart

The date filter changes the booking-based numbers and chart data.

The layout uses:

- A larger aggregate card area on the left
- A smaller chart column on the right

Staff users see dashboard numbers based on records within their scope.

## 3. User Management

The user module is for internal staff accounts.

Available actions:

- Create user
- Edit user
- View user
- Delete user

Tracked fields:

- Name
- NIN
- Role
- Email
- Password

Filters:

- User Name
- NIN

## 4. Supplier Management

The supplier module tracks external car providers.

Available actions:

- Create supplier
- Edit supplier
- View supplier
- Delete supplier

Tracked fields:

- Company name
- Business type
- Contact person
- Contact number
- Email
- CAC No
- TIN
- City
- Address
- Supplier score
- Supplier tier
- Status

Supplier grading is now part of workflow logic:

- `supplier_score` is calculated automatically
- `supplier_tier` is assigned automatically from the calculated score and supplier profile data

Filters:

- Company name
- Contact person
- Contact number
- Number of cars
- Location or address
- CAC No
- TIN
- Status

### Supplier Score

Supplier score is calculated automatically and capped at `100`.

The current formula uses:

- Active status
- CAC No presence
- TIN presence
- Website presence
- Instagram presence
- Years in business
- Fleet size

Current rule:

- `+20` if supplier is `active`
- `+15` if `CAC No` exists
- `+15` if `TIN` exists
- `+10` if `website` exists
- `+5` if `instagram_page` exists
- years in business:
  - `+20` for `10+`
  - `+15` for `5+`
  - `+10` for `2+`
  - `+5` for `1+`
- fleet size:
  - `+15` for `10+` vehicles
  - `+10` for `5+`
  - `+8` for `3+`
  - `+5` for `1+`

### Supplier Tier

Supplier tier is now assigned automatically:

- `gold`
  - supplier is `active`
  - score is `80+`
  - `5+` years in business
  - both `CAC No` and `TIN` exist
  - supplier has at least `3` vehicles
- `silver`
  - supplier is `active`
  - score is `50+`
  - `2+` years in business
  - at least one compliance document exists: `CAC No` or `TIN`
- `bronze`
  - any supplier that does not meet `gold` or `silver`

Supplier score and tier are recalculated when:

- a supplier is created
- a supplier is edited
- a vehicle is added
- a vehicle is moved to another supplier
- a vehicle is deleted

### Supplier Detail View

The supplier detail page now shows:

- all vehicles under that supplier
- all drivers under that supplier
- links from vehicle names to vehicle detail pages
- links from driver names to driver detail pages
- links from driver rows to assigned vehicles

## 5. Vehicle Management

The vehicle module manages fleet inventory.

Available actions:

- Create vehicle
- Edit vehicle
- View vehicle
- Delete vehicle

Tracked fields:

- Supplier
- Vehicle type
- Make
- Model
- Year
- Color
- Plate number
- Passenger capacity
- Condition
- Fuel type
- Air condition
- Location
- Status

Current controlled values:

- Vehicle type: `SUV`, `SEDAN`, `TRUCK`, `VAN`
- Condition: `standard`, `average`, `excellent`
- Fuel type: `gas`, `diesel`
- Status: `available`, `unavailable`
- Year range: `2010` to `2027`

Filters:

- Vehicle type
- Vehicle make
- Vehicle model
- Vehicle condition
- Vehicle plate number
- Year
- Fuel type
- Vehicle color
- Status

### Vehicle Images

Vehicle images are managed on the vehicle detail page.

Supported actions:

- Upload multiple images
- Preview an image in a modal
- Set a primary image
- Delete an image

The first uploaded image becomes the primary image if the vehicle does not already have one.

### Vehicle Detail View

The vehicle detail page now also shows:

- assigned drivers
- links from driver names to driver detail pages
- a compliance section with tracked compliance items for that vehicle
- a form to add or edit compliance records
- attached compliance documents for each record

### Vehicle Status Actions

The vehicle list page now includes quick actions to:

- mark a vehicle as `available`
- mark a vehicle as `unavailable`

These actions ask for confirmation before updating the record.

## 6. Driver Management

The driver module manages driver records and assignments.

Available actions:

- Create driver
- Edit driver
- View driver
- Delete driver

Tracked fields:

- Supplier
- Assigned vehicle
- Driver name
- Phone number
- License number
- Years of experience
- Languages
- Professional experience
- Status

### Driver Status Actions

The driver list page now includes quick actions to:

- mark a driver as `active`
- mark a driver as `inactive`

These actions ask for confirmation before updating the record.

### Driver Detail View

The driver detail page now also shows:

- assigned vehicle information
- a compliance section with tracked compliance items for that driver
- a form to add or edit compliance records
- attached compliance documents for each record

## 7. Compliance Management

The compliance module replaces hardcoded expiry fields with a generic tracking system.

Tracked building blocks:

- `compliance_types`
- `compliance_records`
- `compliance_documents`
- `compliance_notification_logs`
- `compliance_audit_logs`
- framework `notifications`

Supported entity types:

- `vehicle`
- `driver`
- `supplier`

Current compliance capabilities:

- create and edit compliance records from vehicle and driver detail pages
- assign compliance types by entity type
- track document number, issued date, expiry date, status, and creator
- upload supporting documents such as PDF and image files
- calculate status automatically using compliance type rules
- refresh status during dashboard checks and page loads

Current status values:

- `valid`
- `expiring`
- `expired`
- `non_compliant`

### Compliance Status Engine

Status is calculated automatically from:

- expiry date
- notification days before
- grace period days
- whether expiry is required for the compliance type

Current behavior:

- `valid`
  - before the notification window
- `expiring`
  - inside the notification window up to expiry
- `expired`
  - after expiry but still inside grace period
- `non_compliant`
  - after expiry plus grace period

### Compliance Dashboard

The compliance dashboard supports:

- filtering by entity type
- filtering by status
- searching by entity, type, or document number
- exception-only filtering
- summary widgets for valid, expiring, expired, and non-compliant counts
- CSV export for compliance summary
- CSV export for compliance exceptions

### Compliance Notifications

The compliance notification engine runs from the scheduled compliance check.

Current recipients:

- admin users
- the user who created the compliance record

Current channels:

- in-app notifications
- email when the recipient has an email address

Current triggers:

- `expiring`
- `expired`
- `non_compliant`

Anti-spam protection uses:

- `last_notified_at` on the compliance record
- `compliance_notification_logs` with a unique context key per recipient and status snapshot

### Compliance Documents

Each compliance record can store supporting documents.

Current document support:

- multiple uploads
- PDF files
- image files
- remove existing attachments
- open uploaded files from the compliance form

### Compliance Audit Trail

Compliance changes are now written to an audit log.

Current audited actions:

- compliance record created
- compliance record updated
- supporting document added
- supporting document removed
- status changed by the scheduled compliance check

The compliance form shows recent activity history with:

- action summary
- actor name or `System`
- timestamp
- indication when the change came from the scheduled compliance check

## 8. Booking Management

The booking module manages customer ride bookings.

Available actions:

- Create booking
- Edit booking
- View booking
- Delete booking

Tracked fields:

- Customer name
- Customer phone
- Pickup location
- Dropoff location
- Pickup time
- Dropoff time
- Vehicle category
- Booking source
- Assigned vehicle
- Assigned driver
- Status

Filters:

- Search text
- Status
- Start date
- End date

Status actions available from the list:

- Confirm booking
- Reject booking
- Cancel booking

Destructive or negative actions now ask for confirmation before execution.

### Booking Conflict Prevention

The app prevents assigning the same vehicle or driver to overlapping bookings.

This check happens:

- In the UI, by reducing available options
- In the backend, during save

That means stale tabs or crafted requests cannot bypass the conflict rule.

## 9. Reports

The reports module gives an analytics and export center.

Charts included:

- Bookings by source
- Vehicles by category
- Suppliers by tier

Exports included:

- Booking sources CSV
- Vehicle categories CSV
- Supplier tiers CSV
- Supplier fleet overview CSV
- Vehicle driver assignments CSV
- Staff overview CSV
- Selected staff report CSV
- Selected supplier report CSV
- Compliance summary CSV
- Compliance exceptions CSV
- Supplier compliance ranking CSV

Additional report sections:

- Supplier fleet overview
- Vehicle driver assignments
- Supplier specific report
- Staff overview
- Staff specific report
- Compliance summary
- Compliance exceptions
- Supplier compliance ranking

### Supplier Specific Report

The supplier specific report supports:

- selecting a supplier from a live-updating dropdown
- viewing supplier summary information
- viewing the supplier's cars
- viewing assigned drivers under that supplier
- viewing supplier compliance score and compliance counts
- viewing all compliance records tied to the supplier, its vehicles, and its drivers
- exporting supplier summary, vehicle rows, and driver rows to CSV

### Compliance Reports

The reports module now also includes compliance reporting.

Available compliance reporting views:

- total compliance summary
- compliance exception table
- supplier compliance ranking

Supplier compliance ranking is calculated from:

- supplier compliance records
- vehicle compliance records under that supplier
- driver compliance records under that supplier

The current ranking score gives more weight to:

- valid records
- expiring records

And penalizes:

- expired records
- non-compliant records

### Staff Report

The staff report supports:

- overview of all staff users
- counts of suppliers, vehicles, drivers, and bookings created by each staff user
- confirmed booking counts
- selecting one staff user for a detailed report
- exporting the selected staff report to CSV

The detailed staff report includes:

- suppliers created by that staff user
- vehicles created by that staff user
- drivers created by that staff user
- bookings created by that staff user

Date filters affect the booking-based parts of staff reports.

## 10. Profile Management

Authenticated users can:

- Update profile details
- Change password
- Delete their own account

## 11. Admin UI

The admin UI now includes shared usability improvements:

- mobile slide-in sidebar navigation
- responsive tables and toolbars
- icon-based action buttons across major list and detail pages
- themed icon colors for neutral, positive, and destructive actions
- status badges with different colors by status
- stronger table headers with a more visible background
- improved sidebar, top header, and filter input styling
- topbar compliance notification inbox
- full notifications page with unread/read states

## 12. Testing

The app includes automated tests for:

- Authentication
- Registration
- Password flows
- Profile management
- Booking conflict prevention
- Admin route access by role
- Compliance record creation and status calculation
- Compliance document uploads
- Compliance notifications and anti-spam logging
- Compliance notification inbox read actions
- Compliance reports and supplier compliance ranking
- Compliance audit logging for record and status changes
