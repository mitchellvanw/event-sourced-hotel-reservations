# Bounded Context Canvas - Guest

## Name
Guest

## Description
Manages hotel guest information, profiles, preferences, and loyalty programs. This context serves as the central repository for all guest-related data and interactions across the hotel system.

## Strategic Classification

### Domain
- **Core Domain**: Guest information is central to hotel operations and personalized service.

### Business Model
- **Customer Relation**: Directly supports the hotel's ability to maintain guest relationships and provide personalized service.

### Evolution
- **Mature with Innovation**: While guest management is a mature concept, there's continuous innovation in loyalty programs and personalization.

## Domain Roles

### Key Domain Roles
- **Guest**: A person who stays or has stayed at the hotel
- **LoyaltyAccount**: Represents a guest's participation in the hotel's loyalty program
- **GuestPreference**: Specific preferences that enhance a guest's stay
- **AccountManager**: Staff responsible for managing guest accounts and profiles

## Ubiquitous Language

### Key Terms
- **Profile**: The collection of information about a guest
- **Loyalty Tier**: A status level within the loyalty program (e.g., Silver, Gold, Platinum)
- **Points**: Units earned and redeemed through the loyalty program
- **Preference**: A guest's service or amenity requests that enhance their experience
- **Stay History**: Record of a guest's previous visits to the hotel
- **Member**: A guest enrolled in the loyalty program
- **Merge**: Combining duplicate guest profiles into a single unified record

## Business Decisions

### Key Business Rules
- Each guest has a unique identifier across all hotel systems
- Guests can opt-in to marketing communications and special offers
- Loyalty program members earn points based on qualified stays and spending
- Tier levels are determined by points earned within a calendar year or rolling period
- Points have expiration dates unless the guest maintains a minimum tier level
- Duplicate profiles should be identified and merged with guest consent
- Privacy regulations (like GDPR) must be respected for all guest data

## Domain Events

### Key Events
- **GuestRegistered**: A new guest has been added to the system
- **GuestProfileUpdated**: A guest's personal information has been modified
- **GuestPreferencesChanged**: A guest's preferences have been updated
- **GuestAccountDeactivated**: A guest account has been made inactive
- **LoyaltyAccountCreated**: A guest has enrolled in the loyalty program
- **PointsEarned**: A guest has accumulated loyalty points
- **PointsRedeemed**: A guest has used loyalty points for benefits or services
- **LoyaltyTierChanged**: A guest's status level has changed
- **ProfilesMerged**: Two or more guest profiles have been combined

## Commands

### Key Commands
- **RegisterGuest**: Create a new guest profile in the system
- **UpdateGuestProfile**: Modify a guest's personal information
- **ChangeGuestPreferences**: Update a guest's service and amenity preferences
- **DeactivateGuestAccount**: Make a guest account inactive
- **CreateLoyaltyAccount**: Enroll a guest in the loyalty program
- **EarnPoints**: Add loyalty points to a guest's account
- **RedeemPoints**: Use loyalty points for benefits or services
- **ChangeLoyaltyTier**: Update a guest's status level
- **MergeProfiles**: Combine multiple guest profiles

## Queries

### Key Queries
- **GetGuestById**: Retrieve a guest's profile by their unique identifier
- **SearchGuests**: Find guests matching specific criteria
- **GetLoyaltyAccountDetails**: Retrieve information about a guest's loyalty status
- **GetGuestStayHistory**: List a guest's previous stays at the hotel
- **GetGuestPreferences**: Retrieve a guest's service and amenity preferences
- **GetPointsBalance**: Check a guest's current loyalty points
- **GetPointsHistory**: View a record of points earned and redeemed
- **FindPotentialDuplicates**: Identify possible duplicate guest profiles

## Dependencies

### Upstream
- **Identity/Authentication**: Provides authentication and authorization services

### Downstream
- **Reservation**: Consumes guest data for booking processes
- **Finance**: Uses guest information for billing and payment
- **Maintenance**: Accesses guest preferences for room preparation

## Technical Characteristics

### Defining Characteristics
- **Event-sourced**: Maintains complete history of guest profile changes
- **Privacy-focused**: Designed with data protection regulations in mind
- **Scalable**: Handles large volumes of guest data with efficient retrieval

## Team

### Roles
- Customer relationship management specialists
- Data privacy officers
- Software engineers focused on customer data systems
- UX designers specializing in profile management interfaces