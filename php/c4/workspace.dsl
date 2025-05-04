workspace "Hotel Reservation System" "An event-sourced hotel reservation system" {

    model {
        guest = person "Hotel Guest" "A person who books and stays at the hotel"
        staff = person "Hotel Staff" "Hotel employees who manage reservations and operations"
        
        hotelSystem = softwareSystem "Hotel Reservation System" "Manages all aspects of hotel operations" {
            tags "Event Sourced"
            
            # Container level - C4 level 2
            webApp = container "Web Application" "Provides UI for hotel guests and staff" "PHP, Laravel" {
                tags "Web Application"
                
                # Component level - C4 level 3
                guestPortal = component "Guest Portal" "Allows guests to make and manage reservations" "Laravel Blade, Livewire"
                staffPortal = component "Staff Portal" "Allows staff to manage hotel operations" "Laravel Blade, Livewire"
                authComponent = component "Authentication" "Handles user authentication and authorization" "Laravel Auth"
            }
            
            apiApplication = container "API Application" "Handles API requests and business logic" "PHP, Laravel" {
                tags "API"
                
                # Component level - C4 level 3
                # Bounded Contexts as Components
                reservationContext = component "Reservation Context" "Manages bookings and stays" "PHP"
                accommodationContext = component "Accommodation Context" "Manages rooms and room types" "PHP"
                guestContext = component "Guest Context" "Manages guest accounts and preferences" "PHP"
                financeContext = component "Finance Context" "Manages invoices and payments" "PHP"
                maintenanceContext = component "Maintenance Context" "Manages cleaning and maintenance tasks" "PHP"
                
                # Cross-cutting components
                commandBus = component "Command Bus" "Routes commands to appropriate handlers" "PHP"
                eventBus = component "Event Bus" "Publishes domain events to subscribers" "PHP"
                eventStoreClient = component "Event Store Client" "Interfaces with the event store" "PHP"
                # Projector Components per Bounded Context
                accommodationProjector = component "Accommodation Projector" "Projects accommodation events to read models" "PHP"
                reservationProjector = component "Reservation Projector" "Projects reservation events to read models" "PHP"
                guestProjector = component "Guest Projector" "Projects guest events to read models" "PHP"
                financeProjector = component "Finance Projector" "Projects finance events to read models" "PHP"
                maintenanceProjector = component "Maintenance Projector" "Projects maintenance events to read models" "PHP"
            }
            
            eventStore = container "Event Store" "Stores all domain events" "EventStoreDB" {
                tags "Database"
            }
            
            readDatabase = container "Read Database" "Stores read models for queries" "PostgreSQL" {
                tags "Database"
                
                # Read Models/Projections per Bounded Context
                roomReadModels = component "Room Read Models" "Projections for room availability and features" "SQL Tables"
                rateReadModels = component "Rate Read Models" "Projections for room rates and discounts" "SQL Tables"
                reservationReadModels = component "Reservation Read Models" "Projections for bookings and stays" "SQL Tables"
                guestReadModels = component "Guest Read Models" "Projections for guest profiles and preferences" "SQL Tables"
                invoiceReadModels = component "Invoice Read Models" "Projections for invoices and payments" "SQL Tables"
                maintenanceReadModels = component "Maintenance Read Models" "Projections for tasks and supplies" "SQL Tables"
            }
            
            # Container relationships
            webApp -> apiApplication "Calls API endpoints"
            apiApplication -> eventStore "Stores events"
            apiApplication -> readDatabase "Reads/Writes projections"
            
            guest -> webApp "Makes reservations, checks in/out, views invoices using"
            staff -> webApp "Manages rooms, reservations, maintenance and billing using"
            
            # Component relationships - Web App
            guestPortal -> authComponent "Uses"
            staffPortal -> authComponent "Uses"
            guestPortal -> apiApplication "Makes API calls to"
            staffPortal -> apiApplication "Makes API calls to"
            
            # Component relationships - API Application
            commandBus -> reservationContext "Routes commands to"
            commandBus -> accommodationContext "Routes commands to"
            commandBus -> guestContext "Routes commands to"
            commandBus -> financeContext "Routes commands to"
            commandBus -> maintenanceContext "Routes commands to"
            
            reservationContext -> eventBus "Publishes events to"
            accommodationContext -> eventBus "Publishes events to"
            guestContext -> eventBus "Publishes events to"
            financeContext -> eventBus "Publishes events to"
            maintenanceContext -> eventBus "Publishes events to"
            
            eventBus -> eventStoreClient "Sends events via"
            eventStoreClient -> eventStore "Stores events in"
            
            # Event projections to read models
            eventBus -> accommodationProjector "Triggers accommodation projections"
            eventBus -> reservationProjector "Triggers reservation projections"
            eventBus -> guestProjector "Triggers guest projections"
            eventBus -> financeProjector "Triggers finance projections"
            eventBus -> maintenanceProjector "Triggers maintenance projections"
            
            # Projectors update read models
            accommodationProjector -> roomReadModels "Updates"
            accommodationProjector -> rateReadModels "Updates"
            reservationProjector -> reservationReadModels "Updates"
            guestProjector -> guestReadModels "Updates"
            financeProjector -> invoiceReadModels "Updates"
            maintenanceProjector -> maintenanceReadModels "Updates"
        }
        
        guest -> hotelSystem "Makes reservations, checks in/out, views invoices"
        staff -> hotelSystem "Manages rooms, reservations, maintenance and billing"
    }
    
    views {
        systemContext hotelSystem "SystemContext" {
            include *
            autoLayout
        }
        
        container hotelSystem "Containers" {
            include *
            autoLayout
        }
        
        component webApp "WebAppComponents" {
            include *
            autoLayout
        }
        
        component apiApplication "ApiComponents" {
            include *
            autoLayout
        }
        
        component readDatabase "ReadModels" {
            include *
            autoLayout
        }
        
        styles {
            element "Person" {
                shape Person
                background #08427B
                color #ffffff
            }
            element "Software System" {
                background #1168BD
                color #ffffff
            }
            element "Event Sourced" {
                background #5CB85C
                color #ffffff
            }
            element "Container" {
                background #438DD5
                color #ffffff
            }
            element "Component" {
                background #85BBF0
                color #000000
            }
            element "Web Application" {
                shape WebBrowser
            }
            element "Database" {
                shape Cylinder
                background #B86D33
            }
            element "API" {
                shape Hexagon
            }
        }
    }
}