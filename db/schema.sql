CREATE TABLE content (
  slug TEXT NOT NULL,
  content TEXT NOT NULL,
  PRIMARY KEY(slug)
);

CREATE TABLE users (
  username TEXT NOT NULL,
  password TEXT NOT NULL,
  PRIMARY KEY(username)
);
