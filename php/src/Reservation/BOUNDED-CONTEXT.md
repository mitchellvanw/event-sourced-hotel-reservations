# Bounded Context Canvas - Reservation

## Name
Reservation

## Description
Manages the hotel's reservation process, including bookings and stays. This context handles the lifecycle of a reservation from creation through to fulfillment as a stay, managing all transitions between states.

## Strategic Classification

### Domain
- **Core Domain**: This is a core domain as the primary business of a hotel is to manage reservations.

### Business Model
- **Revenue Generator**: Directly tied to the hotel's main revenue stream.

### Evolution
- **Mature**: The concept of hotel reservations is well-understood, though continually being refined.

## Domain Roles

### Key Domain Roles
- **Booking**: Represents a reservation agreement for a future stay
- **Stay**: Represents an actualized booking where guests have checked in
- **Guest**: The customer who makes the booking and/or stays at the hotel
- **Room**: The hotel accommodation being reserved

## Ubiquitous Language

### Key Terms
- **Booking**: An agreement to reserve one or more rooms for specified dates
- **Confirmation**: The act of securing a booking with payment information
- **Cancellation**: The act of terminating a booking before it's fulfilled
- **Modification**: A change to the booking details (dates, room type, etc.)
- **Stay**: The actualization of a booking when a guest checks in
- **Check-in**: The process of a guest arriving and taking occupancy of their room
- **Check-out**: The process of a guest vacating their room and completing their stay
- **No-show**: When a guest fails to arrive for a confirmed booking

## Business Decisions

### Key Business Rules
- Bookings require valid guest details and payment information
- Bookings must be confirmed before they can be fulfilled as stays
- Rooms can only be assigned to one booking at a time for specific dates
- Cancellations may incur fees based on hotel policy and how close to arrival date
- Early check-outs may be subject to the original booking's payment terms
- Stays can be extended if room availability allows

## Domain Events

### Key Events
- **BookingCreated**: A new booking has been made
- **BookingConfirmed**: A booking has been confirmed with payment details
- **BookingModified**: Details of a booking have been changed
- **BookingCancelled**: A booking has been cancelled
- **BookingFulfilled**: A booking has been converted to a stay (check-in)
- **StayExtended**: The duration of a stay has been increased
- **EarlyCheckOutRequested**: A guest wants to leave before their scheduled departure
- **CheckOutPerformed**: A guest has completed their stay and vacated the room
- **NoShowRecorded**: A guest failed to arrive for their booking

## Commands

### Key Commands
- **CreateBooking**: Initialize a new booking in the system
- **ConfirmBooking**: Secure a booking with payment information
- **ModifyBooking**: Change booking details (dates, room type, etc.)
- **CancelBooking**: Terminate a booking before check-in
- **FulfillBooking**: Convert a booking to a stay (check-in process)
- **ExtendStay**: Increase the duration of a current stay
- **RequestEarlyCheckOut**: Process an early departure
- **PerformCheckOut**: Complete a stay and make room available
- **RecordNoShow**: Mark a booking as no-show

## Queries

### Key Queries
- **GetBookingById**: Retrieve details of a specific booking
- **GetBookingsByGuest**: Find all bookings for a specific guest
- **GetStayById**: Retrieve details of a specific stay
- **GetActiveStays**: List all current guests in the hotel
- **GetUpcomingArrivals**: List expected check-ins for a specific date
- **GetUpcomingDepartures**: List expected check-outs for a specific date
- **GetAvailableRooms**: Find rooms available for specific dates
- **GetBookingHistory**: Retrieve history of a booking including all changes

## Dependencies

### Upstream
- **Accommodation**: Provides information about rooms and their availability
- **Guest**: Provides guest profile information

### Downstream
- **Finance**: Consumes booking events to generate invoices and process payments
- **Maintenance**: Uses stay information to schedule room cleaning and maintenance

## Technical Characteristics

### Defining Characteristics
- **Event-sourced**: Uses event sourcing to maintain the complete history of all reservations
- **Aggregate-based**: Models bookings and stays as separate aggregates with clear boundaries
- **Strong consistency**: Ensures booking integrity with validation rules

## Team

### Roles
- Domain experts from front desk and reservations departments
- Software engineers specialized in booking systems
- UX designers focused on the booking experience