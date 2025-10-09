-- SuperLeague Manager - Assignment 3

CREATE TABLE season (
  season_id INT AUTO_INCREMENT PRIMARY KEY,
  year INT NOT NULL,
  UNIQUE KEY uq_season_year (year)
) ENGINE=InnoDB;

CREATE TABLE team (
  team_id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  city VARCHAR(100),
  UNIQUE KEY uq_team_name (name)
) ENGINE=InnoDB;

CREATE TABLE venue (
  venue_id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  city VARCHAR(100) NOT NULL,
  UNIQUE KEY uq_venue_name (name)
) ENGINE=InnoDB;

CREATE TABLE person (
  person_id INT AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(120) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE official (
  person_id INT PRIMARY KEY,
  role VARCHAR(50) DEFAULT 'official',
  CONSTRAINT fk_official_person FOREIGN KEY (person_id)
    REFERENCES person(person_id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE referee (
  person_id INT PRIMARY KEY,
  CONSTRAINT fk_referee_official FOREIGN KEY (person_id)
    REFERENCES official(person_id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE linesman (
  person_id INT PRIMARY KEY,
  CONSTRAINT fk_linesman_official FOREIGN KEY (person_id)
    REFERENCES official(person_id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE player (
  person_id INT PRIMARY KEY,
  team_id INT,
  position VARCHAR(50),
  shirt_number INT,
  CONSTRAINT fk_player_person FOREIGN KEY (person_id)
    REFERENCES person(person_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_player_team FOREIGN KEY (team_id)
    REFERENCES team(team_id) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT uq_player_number UNIQUE (team_id, shirt_number)
) ENGINE=InnoDB;

CREATE TABLE team_season (
  ts_id INT AUTO_INCREMENT PRIMARY KEY,
  season_id INT NOT NULL,
  team_id INT NOT NULL,
  points INT NOT NULL DEFAULT 0,
  wins INT NOT NULL DEFAULT 0,
  draws INT NOT NULL DEFAULT 0,
  losses INT NOT NULL DEFAULT 0,
  goals_for INT NOT NULL DEFAULT 0,
  goals_against INT NOT NULL DEFAULT 0,
  CONSTRAINT fk_ts_season FOREIGN KEY (season_id)
    REFERENCES season(season_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_ts_team FOREIGN KEY (team_id)
    REFERENCES team(team_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT uq_ts UNIQUE (season_id, team_id)
) ENGINE=InnoDB;

CREATE TABLE `match` (
  match_id INT AUTO_INCREMENT PRIMARY KEY,
  season_id INT NOT NULL,
  round_no INT,
  date_time DATETIME,
  home_team_id INT NOT NULL,
  away_team_id INT NOT NULL,
  venue_id INT,
  home_goals INT NULL,
  away_goals INT NULL,
  CONSTRAINT chk_nonneg_home CHECK (home_goals IS NULL OR home_goals >= 0),
  CONSTRAINT chk_nonneg_away CHECK (away_goals IS NULL OR away_goals >= 0),
  CONSTRAINT fk_match_season FOREIGN KEY (season_id)
    REFERENCES season(season_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_match_home FOREIGN KEY (home_team_id)
    REFERENCES team(team_id) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT fk_match_away FOREIGN KEY (away_team_id)
    REFERENCES team(team_id) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT fk_match_venue FOREIGN KEY (venue_id)
    REFERENCES venue(venue_id) ON DELETE SET NULL ON UPDATE CASCADE,
  INDEX idx_match_home (home_team_id),
  INDEX idx_match_away (away_team_id),
  INDEX idx_match_season_round (season_id, round_no)
) ENGINE=InnoDB;
