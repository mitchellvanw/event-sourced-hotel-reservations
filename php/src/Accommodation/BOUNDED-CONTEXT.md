# Bounded Context Canvas - Accommodation

## Name
Accommodation

## Description
Manages the hotel's physical spaces, specifically rooms and their characteristics. This context tracks room types, features, availability, pricing, and status, serving as the authoritative source for accommodation information across the system.

## Strategic Classification

### Domain
- **Core Domain**: The hotel's physical accommodations are fundamental to its business.

### Business Model
- **Revenue Generator**: Room inventory directly contributes to the hotel's revenue capacity.

### Evolution
- **Mature with Innovation**: While room management is established, innovations in pricing strategies and feature offerings continue to evolve.

## Domain Roles

### Key Domain Roles
- **Room**: An individual accommodation unit that can be reserved and occupied
- **RoomType**: A category of rooms with similar characteristics and pricing
- **SeasonalRate**: Time-specific pricing for room types during particular periods
- **Discount**: A price reduction applied to room types during specified periods
- **Feature**: An amenity or characteristic that distinguishes rooms

## Ubiquitous Language

### Key Terms
- **Room**: A specific numbered accommodation unit in the hotel
- **Room Type**: A category of similar rooms (e.g., Standard, Deluxe, Suite)
- **Rate**: The price charged for a room, varying by type and season
- **Status**: The current condition of a room (available, occupied, maintenance, etc.)
- **Feature**: An amenity or characteristic of a room (e.g., ocean view, balcony, etc.)
- **Blocking**: Temporarily making a room unavailable for specified dates
- **Seasonal Rate**: Special pricing during specific calendar periods
- **Discount**: A reduced rate offered for marketing or loyalty purposes

## Business Decisions

### Key Business Rules
- Each room has one specific room type
- Room rates vary based on type, season, and special promotions
- Rooms can be blocked for maintenance or other purposes
- Room features affect desirability and pricing
- Discounts have specific validity periods and may have usage restrictions
- Seasonal rates override standard rates during their effective periods
- Room status changes must be tracked with reason codes
- Room inventory must be accurately maintained to prevent overbooking

## Domain Events

### Key Events
- **RoomCreated**: A new room has been added to the hotel inventory
- **RoomStatusChanged**: A room's availability status has been updated
- **RoomTypeChanged**: A room has been recategorized to a different type
- **RoomRateUpdated**: The standard price for a room has changed
- **RoomFeaturesUpdated**: A room's amenities or characteristics have changed
- **RoomBlocked**: A room has been made unavailable for a specific period
- **RoomBlockReleased**: A previously blocked room has been made available again
- **SeasonalRateCreated**: A special time-period rate has been defined
- **SeasonalRateUpdated**: An existing seasonal rate has been modified
- **RoomDiscountApplied**: A price reduction has been applied to a room type

## Commands

### Key Commands
- **CreateRoom**: Add a new room to the hotel inventory
- **ChangeRoomStatus**: Update a room's availability status
- **ChangeRoomType**: Recategorize a room to a different type
- **UpdateRoomRate**: Change the standard price for a room
- **UpdateRoomFeatures**: Modify a room's amenities or characteristics
- **BlockRoom**: Make a room unavailable for a specific period
- **ReleaseRoomBlock**: Return a blocked room to available status
- **CreateSeasonalRate**: Define a special time-period price
- **UpdateSeasonalRate**: Modify an existing seasonal rate
- **ApplyRoomDiscount**: Create a price reduction for a room type

## Queries

### Key Queries
- **GetRoomById**: Retrieve details of a specific room
- **GetAvailableRooms**: Find rooms that can be booked for specific dates
- **GetRoomsByType**: List all rooms of a particular category
- **GetRoomsByFeature**: Find rooms with specific amenities
- **GetRoomsByStatus**: List rooms with a particular availability status
- **GetBlockedRooms**: Show rooms that are temporarily unavailable
- **GetActiveSeasonalRates**: List current special pricing periods
- **GetAvailableRoomsWithFeatures**: Find bookable rooms with specific amenities
- **GetActiveDiscounts**: List current price reductions
- **GetRoomRateHistory**: View historical pricing for a room type

## Dependencies

### Upstream
- None (this is typically a source context)

### Downstream
- **Reservation**: Consumes room availability information for bookings
- **Finance**: Uses room rate information for invoicing
- **Maintenance**: References room data for cleaning and repair tasks

## Technical Characteristics

### Defining Characteristics
- **Event-sourced**: Maintains full history of room changes and pricing
- **Inventory-focused**: Optimized for accurate room availability tracking
- **Reference data manager**: Serves as the system of record for room information

## Team

### Roles
- Hotel operations managers
- Revenue management specialists
- Software engineers with inventory management expertise
- UX designers focused on property management interfaces