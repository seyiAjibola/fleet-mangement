# Feature Guide

This document explains the main modules in simple terms.

## 1. Login And Access

- The app opens on the login page.
- After login, the user is sent to the admin area.
- Only authenticated users can access the admin routes.

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

- Booking status
- Vehicle status

The date filter changes the booking-based numbers and chart data.

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

Filters:

- Company name
- Contact person
- Contact number
- Number of cars
- Location or address
- CAC No
- TIN
- Status

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

## 7. Booking Management

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

### Booking Conflict Prevention

The app prevents assigning the same vehicle or driver to overlapping bookings.

This check happens:

- In the UI, by reducing available options
- In the backend, during save

That means stale tabs or crafted requests cannot bypass the conflict rule.

## 8. Reports

The reports module gives a simple analytics view.

Charts included:

- Bookings by source
- Vehicles by category
- Suppliers by tier

Exports included:

- Booking sources CSV
- Vehicle categories CSV
- Supplier tiers CSV

## 9. Profile Management

Authenticated users can:

- Update profile details
- Change password
- Delete their own account

## 10. Testing

The app includes automated tests for:

- Authentication
- Registration
- Password flows
- Profile management
- Booking conflict prevention
