/**
  * Database creation script
  */

DROP TABLE IF EXISTS post;

CREATE TABLE post (
  id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
  title VARCHAR NOT NULL,
  body VARCHAR NOT NULL,
  user_id INTEGER NOT NULL,
  created_at VARCHAR NOT NULL,
  updated_at VARCHAR
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
  date('now', '-2 months')
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
  date('now', '-40 days')
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
  date('now', '-13 days')
);
