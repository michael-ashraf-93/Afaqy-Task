## About The App

### Version

    php: 8.2
    laravel: 11.9

### App Description

##### Vehicle Expense Management System

This application is designed to efficiently manage and retrieve vehicle expenses using a variety of approaches to suit
different needs and scenarios. It provides a robust and scalable solution for tracking and analyzing vehicle-related
costs.

### Features

- Multiple Approaches for Retrieving Vehicle Expenses:
    - View Table: Provides a unified view of vehicle expenses by combining data from multiple tables.
    - Query Builder and Relations: Uses Laravel’s query builder and Eloquent relationships for flexible and efficient
      data retrieval.
    - Factory Design Pattern: Employs the Factory Design Pattern to handle data formatting and retrieval.

- Comprehensive Testing:
    - Includes test cases for all API endpoints to ensure functionality and reliability.

- API Throttling:
    - List All Vehicle Expenses: Throttled to 5 requests per minute to prevent abuse and ensure performance.
    - List Expenses for Specific Vehicle: Throttled to 5 requests per minute per vehicle, maintaining performance and
      preventing excessive load.

## Initialization

```bash
  composer install
```

```bash
  php artisan migrate
```

```bash
  php artisan serve
```

## Testing

```bash
  php artisan test
```

# First Approach
## View Table Approach
#### Description:
This APIs endpoint retrieves a list of vehicle expenses, including fuel entries, insurance payments, and
services. The data is fetched using a view table that combines information from multiple tables into a unified format.

#### Pros:
- **Simplified Queries:** Complex joins and aggregations are precomputed, making queries simpler and faster.
- **Performance:** Querying a view table can be more efficient for read-heavy operations, as the data is already aggregated.
- **Consistency:** Ensures consistent results as the view is defined in a single place.
#### Cons:
- **Data Freshness:** Depending on how the view is maintained, it may not reflect real-time data changes.
- **Maintenance:** Requires careful management of the view, especially if the underlying tables change frequently.
- **Storage:** May increase storage requirements as data is duplicated in the view table.

### Endpoints:

### Index

##### Description:

###### Retrieve list of vehicle expenses, with the ability of sorting, filtering, and paginating.

`` [GET]: {BaseURL}/api/vehicles/expenses``

##### Query Parameters:

| Parameter           | Type    | DataType  | Description                                                    |
|:--------------------|---------|:----------|:---------------------------------------------------------------|
| `type[]`            | `query` | `array`   | **(optional)**. List of types (e.g., fuel, insurance, service) |
| `vehicle_id`        | `query` | `numeric` | **(optional)**. Vehicle ID to filter                           |
| `vehicle_name`      | `query` | `string`  | **(optional)**. Vehicle name to filter                         |
| `plate_number`      | `query` | `string`  | **(optional)**. Vehicle Plate number to filter                 |
| `min_cost`          | `query` | `numeric` | **(optional)**. Minimum cost filter                            |
| `max_cost`          | `query` | `numeric` | **(optional)**. Maximum cost filter                            |
| `min_creation_date` | `query` | `date`    | **(optional)**. Minimum creation date filter                   |
| `max_creation_date` | `query` | `date`    | **(optional)**. Maximum creation date filter                   |
| `sort_by`           | `query` | `string`  | **(optional)**. Field to sort by (default: created_at)         |
| `sort_direction`    | `query` | `string`  | **(optional)**. Sort direction (asc or desc) (default: desc)   |
| `per_page`          | `query` | `numeric` | **(optional)**. Number of results per page (default: 100)      |
| `page`              | `query` | `numeric` | **(optional)**. Page number (default: 1)                       |

### Example Request:

#### [GET]

```
{BaseURL}/api/vehicles/expenses?type[]=fuel&type[]=insurance&type[]=service&vehicle_id=300&vehicle_name=ter&plate_number=90804&min_cost=9&max_cost=100&min_creation_date=2020-01-10&max_creation_date=2000-01-10&sort_by=created_at&sort_direction=asc&per_page=15&page=1
```

#### Response

```json
{
    "data": [
        {
            "vehicle_id": 3514,
            "vehicle_name": "Lorenzo Barge",
            "vehicle_plate_number": "4708404",
            "type": "service",
            "cost": 0,
            "created_at": "2020-02-27T00:31:24.000000Z"
        }
    ]
}
```

### Show

##### Description:

###### Retrieve list of expenses for specific vehicle, with the ability of sorting, filtering, and paginating.

`` [GET]: {BaseURL}/api/vehicles/{vehicle}/expenses``

##### Query Parameters:

| Parameter           | Type    | DataType  | Description                                                    |
|:--------------------|---------|:----------|:---------------------------------------------------------------|
| `vehicle_id`        | `path`  | `numeric` |                                                                |
| `type[]`            | `query` | `array`   | **(optional)**. List of types (e.g., fuel, insurance, service) |
| `min_cost`          | `query` | `numeric` | **(optional)**. Minimum cost filter                            |
| `max_cost`          | `query` | `numeric` | **(optional)**. Maximum cost filter                            |
| `min_creation_date` | `query` | `date`    | **(optional)**. Minimum creation date filter                   |
| `max_creation_date` | `query` | `date`    | **(optional)**. Maximum creation date filter                   |
| `sort_by`           | `query` | `string`  | **(optional)**. Field to sort by (default: created_at)         |
| `sort_direction`    | `query` | `string`  | **(optional)**. Sort direction (asc or desc) (default: desc)   |
| `per_page`          | `query` | `numeric` | **(optional)**. Number of results per page (default: 100)      |
| `page`              | `query` | `numeric` | **(optional)**. Page number (default: 1)                       |

### Example Request:

#### [GET]

```
{BaseURL}/api/vehicles/1/expenses?type[]=fuel&type[]=insurance&type[]=service&min_cost=9&max_cost=100&min_creation_date=2020-01-10&max_creation_date=2000-01-10&sort_by=created_at&sort_direction=asc&per_page=15&page=1
```

#### Response

```json
{
    "data": [
        {
            "vehicle_id": 3514,
            "vehicle_name": "Lorenzo Barge",
            "vehicle_plate_number": "4708404",
            "type": "service",
            "cost": 0,
            "created_at": "2020-02-27T00:31:24.000000Z"
        }
    ]
}
```

# Second Approach
## Query Builder and Relations Approach

#### Description:
This API endpoint retrieves a list of expenses aggregated from various types (fuel, insurance, service) for
vehicles. The data is queried using Laravel Query Builder, Laravel relations and includes filtering and sorting
options.

#### Pros:
- **Real-time Data:** Always works with the most current data from the database.
- **Flexibility:** Allows for dynamic query construction based on various conditions and relationships.
- **Granular Control:** Offers fine-grained control over the query logic and performance tuning.
#### Cons:
- **Complexity:** Can result in complex and hard-to-maintain query logic, especially with multiple joins and conditions.
- **Performance:** May be slower compared to precomputed views, especially for large datasets or complex aggregations.
- **Development Time:** Requires more development effort to construct and optimize queries.


### Endpoints:

### Index

##### Description:

###### Retrieve list of vehicle expenses, with the ability of sorting, filtering, and paginating.

`` [GET]: {BaseURL}/api/expenses/aggregator``

##### Query Parameters:

| Parameter           | Type    | DataType  | Description                                                    |
|:--------------------|---------|:----------|:---------------------------------------------------------------|
| `type[]`            | `query` | `array`   | **(optional)**. List of types (e.g., fuel, insurance, service) |
| `vehicle_id`        | `query` | `numeric` | **(optional)**. Vehicle ID to filter                           |
| `vehicle_name`      | `query` | `string`  | **(optional)**. Vehicle name to filter                         |
| `plate_number`      | `query` | `string`  | **(optional)**. Vehicle Plate number to filter                 |
| `min_cost`          | `query` | `numeric` | **(optional)**. Minimum cost filter                            |
| `max_cost`          | `query` | `numeric` | **(optional)**. Maximum cost filter                            |
| `min_creation_date` | `query` | `date`    | **(optional)**. Minimum creation date filter                   |
| `max_creation_date` | `query` | `date`    | **(optional)**. Maximum creation date filter                   |
| `sort_by`           | `query` | `string`  | **(optional)**. Field to sort by (default: created_at)         |
| `sort_direction`    | `query` | `string`  | **(optional)**. Sort direction (asc or desc) (default: desc)   |
| `per_page`          | `query` | `numeric` | **(optional)**. Number of results per page (default: 100)      |
| `page`              | `query` | `numeric` | **(optional)**. Page number (default: 1)                       |

##### Example Request:

### [GET]

```
{BaseURL}/api/expenses/aggregator?type[]=fuel&type[]=insurance&type[]=service&vehicle_id=300&vehicle_name=ter&plate_number=90804&min_cost=9&max_cost=100&min_creation_date=2020-01-10&max_creation_date=2000-01-10&sort_by=created_at&sort_direction=asc&per_page=15&page=1
```

#### Response

```json
{
    "data": [
        {
            "vehicle_id": 3514,
            "vehicle_name": "Lorenzo Barge",
            "vehicle_plate_number": "4708404",
            "type": "service",
            "cost": 0,
            "created_at": "2020-02-27T00:31:24.000000Z"
        }
    ]
}
```

### Show

##### Description:

###### Retrieve list of expenses for specific vehicle, with the ability of sorting, filtering, and paginating.

`` [GET]: {BaseURL}/api/expenses/aggregator/vehicles/{vehicle}``

##### Query Parameters:

| Parameter           | Type    | DataType  | Description                                                    |
|:--------------------|---------|:----------|:---------------------------------------------------------------|
| `vehicle`           | `path`  | `numeric` |                                                                |
| `type[]`            | `Query` | `array`   | **(optional)**. List of types (e.g., fuel, insurance, service) |
| `min_cost`          | `Query` | `numeric` | **(optional)**. Minimum cost filter                            |
| `max_cost`          | `Query` | `numeric` | **(optional)**. Maximum cost filter                            |
| `min_creation_date` | `Query` | `date`    | **(optional)**. Minimum creation date filter                   |
| `max_creation_date` | `Query` | `date`    | **(optional)**. Maximum creation date filter                   |
| `sort_by`           | `Query` | `string`  | **(optional)**. Field to sort by (default: created_at)         |
| `sort_direction`    | `Query` | `string`  | **(optional)**. Sort direction (asc or desc) (default: desc)   |
| `per_page`          | `Query` | `numeric` | **(optional)**. Number of results per page (default: 100)      |
| `page`              | `Query` | `numeric` | **(optional)**. Page number (default: 1)                       |

### Example Request:

#### [GET]

```
{BaseURL}/api/expenses/aggregator/vehicles/1?type[]=fuel&type[]=insurance&type[]=service&min_cost=9&max_cost=100&min_creation_date=2020-01-10&max_creation_date=2000-01-10&sort_by=created_at&sort_direction=asc&per_page=15&page=1
```

#### Response

```json
{
    "data": {
        "vehicle_id": 1,
        "vehicle_name": "Prof. Garland Lang",
        "plate_number": "3290804",
        "created_at": "2020-01-20T11:53:05.000000Z",
        "total_expenses": 105,
        "total_fuel_expenses": 12,
        "total_insurance_expenses": 93,
        "total_services_expenses": 0,
        "expenses": [
            {
                "vehicle_id": 1,
                "cost": 0,
                "created_at": "2020-02-26T06:07:05.000000Z",
                "type": "service"
            }
        ]
    }
}

```

# Third Approach

## Factory Design Pattern

#### Description:
This APIs endpoint retrieves an expenses based on various filters. It uses a Factory Design Pattern to handle different
expense types and query processing.

#### Pros:
- **Separation of Concerns:** Keeps the creation logic separate from business logic, making the code cleaner and more maintainable.
- **Flexibility:** Easily extendable to support new types of expenses or data sources.
#### Cons:
- **Complexity:** Adds an additional layer of abstraction, which can increase the complexity of the codebase.
- **Overhead:** May introduce slight overhead in terms of performance and memory usage due to the additional factory layer.

### Endpoints:

### Index

##### Description:

###### Retrieve list of vehicle expenses, with the ability of sorting, filtering, and paginating.

`` [GET]: {BaseURL}/api/expenses``

##### Query Parameters:

| Parameter           | Type    | DataType  | Description                                                    |
|:--------------------|---------|:----------|:---------------------------------------------------------------|
| `type[]`            | `query` | `array`   | **(optional)**. List of types (e.g., fuel, insurance, service) |
| `vehicle_id`        | `query` | `numeric` | **(optional)**. Vehicle ID to filter                           |
| `vehicle_name`      | `query` | `string`  | **(optional)**. Vehicle name to filter                         |
| `plate_number`      | `query` | `string`  | **(optional)**. Vehicle Plate number to filter                 |
| `min_cost`          | `query` | `numeric` | **(optional)**. Minimum cost filter                            |
| `max_cost`          | `query` | `numeric` | **(optional)**. Maximum cost filter                            |
| `min_creation_date` | `query` | `date`    | **(optional)**. Minimum creation date filter                   |
| `max_creation_date` | `query` | `date`    | **(optional)**. Maximum creation date filter                   |
| `sort_by`           | `query` | `string`  | **(optional)**. Field to sort by (default: created_at)         |
| `sort_direction`    | `query` | `string`  | **(optional)**. Sort direction (asc or desc) (default: desc)   |
| `per_page`          | `query` | `numeric` | **(optional)**. Number of results per page (default: 100)      |
| `page`              | `query` | `numeric` | **(optional)**. Page number (default: 1)                       |

### Example Request:

#### [GET]

```
{BaseURL}/api/expenses?type[]=fuel&type[]=insurance&type[]=service&vehicle_id=300&vehicle_name=ter&plate_number=90804&min_cost=9&max_cost=100&min_creation_date=2020-01-10&max_creation_date=2000-01-10&sort_by=created_at&sort_direction=asc&per_page=15&page=1
```

#### Response

```json
{
    "data": [
        {
            "vehicle_id": 3514,
            "vehicle_name": "Lorenzo Barge",
            "vehicle_plate_number": "4708404",
            "type": "service",
            "cost": 0,
            "created_at": "2020-02-27T00:31:24.000000Z"
        }
    ]
}
```

### Show

##### Description:

###### Retrieve list of expenses for specific vehicle, with the ability of sorting, filtering, and paginating.

`` [GET]: {BaseURL}/api/expenses/vehicles/{vehicle}``

##### Query Parameters:

| Parameter           | Type    | DataType  | Description                                                    |
|:--------------------|---------|:----------|:---------------------------------------------------------------|
| `vehicle`           | `path`  | `numeric` |                                                                |
| `type[]`            | `query` | `array`   | **(optional)**. List of types (e.g., fuel, insurance, service) |
| `min_cost`          | `query` | `numeric` | **(optional)**. Minimum cost filter                            |
| `max_cost`          | `query` | `numeric` | **(optional)**. Maximum cost filter                            |
| `min_creation_date` | `query` | `date`    | **(optional)**. Minimum creation date filter                   |
| `max_creation_date` | `query` | `date`    | **(optional)**. Maximum creation date filter                   |
| `sort_by`           | `query` | `string`  | **(optional)**. Field to sort by (default: created_at)         |
| `sort_direction`    | `query` | `string`  | **(optional)**. Sort direction (asc or desc) (default: desc)   |
| `per_page`          | `query` | `numeric` | **(optional)**. Number of results per page (default: 100)      |
| `page`              | `query` | `numeric` | **(optional)**. Page number (default: 1)                       |

### Example Request:

#### [GET]

```
{BaseURL}/api/expenses/vehicles/1?type[]=fuel&type[]=insurance&type[]=service&min_cost=9&max_cost=100&min_creation_date=2020-01-10&max_creation_date=2000-01-10&sort_by=created_at&sort_direction=asc&per_page=15&page=1
```

#### Response

```json
{
    "data": [
        {
            "vehicle_id": 3514,
            "vehicle_name": "Lorenzo Barge",
            "vehicle_plate_number": "4708404",
            "type": "service",
            "cost": 0,
            "created_at": "2020-02-27T00:31:24.000000Z"
        }
    ]
}
```
