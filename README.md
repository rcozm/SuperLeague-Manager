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

## Database Connection
Database credentials are handled locally on the CLAMV server in a private `db.php` file (excluded from this repository for security)
