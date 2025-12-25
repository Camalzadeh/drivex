# Conceptual Data Model (CDM)

The Conceptual Data Model identifies the high-level entities and relationships in the Extracker system, independent of specific database technology.

## Entities

*   **User**: A registered person who uses the application.
*   **DrivingSession** (Trip): A single period of driving activity recorded by a User.
*   **WeatherCondition**: The state of the atmosphere during a drive (e.g., Sunny, Rainy).
*   **Visibility**: The distance one can see during a drive (e.g., Clear, Foggy).
*   **RoadType**: The classification of the road being driven on (e.g., Highway, Urban).
*   **TrafficCondition**: The density of vehicles on the road (e.g., Light, Heavy).
*   **RoutePoint**: A specific geographic location (latitude/longitude) recorded at a specific time during a session.

## Relationships

*   **User - DrivingSession**: One-to-Many.
    *   A User can have multiple Driving Sessions.
    *   A Driving Session belongs to exactly one User.

*   **DrivingSession - WeatherCondition**: Many-to-One.
    *   A Driving Session has one primary Weather Condition.
    *   A Weather Condition can characterize many Driving Sessions.

*   **DrivingSession - Visibility**: Many-to-One.
    *   A Driving Session has one primary Visibility level.
    *   A Visibility level can characterize many Driving Sessions.

*   **DrivingSession - RoadType**: Many-to-Many.
    *   A Driving Session can occur on multiple Road Types (e.g., starting in Urban, moving to Highway).
    *   A Road Type can be associated with many Driving Sessions.

*   **DrivingSession - TrafficCondition**: Many-to-Many.
    *   A Driving Session can experience multiple Traffic Conditions.
    *   A Traffic Condition can be associated with many Driving Sessions.

*   **DrivingSession - RoutePoint**: One-to-Many.
    *   A Driving Session is composed of many Route Points (for live tracking).
    *   A Route Point belongs to exactly one Driving Session.
