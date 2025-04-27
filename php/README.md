# Event-Sourced Hotel Reservation System

This project serves as an educational example of an event-sourced system for hotel reservations, with a focus on domain-driven design principles and ubiquitous language.

## Project Overview

This hotel reservation system demonstrates the implementation of event sourcing in a real-world domain. The system is built using PHP 8 and structured around vertical slices (bounded contexts), each representing a specific domain area of the hotel business.

### What is Event Sourcing?

Event sourcing is an architectural pattern where:
- The state of the application is determined by a sequence of events
- Each event represents a fact that happened in the domain
- The system's state is derived by replaying these events
- Events are immutable and represent the history of the system

## Domain Model and Ubiquitous Language

The system is organized around the following bounded contexts, each with its own domain language:

### Reservation

The core process of booking a room for a guest within a specific timeframe.

**Domain Concepts:**
- **Reservation** - An agreement to reserve a room for a specific period
- **Check-in** - The process when a guest arrives and takes possession of the room
- **Check-out** - The process when a guest leaves and returns possession of the room
- **No-show** - When a guest fails to arrive for a confirmed reservation

**Events:**
- `ReservationCreated` - A new reservation has been made
- `ReservationConfirmed` - A reservation has been confirmed
- `ReservationModified` - Details of a reservation have been changed
- `ReservationCancelled` - A reservation has been cancelled
- `CheckInPerformed` - A guest has checked in
- `CheckOutPerformed` - A guest has checked out
- `NoShowRecorded` - A guest failed to arrive for their reservation

### Room

Management of rooms, their categories, pricing, and availability.

**Domain Concepts:**
- **Room** - A physical space that can be reserved
- **Room Category** - Classification of rooms (e.g., Standard, Deluxe, Suite)
- **Room Status** - Current state of a room (e.g., available, occupied, maintenance)

**Events:**
- `RoomAdded` - A new room has been added to the hotel inventory
- `RoomStatusChanged` - The status of a room has changed
- `RoomCategoryChanged` - A room has been reassigned to a different category
- `RoomPriceUpdated` - The pricing for a room has been updated

### Guest

Management of guest profiles and preferences.

**Domain Concepts:**
- **Guest** - A person who stays or will stay at the hotel
- **Guest Preferences** - Recorded preferences to enhance guest experience

**Events:**
- `GuestRegistered` - A new guest has been added to the system
- `GuestProfileUpdated` - Guest's personal information has been updated
- `GuestPreferencesChanged` - Guest's stay preferences have been modified

### Billing

Handling of payments and invoices related to reservations.

**Domain Concepts:**
- **Invoice** - A document requesting payment for a reservation
- **Payment** - Money received for a reservation
- **Refund** - Money returned to a guest

**Events:**
- `InvoiceGenerated` - An invoice has been created for a reservation
- `PaymentReceived` - A payment has been made by a guest
- `PaymentRefunded` - Money has been returned to a guest

### Housekeeping

Management of room cleaning and maintenance.

**Domain Concepts:**
- **Cleaning Request** - Request to prepare a room for new guests
- **Maintenance Request** - Request to fix an issue in a room

**Events:**
- `RoomCleaningRequested` - A request to clean a room has been created
- `RoomCleaningCompleted` - A room has been cleaned and is ready
- `MaintenanceRequested` - A room needs maintenance or repair

## System Architecture

The project follows these architectural principles:

1. **Vertical Slices / Bounded Contexts**: Each domain area is encapsulated in its own module
2. **Command-Query Responsibility Segregation (CQRS)**: Separate paths for commands (write) and queries (read)
3. **Event Sourcing**: All state changes are captured as a sequence of events
4. **Rich Domain Model**: Domain logic encapsulated in aggregates with enforced invariants

### Folder Structure

```
src/
├── Reservation/
│   ├── Command/
│   ├── Query/
│   ├── ReservationAggregate.php
│   ├── ReservationCreated.php
│   ├── ReservationConfirmed.php
│   └── ...
├── Room/
│   ├── Command/
│   ├── Query/
│   ├── RoomAggregate.php
│   ├── RoomAdded.php
│   └── ...
├── Guest/
│   ├── Command/
│   ├── Query/
│   └── ...
├── Billing/
│   ├── Command/
│   ├── Query/
│   └── ...
└── Housekeeping/
    ├── Command/
    ├── Query/
    └── ...
```

## Educational Purpose

This project serves as a practical example for understanding:

1. **Event Sourcing**: How to implement an event-sourced system
2. **Domain-Driven Design**: Applying DDD principles to a realistic domain
3. **Ubiquitous Language**: Building a shared language between technical and domain experts
4. **Bounded Contexts**: Organizing complex domains into manageable parts
5. **CQRS**: Separating read and write models for better scalability and complexity management

## Getting Started

1. Clone the repository
2. Run `composer install`
3. Explore the different bounded contexts and their event models

## Contributing

This is an educational project. Contributions that enhance its educational value or extend its functionality in ways aligned with the architectural principles are welcome.

## License

This project is licensed under the MIT License - see the LICENSE file for details.