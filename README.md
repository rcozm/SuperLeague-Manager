# SuperLeague Manager
Constructor University – Databases & Web Services Project
Student: Radu Cozmolici  

## Files
- `superleague_schema.sql` – Table definitions for the SuperLeague database
- `superleague_data.sql` – Sample data for testing (4 teams, 1 season, 6 matches)
- `superleague_queries.sql` – Six SQL queries (standings, fixtures, results, etc.)
- `A3_RUN_LOG.txt` – Query execution log captured from CLABSQL

## Entities (4):
- `add_person.php` – Add new persons to the database  
- `add_player.php` – Add players linked to existing persons  
- `add_team.php` – Create new teams  
- `add_venue.php` – Register new stadiums or arenas  

## Relationships (2):
- `link_player_team.php` – Assign players to teams  
- `link_team_venue.php` – Assign teams to their venues

## Web Application

### Pages
- **`index.html`** – Main entry page
- **`search_team.php`** – Search teams by name  
- **`team_detail.php`** – View a team’s matches, home venue, and city  
- **`search_player.php`** – Search players by name  
- **`player_detail.php`** – View player details (name, position, shirt number, team)

### Features
- Search functionality for both teams and players  
- Detailed player and team info pages  
- Clean navigation between search pages and home page  
- Input sanitization and result limits (50 max)  
- Consistent and simple design with proper navigation links

## Example Data

### Teams
- **Rapid București**  
- **FC Botoșani**  
- **Univ. Craiova**  
- **Farul Constanța**

### Players
- **Cristian Săpunaru** (DF #22 – Rapid București)  
- **Alexandru Mitriță** (FW #28 – Univ. Craiova)  
- **Enriko Papa** (MF #8 – FC Botoșani)  
- **Denis Alibec** (FW #7 – Farul Constanța)  

These entries can be used to test the search and player detail functionalities.

---

##  Database Connection
Database credentials are handled locally on the CLAMV server in a private `db.php` file  
(excluded from this repository for security).

## Database Connection
Database credentials are handled locally on the CLAMV server in a private `db.php` file (excluded from this repository for security)
