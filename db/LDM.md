# Logical Data Model (LDM)

The Logical Data Model defines the structure of the data elements and their relationships, adding attributes and specifying primary/foreign keys without committing to physical storage types.

## Entities & Attributes

### Users
*   **user_id** (PK)
*   username
*   email
*   password_hash
*   created_at

### DrivingSession
*   **session_id** (PK)
*   user_id (FK -> Users)
*   start_date
*   end_date
*   mileage
*   visibility_id (FK -> Visibility)
*   weather_condition_id (FK -> WeatherCondition)

### WeatherCondition
*   **weather_condition_id** (PK)
*   weather_condition

### Visibility
*   **visibility_id** (PK)
*   visibility

### RoadType
*   **road_type_id** (PK)
*   road_type

### TrafficCondition
*   **traffic_condition_id** (PK)
*   traffic_condition

### RoutePoints (Weak Entity)
*   **point_id** (PK)
*   session_id (FK -> DrivingSession)
*   latitude
*   longitude
*   timestamp

### OccursOn (Association Entity for Session-RoadType)
*   session_id (FK -> DrivingSession)
*   road_type_id (FK -> RoadType)

### TakesPlace (Association Entity for Session-Traffic)
*   session_id (FK -> DrivingSession)
*   traffic_condition_id (FK -> TrafficCondition)
