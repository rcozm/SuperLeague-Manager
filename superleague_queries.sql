-- Using default user database (db_rcozmolici)
SET @season_id := 1;
SET @team_id := 1;   -- change to 2/3/4 to test other teams

-- Q1: Standings computed from matches (ignores fixtures with NULL scores)
SET @season_id := 1;

SELECT
  t.team_id,
  t.name,
  -- wins
  SUM(CASE
        WHEN (m.home_team_id = t.team_id AND m.home_goals > m.away_goals)
          OR (m.away_team_id = t.team_id AND m.away_goals > m.home_goals)
        THEN 1 ELSE 0 END) AS wins,
  -- draws
  SUM(CASE
        WHEN m.home_goals IS NOT NULL AND m.away_goals IS NOT NULL
             AND ((m.home_team_id = t.team_id OR m.away_team_id = t.team_id)
                  AND m.home_goals = m.away_goals)
        THEN 1 ELSE 0 END) AS draws,
  -- losses
  SUM(CASE
        WHEN (m.home_team_id = t.team_id AND m.home_goals < m.away_goals)
          OR (m.away_team_id = t.team_id AND m.away_goals < m.home_goals)
        THEN 1 ELSE 0 END) AS losses,
  -- goals_for
  SUM(CASE
        WHEN m.home_team_id = t.team_id THEN IFNULL(m.home_goals,0)
        WHEN m.away_team_id = t.team_id THEN IFNULL(m.away_goals,0)
        ELSE 0 END) AS goals_for,
  -- goals_against
  SUM(CASE
        WHEN m.home_team_id = t.team_id THEN IFNULL(m.away_goals,0)
        WHEN m.away_team_id = t.team_id THEN IFNULL(m.home_goals,0)
        ELSE 0 END) AS goals_against,
  -- points
  SUM(CASE
        WHEN m.home_goals IS NULL OR m.away_goals IS NULL THEN 0
        WHEN (m.home_team_id = t.team_id AND m.home_goals > m.away_goals)
          OR (m.away_team_id = t.team_id AND m.away_goals > m.home_goals) THEN 3
        WHEN m.home_goals = m.away_goals THEN 1
        ELSE 0 END) AS points
FROM team t
LEFT JOIN `match` m
  ON m.season_id = @season_id
 AND (m.home_team_id = t.team_id OR m.away_team_id = t.team_id)
GROUP BY t.team_id, t.name
ORDER BY
  -- points DESC
  SUM(CASE
        WHEN m.home_goals IS NULL OR m.away_goals IS NULL THEN 0
        WHEN (m.home_team_id = t.team_id AND m.home_goals > m.away_goals)
          OR (m.away_team_id = t.team_id AND m.away_goals > m.home_goals) THEN 3
        WHEN m.home_goals = m.away_goals THEN 1
        ELSE 0 END) DESC,
  -- goal difference DESC
  (SUM(CASE
         WHEN m.home_team_id = t.team_id THEN IFNULL(m.home_goals,0)
         WHEN m.away_team_id = t.team_id THEN IFNULL(m.away_goals,0)
         ELSE 0 END)
   -
   SUM(CASE
         WHEN m.home_team_id = t.team_id THEN IFNULL(m.away_goals,0)
         WHEN m.away_team_id = t.team_id THEN IFNULL(m.home_goals,0)
         ELSE 0 END)) DESC,
  -- goals_for DESC
  SUM(CASE
        WHEN m.home_team_id = t.team_id THEN IFNULL(m.home_goals,0)
        WHEN m.away_team_id = t.team_id THEN IFNULL(m.away_goals,0)
        ELSE 0 END) DESC,
  -- then name
  t.name;

-- Q2: All fixtures/results for a team
SELECT
  m.round_no,
  DATE_FORMAT(m.date_time, '%Y-%m-%d %H:%i') AS kickoff,
  th.name AS home,
  ta.name AS away,
  CONCAT(COALESCE(m.home_goals,'?'),' - ',COALESCE(m.away_goals,'?')) AS score
FROM `match` m
JOIN team th ON m.home_team_id = th.team_id
JOIN team ta ON m.away_team_id = ta.team_id
WHERE m.season_id = @season_id
  AND (@team_id IN (m.home_team_id, m.away_team_id))
ORDER BY m.date_time;

-- Q3: Upcoming fixtures only for a team
SELECT
  m.round_no, DATE_FORMAT(m.date_time, '%Y-%m-%d %H:%i') AS kickoff,
  th.name AS home, ta.name AS away
FROM `match` m
JOIN team th ON m.home_team_id = th.team_id
JOIN team ta ON m.away_team_id = ta.team_id
WHERE m.season_id = @season_id
  AND (@team_id IN (m.home_team_id, m.away_team_id))
  AND m.home_goals IS NULL AND m.away_goals IS NULL
ORDER BY m.date_time;

-- Q4: Recent form (last 5 played) for a team as W/D/L
SELECT
  DATE_FORMAT(m.date_time, '%Y-%m-%d') AS day,
  CASE
    WHEN m.home_goals IS NULL OR m.away_goals IS NULL THEN '-'
    WHEN (m.home_team_id = @team_id AND m.home_goals > m.away_goals)
      OR (m.away_team_id = @team_id AND m.away_goals > m.home_goals) THEN 'W'
    WHEN m.home_goals = m.away_goals THEN 'D'
    ELSE 'L'
  END AS result,
  th.name AS home, ta.name AS away, m.home_goals, m.away_goals
FROM `match` m
JOIN team th ON m.home_team_id = th.team_id
JOIN team ta ON m.away_team_id = ta.team_id
WHERE m.season_id = @season_id
  AND (@team_id IN (m.home_team_id, m.away_team_id))
  AND m.home_goals IS NOT NULL AND m.away_goals IS NOT NULL
ORDER BY m.date_time DESC
LIMIT 5;

-- Q5: Goals scored at home vs away per team
SELECT
  t.name,
  SUM(CASE WHEN m.home_team_id = t.team_id THEN IFNULL(m.home_goals,0) ELSE 0 END) AS goals_home,
  SUM(CASE WHEN m.away_team_id = t.team_id THEN IFNULL(m.away_goals,0) ELSE 0 END) AS goals_away
FROM team t
LEFT JOIN `match` m
  ON m.season_id = @season_id
 AND (m.home_team_id = t.team_id OR m.away_team_id = t.team_id)
GROUP BY t.team_id, t.name
ORDER BY t.name;

-- Q6: Round schedule (with venue) for a given round
SET @round := 1;
SELECT
  m.round_no,
  DATE_FORMAT(m.date_time, '%Y-%m-%d %H:%i') AS kickoff,
  th.name AS home, ta.name AS away,
  v.name AS venue,
  m.home_goals, m.away_goals
FROM `match` m
JOIN team th ON m.home_team_id = th.team_id
JOIN team ta ON m.away_team_id = ta.team_id
LEFT JOIN venue v ON m.venue_id = v.venue_id
WHERE m.season_id = @season_id AND m.round_no = @round
ORDER BY m.date_time;
