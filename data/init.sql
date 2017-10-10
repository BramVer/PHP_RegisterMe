/**
  * Database creation script
  */

/* Foreign key constraints need to be explicitly enabled in SQLite */
PRAGMA foreign_keys = ON;

DROP TABLE IF EXISTS user;

CREATE TABLE user (
    id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    username VARCHAR NOT NULL,
    password VARCHAR NOT NULL,
    created_at VARCHAR NOT NULL,
    is_enabled BOOLEAN NOT NULL DEFAULT true
);

/* This will become user = 1. I'm creating this just to satisfy constraints here.
   The password will be properly hashed in the installer */
INSERT INTO user (
		username,
    password,
    created_at,
    is_enabled
) VALUES (
		"admin",
    "HENLOFRENDO",
    datetime('now', '-3 months'),
    0
);

DROP TABLE IF EXISTS post;

CREATE TABLE post (
  id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  title VARCHAR NOT NULL,
  body VARCHAR NOT NULL,
  user_id INTEGER NOT NULL,
  created_at VARCHAR NOT NULL,
  updated_at VARCHAR,
  FOREIGN KEY (user_id) REFERENCES user(id)
);

INSERT INTO post (
  title,
  body,
  user_id,
  created_at
) VALUES (
  "Mama mia, wat-een-e lekker-e post-a",
  "AUGH

  Gurl look at dat body..",
  1,
  datetime('now', '-2 months', '-45 minutes', '+10 seconds')
);

INSERT INTO post (
  title,
  body,
  user_id,
  created_at
) VALUES (
  "Papa pizza, wat-een-e lekker-e post-afilioneee",
  "wowie nice zo jaaaaaha dat body..",
  1,
  datetime('now', '-40 days', '+815 minutes', '+37 seconds')
);

INSERT INTO post (
  title,
  body,
  user_id,
  created_at
) VALUES (
  "Pardone mascarpone",
  "Don Corleone, et was ni express-zo-nee",
  1,
  datetime('now', '-13 days', '+815 minutes', '+37 seconds')
);

DROP TABLE IF EXISTS comment;

CREATE TABLE comment(
  id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  post_id INTEGER NOT NULL,
  created_at VARCHAR NOT NULL,
  name VARCHAR NOT NULL,
  website VARCHAR,
  text VARCHAR NOT NULL,
  FOREIGN KEY (post_id) REFERENCES post(id)
);

INSERT INTO comment(
  post_id,
  created_at,
  name,
  website,
  text
) VALUES (
  1,
  datetime('now', '-10 days', '+815 minutes', '+37 seconds'),
  "Jimminy Cricket",
  "http://example.com",
  "This is Cricket's contribution yesss"
);

INSERT INTO comment(
  post_id,
  created_at,
  name,
  website,
  text
) VALUES (
  1,
  datetime('now', '-8 days', '+815 minutes', '+37 seconds'),
  "BENIS",
  "http://benis.com",
  "This is a funny name hihi"
);
