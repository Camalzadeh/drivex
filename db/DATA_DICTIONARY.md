# Data Dictionary

A comprehensive reference for all data elements in the Extracker database.

## 1. User Data
| Element Name | Table | Data Type | Description | Format/Example |
| :--- | :--- | :--- | :--- | :--- |
| User ID | `Users` | INT | Unique system ID for a user. | 1, 100, 503 |
| Username | `Users` | VARCHAR(50) | Public display name chosen by user. | "johndoe", "racer_x" |
| Email | `Users` | VARCHAR(100) | Unique contact email. | "user@example.com" |
| Created At | `Users` | TIMESTAMP | Date and time of registration. | YYYY-MM-DD HH:MM:SS |

## 2. Trip Data
| Element Name | Table | Data Type | Description | Format/Example |
| :--- | :--- | :--- | :--- | :--- |
| Session ID | `DrivingSession` | INT | Unique ID for a single trip. | 1001, 1002 |
| Start Date | `DrivingSession` | TIMESTAMP | Trip start time. | YYYY-MM-DD HH:MM:SS |
| End Date | `DrivingSession` | TIMESTAMP | Trip end time. | YYYY-MM-DD HH:MM:SS |
| Mileage | `DrivingSession` | FLOAT | Total distance covered. | 12.5 (km) |

## 3. Environmental Conditions (Lookups)
These fields are stored as foreign keys referring to standardized lookup tables.

| Element Name | Ref Table | Values (Examples) | Description |
| :--- | :--- | :--- | :--- |
| Weather | `WeatherCondition` | Sunny, Rainy, Foggy, Snowy | Atmospheric condition during trip. |
| Visibility | `Visibility` | Clear, Low, Moderate | How far the driver could see. |
| Road Type | `RoadType` | Urban, Highway, Rural | Classification of road surface/environment. |
| Traffic | `TrafficCondition` | Light, Heavy, Moderate | Density of other vehicles. |

## 4. Geospatial Data
Used for plotting trips on a map.

| Element Name | Table | Data Type | Description |
| :--- | :--- | :--- | :--- |
| Latitude | `RoutePoints` | DOUBLE | Geographic latitude coordinate. |
| Longitude | `RoutePoints` | DOUBLE | Geographic longitude coordinate. |
| Timestamp | `RoutePoints` | TIMESTAMP | Exact time the point was recorded. |
