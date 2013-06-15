CREATE TABLE post (
    title varchar(50) NOT NULL,
    slug varchar(50) PRIMARY KEY,
    body text NOT NULL,
    created_at timestamp DEFAULT LOCALTIMESTAMP,
    updated_at timestamp DEFAULT LOCALTIMESTAMP
);
