INSERT INTO season (year) VALUES (2025);

INSERT INTO team (name, city) VALUES
 ('Univ. Craiova','Craiova'),
 ('FC Botosani','Botosani'),
 ('Rapid Bucuresti','Bucuresti'),
 ('Farul Constanta','Constanta');

INSERT INTO venue (name, city) VALUES
 ('Ion Oblemenco','Craiova'),
 ('Municipal Botosani','Botosani'),
 ('Giulesti','Bucuresti'),
 ('Farul Stadium','Constanta');

-- persons (players + officials)
INSERT INTO person (full_name) VALUES
 ('Mihai Radu'), ('Andrei Pop'), ('Ionut Stoica'), ('Vlad Ionescu'),
 ('Robert Marin'), ('Cristian Enache'), ('Sorin Matei'), ('Florin Dima'),
 ('Alex Referee'), ('Bogdan Linesman');

-- players (map to teams)
INSERT INTO player (person_id, team_id, position, shirt_number) VALUES
 (1, 1, 'FW', 9),
 (2, 1, 'MF', 8),
 (3, 2, 'FW', 10),
 (4, 2, 'DF', 4),
 (5, 3, 'FW', 11),
 (6, 3, 'MF', 6),
 (7, 4, 'FW', 7),
 (8, 4, 'GK', 1);

-- officials
INSERT INTO official (person_id, role) VALUES (9, 'referee'), (10, 'linesman');
INSERT INTO referee (person_id) VALUES (9);
INSERT INTO linesman (person_id) VALUES (10);

-- one row per team for the 2025 season
INSERT INTO team_season (season_id, team_id) SELECT 1, team_id FROM team;

-- matches (some played, one upcoming fixture)
-- round 1
INSERT INTO `match`
(season_id, round_no, date_time, home_team_id, away_team_id, venue_id, home_goals, away_goals) VALUES
 (1, 1, '2025-09-01 18:00:00', 1, 2, 1, 2, 1),  -- Craiova 2-1 Botosani
 (1, 1, '2025-09-02 20:00:00', 3, 4, 3, 1, 1);  -- Rapid 1-1 Farul

-- round 2
INSERT INTO `match`
(season_id, round_no, date_time, home_team_id, away_team_id, venue_id, home_goals, away_goals) VALUES
 (1, 2, '2025-09-09 19:00:00', 4, 1, 4, 0, 3),  -- Farul 0-3 Craiova
 (1, 2, '2025-09-10 19:30:00', 2, 3, 2, 2, 2);  -- Botosani 2-2 Rapid

-- round 3 (one fixture without score yet)
INSERT INTO `match`
(season_id, round_no, date_time, home_team_id, away_team_id, venue_id, home_goals, away_goals) VALUES
 (1, 3, '2025-09-18 18:00:00', 3, 1, 3, 0, 2),  -- Rapid 0-2 Craiova
 (1, 3, '2025-10-01 17:30:00', 2, 4, 2, NULL, NULL); -- upcoming
