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

The system is organized around the following bounded contexts, each with its own domain language. Each bounded context has a detailed description in its `BOUNDED-CONTEXT.md` file.

### Reservation

The core process of booking a room for a guest within a specific timeframe.

**Key Aggregates:**
- **Booking** - Represents a reservation agreement for a future stay
- **Stay** - Represents an actualized booking when guests have checked in

### Guest

Management of guest profiles, preferences, and loyalty programs.

**Key Aggregates:**
- **Guest** - A person who stays or has stayed at the hotel
- **LoyaltyAccount** - Represents a guest's participation in the hotel's loyalty program

### Finance (previously Billing)

Handling of payments, invoices, and financial operations related to bookings.

**Key Aggregates:**
- **Invoice** - A bill issued to a guest for services rendered
- **Payment** - Money received from a guest
- **PaymentPlan** - An arrangement for installment payments

### Maintenance (previously Housekeeping)

Management of room cleaning, repairs, inspections, and supply management.

**Key Aggregates:**
- **CleaningTask** - Represents room cleaning work to be performed
- **MaintenanceTask** - Represents repair or maintenance work
- **InspectionTask** - Represents quality verification of rooms
- **SupplyRequest** - Represents a request for maintenance supplies

### Accommodation (previously Room)

Management of rooms, their categories, features, pricing, and availability.

**Key Aggregates:**
- **Room** - An individual accommodation unit
- **RoomType** - A category of rooms with similar characteristics
- **SeasonalRate** - Time-specific pricing for room types
- **Discount** - A price reduction applied to room types

## System Architecture

The project follows these architectural principles:

1. **Vertical Slices / Bounded Contexts**: Each domain area is encapsulated in its own module
2. **Command-Query Responsibility Segregation (CQRS)**: Separate paths for commands (write) and queries (read)
3. **Event Sourcing**: All state changes are captured as a sequence of events
4. **Rich Domain Model**: Domain logic encapsulated in aggregates with enforced invariants

### Architecture Documentation

This project uses the C4 model to document the architecture. The architecture is defined in the `workspace.dsl` file using Structurizr DSL.

The C4 model provides multiple levels of abstraction:
- **Context**: Shows the hotel system and its interactions with users and external systems
- **Container**: Displays the bounded contexts and key infrastructure components
- **Component**: Details the internal structure of each bounded context
- **Dynamic Views**: Illustrates key processes like booking and check-in flows

#### Visualizing the Architecture

For your convenience, we've included a helper script to visualize the architecture diagrams. Simply run:

```bash
# Make the script executable if needed
chmod +x view-architecture.sh

# Run the script
./view-architecture.sh
```

This script offers two options:
1. Generate static diagram images you can view in any image viewer
2. Start an interactive web viewer to explore the architecture

Alternatively, you can use these approaches manually:

##### Option 1: Structurizr CLI (Recommended)

1. Install the Structurizr CLI:

```bash
# If you have Docker installed:
docker pull structurizr/cli:latest

# Or download the Java JAR file:
# https://github.com/structurizr/cli/releases
```

2. Generate diagrams from the DSL file:

```bash
# Using Docker:
docker run --rm -v $(pwd):/usr/local/structurizr structurizr/cli:latest export -workspace /usr/local/structurizr/workspace.dsl -format plantuml -output /usr/local/structurizr/diagrams

# Or with the JAR file:
java -jar structurizr-cli.jar export -workspace workspace.dsl -format plantuml -output diagrams
```

3. This will create diagram files in the `diagrams` directory that you can open with any image viewer.

##### Option 2: Structurizr Lite (Local Web Interface)

1. Run Structurizr Lite using Docker:

```bash
docker run -it --rm -p 8080:8080 -v $(pwd):/usr/local/structurizr structurizr/lite
```

2. Open your browser at http://localhost:8080 to view and interact with the diagrams

##### Option 3: Structurizr Web Service

1. Sign up for a free account on [Structurizr.com](https://structurizr.com)

2. Create a new workspace and upload your DSL file

3. View and edit diagrams in the web browser

##### Option 4: PlantUML (Alternative Approach)

1. Install PlantUML (https://plantuml.com/starting)

2. Convert the Structurizr DSL to PlantUML using the Structurizr CLI

3. Render the PlantUML diagrams using your preferred PlantUML viewer

### Folder Structure

```
src/
├── Reservation/
│   ├── Command/
│   ├── Query/
│   ├── DomainEvent/
│   ├── Booking.php
│   ├── Stay.php
│   └── ...
├── Guest/
│   ├── Command/
│   ├── Query/
│   ├── DomainEvent/
│   ├── Guest.php
│   ├── LoyaltyAccount.php
│   └── ...
├── Finance/
│   ├── Command/
│   ├── Query/
│   ├── DomainEvent/
│   ├── Invoice.php
│   ├── PaymentPlan.php
│   └── ...
├── Maintenance/
│   ├── Command/
│   ├── Query/
│   ├── DomainEvent/
│   ├── CleaningTask.php
│   ├── MaintenanceTask.php
│   └── ...
└── Accommodation/
    ├── Command/
    ├── Query/
    ├── DomainEvent/
    ├── Room.php
    ├── SeasonalRate.php
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

This project is licensed under the MIT License - see the LICENSE file for details