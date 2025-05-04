# Bounded Context Canvas - Maintenance

## Name
Maintenance

## Description
Manages all maintenance and housekeeping operations within the hotel, including room cleaning, repairs, inspections, and supply management. This context ensures hotel facilities are properly maintained and guest rooms are prepared to quality standards.

## Strategic Classification

### Domain
- **Supporting Domain**: Enables the core hotel experience by ensuring facilities are clean and functioning properly.

### Business Model
- **Operational Excellence**: Focused on efficiency and quality of hotel operations.

### Evolution
- **Mature with Emerging Practices**: While basic maintenance processes are mature, new efficiency practices and technologies continue to emerge.

## Domain Roles

### Key Domain Roles
- **CleaningTask**: Represents room cleaning work to be performed
- **MaintenanceTask**: Represents repair or maintenance work to be performed
- **InspectionTask**: Represents quality verification of rooms or facilities
- **SupplyRequest**: Represents a request for maintenance or cleaning supplies
- **Staff**: Personnel who perform maintenance and cleaning tasks

## Ubiquitous Language

### Key Terms
- **Task**: A unit of work to be performed (cleaning, maintenance, inspection)
- **Room Status**: The current state of a room (clean, dirty, inspected, maintenance)
- **Priority**: The urgency level of a task (normal, high, urgent)
- **Escalation**: Increasing the visibility and priority of an unresolved issue
- **Assignment**: Allocation of a task to specific staff member
- **Completion**: Finishing a task with resolution details
- **Supplies**: Materials needed for maintenance and cleaning operations
- **Inspection**: Quality verification of a room or facility

## Business Decisions

### Key Business Rules
- Rooms must be cleaned after guest checkout and before new check-in
- Maintenance tasks are prioritized based on impact to guest experience
- Urgent maintenance issues can trigger room status changes
- Task assignments consider staff expertise and current workload
- Room inspections must meet quality standards before rooms become available
- Supply requests must be fulfilled within time thresholds based on urgency
- Escalated tasks require supervisor attention and tracking
- Recurring maintenance follows preventative schedules

## Domain Events

### Key Events
- **CleaningRequested**: A new cleaning task has been created
- **CleaningCompleted**: A cleaning task has been finished
- **MaintenanceRequested**: A repair or maintenance task has been created
- **MaintenanceCompleted**: A repair or maintenance task has been finished
- **InspectionScheduled**: A quality verification task has been planned
- **InspectionCompleted**: A quality verification task has been finished
- **TaskAssigned**: A task has been allocated to a staff member
- **TaskRescheduled**: A task's scheduled time has been changed
- **TaskEscalated**: A task has been elevated in priority and visibility
- **TaskCancelled**: A task has been terminated before completion
- **MaintenanceSuppliesRequested**: Materials have been requested
- **MaintenanceSuppliesReceived**: Materials have been delivered

## Commands

### Key Commands
- **RequestCleaning**: Create a new room cleaning task
- **CompleteCleaning**: Mark a cleaning task as finished
- **RequestMaintenance**: Create a new repair or maintenance task
- **CompleteMaintenance**: Mark a maintenance task as resolved
- **ScheduleInspection**: Create a new quality verification task
- **CompleteInspection**: Mark an inspection task as finished
- **AssignTask**: Allocate a task to a specific staff member
- **RescheduleTask**: Change a task's scheduled time
- **EscalateTask**: Elevate a task's priority and visibility
- **CancelTask**: Terminate a task before completion
- **RequestSupplies**: Create a request for maintenance materials
- **ReceiveSupplies**: Record receipt of requested materials

## Queries

### Key Queries
- **GetTaskById**: Retrieve details of a specific task
- **GetTasksByStatus**: List tasks with a particular status
- **GetTasksByRoom**: Find all tasks for a specific room
- **GetTasksByAssignee**: List tasks allocated to a specific staff member
- **GetEscalatedTasks**: Find tasks that have been elevated in priority
- **GetScheduledTasks**: List tasks planned for a specific time period
- **GetActiveSupplyRequests**: List pending material requests
- **GetRoomStatusReport**: Generate a report of all room statuses
- **GetStaffWorkloadReport**: View task distribution among staff

## Dependencies

### Upstream
- **Reservation**: Provides check-out information to trigger cleaning tasks
- **Accommodation**: Provides room information for maintenance planning

### Downstream
- **Finance**: Consumes supply request data for cost tracking
- **Accommodation**: Updates room status based on maintenance activities

## Technical Characteristics

### Defining Characteristics
- **Event-sourced**: Maintains complete history of maintenance activities
- **Task-oriented**: Focused on work assignment and completion tracking
- **Mobile-friendly**: Designed for staff using mobile devices throughout the property

## Team

### Roles
- Housekeeping and maintenance managers
- Facility engineers and specialists
- Software engineers with operations expertise
- UX designers specialized in task management interfaces