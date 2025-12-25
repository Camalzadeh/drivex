# Database Documentation & Entity Relationships

This document outlines the database structure, entity relationships, and schema details for the Extracker application.

## Entity Relationship Diagram (ERD)

The following diagram illustrates the relationships between the tables in the database.

```mermaid
erDiagram
    Users ||--o{ DrivingSession : "initiates"
    DrivingSession ||--|| Visibility : "has"
    DrivingSession ||--|| WeatherCondition : "has"
    DrivingSession ||--|{ RoutePoints : "recorded in"
    
    DrivingSession ||--|{ OccursOn : "has"
    RoadType ||--|{ OccursOn : "part of"
    
    DrivingSession ||--|{ TakesPlace : "has"
    TrafficCondition ||--|{ TakesPlace : "part of"

    Users {
        int user_id PK
        string username
        string email
        string password_hash
        timestamp created_at
    }

    DrivingSession {
        int session_id PK
        int user_id FK
        timestamp start_date
        timestamp end_date
        float mileage
        int visibility_id FK
        int weather_condition_id FK
    }

    RoutePoints {
        int point_id PK
        int session_id FK
        double latitude
        double longitude
        timestamp timestamp
    }

    RoadType {
        int road_type_id PK
        string road_type
    }

    Visibility {
        int visibility_id PK
        string visibility
    }

    WeatherCondition {
        int weather_condition_id PK
        string weather_condition
    }

    TrafficCondition {
        int traffic_condition_id PK
        string traffic_condition
    }

    OccursOn {
        int session_id FK
        int road_type_id FK
    }

    TakesPlace {
        int session_id FK
        int traffic_condition_id FK
    }
```

## Table Descriptions

### Core Entities

#### `Users`
Stores user account information.
- **Relationships**: One User can have multiple `DrivingSession`s (1:N).

#### `DrivingSession`
Represents a single driving trip recorded by a user.
- **Relationships**:
  - Belongs to one `User`.
  - Has one `Visibility` condition.
  - Has one `WeatherCondition`.
  - Can have multiple `RoadTypes` (M:N via `OccursOn`).
  - Can have multiple `TrafficConditions` (M:N via `TakesPlace`).
  - Contains multiple `RoutePoints` (1:N) tracking the GPS path.

#### `RoutePoints`
Stores individual GPS coordinates for a session.

### Lookup Tables (Static Data)

- **`RoadType`**: Types of roads (e.g., Urban, Highway).
- **`TrafficCondition`**: Traffic density (e.g., Light, Heavy).
- **`WeatherCondition`**: Weather during the trip (e.g., Sunny, Rainy).
- **`Visibility`**: Visibility conditions (e.g., Clear, Low).

### Junction Tables (Many-to-Many Relationships)

- **`OccursOn`**: Links `DrivingSession` and `RoadType`. A trip can involve multiple road types (e.g., started on Urban, moved to Highway).
- **`TakesPlace`**: Links `DrivingSession` and `TrafficCondition`. A trip can experience varying traffic conditions.
