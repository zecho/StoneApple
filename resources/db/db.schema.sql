CREATE TABLE post (
    id serial PRIMARY KEY,
    title varchar(50) NOT NULL,
    slug varchar(50) UNIQUE NOT NULL,
    body text NOT NULL,
    created_at timestamp DEFAULT LOCALTIMESTAMP,
    updated_at timestamp DEFAULT LOCALTIMESTAMP
);

CREATE TABLE tag (
    id serial PRIMARY KEY,
    label varchar(100) NOT NULL UNIQUE,
    slug varchar(100) NOT NULL UNIQUE
);

CREATE TABLE post_tag (
    id serial PRIMARY KEY,
    post_id serial NOT NULL REFERENCES post (id) ON DELETE CASCADE,
    tag_id serial NOT NULL REFERENCES tag (id) ON DELETE CASCADE,
    UNIQUE (post_id, tag_id)
);

CREATE TABLE comment (
    id serial PRIMARY KEY,
    post_id serial NOT NULL REFERENCES post (id) ON DELETE CASCADE,
    name varchar(80) NOT NULL,
    email varchar(100) NOT NULL,
    website varchar(256) NULL,
    body text NOT NULL,
    created_at timestamp DEFAULT LOCALTIMESTAMP
);

CREATE TABLE "user" (
    id serial PRIMARY KEY,
    username varchar(50) UNIQUE NOT NULL,
    password varchar(50) NOT NULL,
    email varchar(100) UNIQUE NOT NULL,
    created_at timestamp DEFAULT LOCALTIMESTAMP,
    updated_at timestamp DEFAULT LOCALTIMESTAMP
);