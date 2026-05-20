# Real Estate MLS — Web Application

A full-stack web application simulating a real estate Multiple Listing Service (MLS), built for COP4710 (Database Systems) at Florida State University. The project covers relational database design, complex SQL querying, and a PHP/HTML/jQuery front-end with AJAX-driven interactions backed by MySQL. Developed on macOS using MAMP.

---

## Tech Stack

- **Database:** MySQL (MariaDB via XAMPP)
- **Back-end:** PHP (with prepared statements for SQL injection protection)
- **Front-end:** HTML, CSS, JavaScript, jQuery (AJAX)
- **Server:** Apache (MAMP for macOS)

---

## Features

- **All Listings** — displays every property in the database with owner, price, bedrooms, bathrooms, and business type in a single unified table
- **Search Houses** — filter by min/max price, minimum bedrooms, and minimum bathrooms; results ordered by price descending
- **Search Business Properties** — filter by price range and square footage range; results ordered by price descending
- **Agent Directory** — view all agents with their firm name and employment start date (JOIN across Agent and Firm tables)
- **Buyer Directory** — view all buyers with property type preferences and price ranges
- **Custom SQL Console** — enter any SQL query manually and view results rendered as an HTML table

---

## Database Schema

| Table | Primary Key | Notable Constraints |
|---|---|---|
| `Property` | `address` | Base table for all properties |
| `House` | `address` (FK → Property) | Inherits from Property |
| `BusinessProperty` | `address` (FK → Property) | Inherits from Property |
| `Agent` | `agentId` | `firmId` FK → Firm |
| `Firm` | `id` | — |
| `Buyer` | `id` | Stores price range preferences |
| `Listings` | `mlsNumber` | `address` FK → Property, `agentId` FK → Agent |
| `Works_With` | `(buyerId, agentId)` | Junction table |

---

## SQL Queries

10 queries implemented covering a range of complexity:

| # | Description | Techniques Used |
|---|---|---|
| 1 | All listed house addresses | JOIN |
| 2 | Listed house addresses + MLS numbers | JOIN |
| 3 | 3-bed / 2-bath houses currently listed | JOIN + WHERE |
| 4 | 3-bed / 2-bath houses, $100K–$250K | JOIN + BETWEEN + ORDER BY DESC |
| 5 | Office space listings by price | JOIN + WHERE + ORDER BY DESC |
| 6 | All agents with firm names and start dates | JOIN |
| 7 | All properties listed by a specific agent | WHERE filter |
| 8 | Agent–Buyer name pairs | 3-table JOIN + ORDER BY |
| 9 | Buyer count per agent | GROUP BY + COUNT |
| 10 | Houses matching a buyer's preferences | Multi-table JOIN + BETWEEN |

---

## Project Structure

```
/real_estate/
  ├── index.php            # Main app — tabbed UI with AJAX calls
  ├── database.php         # PHP back-end router (listings, search, agents, buyers, SQL)
  ├── ListingQueries.sql   # All 10 course-required SQL queries
  ├── create_tables.sql    # Schema creation script
  └── seed_data.sql        # Test data (5+ records per table)
```

---

## Setup & Installation

1. Download and install [MAMP](https://www.mamp.info/en/downloads/)
2. Start Apache and MySQL from the MAMP control panel
3. Open a terminal and log into MySQL:
   ```bash
   /Applications/MAMP/Library/bin/mysql -u root -p
   ```
4. Create the database and run the scripts:
   ```sql
   CREATE DATABASE real_estate_listings;
   USE real_estate_listings;
   SOURCE create_tables.sql;
   SOURCE seed_data.sql;
   ```
5. Place the project folder in `/Applications/MAMP/htdocs/real_estate/`
6. Visit `http://localhost:8888/real_estate/index.php` in your browser

---

## Sample Data

The database is seeded with 10 property listings including houses and business properties (office space, department store, gas station, warehouse), 5 agents across 5 firms, and 5 buyers with varied preferences.

---

## Course

COP4710 — Database Systems | Florida State University
