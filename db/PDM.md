# Physical Data Model (PDM)

The Physical Data Model describes the implementation of the data model in the MySQL database, including specific data types and constraints.

## Tables

### `Users`
| Column | Type | Constraints | Description |
| :--- | :--- | :--- | :--- |
| `user_id` | INT | PK, AUTO_INCREMENT, NOT NULL | Unique identifier for the user. |
| `username` | VARCHAR(50) | NOT NULL | User's display name. |
| `email` | VARCHAR(100) | UNIQUE, NOT NULL | User's email address. |
| `password_hash` | VARCHAR(255) | NOT NULL | Hashed password for security. |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Account creation timestamp. |

### `DrivingSession`
| Column | Type | Constraints | Description |
| :--- | :--- | :--- | :--- |
| `session_id` | INT | PK, AUTO_INCREMENT, NOT NULL | Unique identifier for the trip. |
| `user_id` | INT | FK -> Users(user_id), NOT NULL | The user who drove this session. |
| `start_date` | TIMESTAMP | NOT NULL | Start time of the trip. |
| `end_date` | TIMESTAMP | NOT NULL | End time of the trip. |
| `mileage` | FLOAT | - | Distance driven in km. |
| `visibility_id` | INT | FK -> Visibility(id), NOT NULL | Primary visibility condition. |
| `weather_condition_id` | INT | FK -> WeatherCondition(id), NOT NULL | Primary weather condition. |

### Lookup Tables

#### `WeatherCondition`
| Column | Type | Constraints |
| :--- | :--- | :--- |
| `weather_condition_id` | INT | PK, NOT NULL |
| `weather_condition` | VARCHAR(50) | NOT NULL |

#### `Visibility`
| Column | Type | Constraints |
| :--- | :--- | :--- |
| `visibility_id` | INT | PK, NOT NULL |
| `visibility` | VARCHAR(50) | NOT NULL |

#### `RoadType`
| Column | Type | Constraints |
| :--- | :--- | :--- |
| `road_type_id` | INT | PK, NOT NULL |
| `road_type` | VARCHAR(50) | NOT NULL |

#### `TrafficCondition`
| Column | Type | Constraints |
| :--- | :--- | :--- |
| `traffic_condition_id` | INT | PK, NOT NULL |
| `traffic_condition` | VARCHAR(50) | NOT NULL |

### Junction Tables (Many-to-Many Relationships)

#### `OccursOn`
Links `DrivingSession` to `RoadType`.
| Column | Type | Constraints |
| :--- | :--- | :--- |
| `session_id` | INT | FK -> DrivingSession |
| `road_type_id` | INT | FK -> RoadType |

#### `TakesPlace`
Links `DrivingSession` to `TrafficCondition`.
| Column | Type | Constraints |
| :--- | :--- | :--- |
| `session_id` | INT | FK -> DrivingSession |
| `traffic_condition_id` | INT | FK -> TrafficCondition |

### `RoutePoints`
Stores geospatial data for live tracking.
| Column | Type | Constraints |
| :--- | :--- | :--- |
| `point_id` | INT | PK, AUTO_INCREMENT, NOT NULL |
| `session_id` | INT | FK -> DrivingSession, NOT NULL |
| `latitude` | DOUBLE | NOT NULL |
| `longitude` | DOUBLE | NOT NULL |
| `timestamp` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP |
