DROP TABLE posts;
DROP TABLE topics;
DROP TABLE users;
DROP TABLE categories;



CREATE TABLE users (
    user_id         INT(4)          NOT NULL AUTO_INCREMENT,
    user_login      VARCHAR(40)     NOT NULL,
    user_name       VARCHAR(40)     NOT NULL,
    user_last       VARCHAR(4)      NOT NULL,
    user_pass       VARCHAR(255)    NOT NULL,
    user_mail       VARCHAR(255)    NOT NULL,
    user_date       DATETIME        NOT NULL,
    user_level      INT(4)          NOT NULL,

    UNIQUE INDEX user_name_unique (user_login),
    PRIMARY KEY (user_id)
) ENGINE=INNODB;


CREATE TABLE categories (
    cat_id          INT(4)          NOT NULL AUTO_INCREMENT,
    cat_name        VARCHAR(255)    NOT NULL,
    cat_desc        VARCHAR(255)    NOT NULL,

    UNIQUE INDEX cat_name_unique (cat_name),
    PRIMARY KEY (cat_id)
) ENGINE=INNODB;


CREATE TABLE topics (
    top_id          INT(4)          NOT NULL AUTO_INCREMENT,
    top_subject     VARCHAR(255)    NOT NULL,
    top_date        DATETIME        NOT NULL,
    top_cat         INT(4)          NOT NULL,
    top_by          INT(4)          NOT NULL,

    PRIMARY KEY (top_id)
) ENGINE=INNODB;


CREATE TABLE posts (
    pos_id          INT(4)          NOT NULL AUTO_INCREMENT,
    pos_content     LONGTEXT        NOT NULL,
    pos_date        DATETIME        NOT NULL,
    pos_topic       INT(4)          NOT NULL,
    pos_by          INT(4)          NOT NULL,

    PRIMARY KEY (pos_id)
) ENGINE=INNODB;

ALTER TABLE topics ADD FOREIGN KEY (top_cat)   REFERENCES categories(cat_id) ON DELETE CASCADE  ON UPDATE CASCADE;
ALTER TABLE topics ADD FOREIGN KEY (top_by)    REFERENCES users(user_id)     ON DELETE RESTRICT ON UPDATE CASCADE;
ALTER TABLE posts  ADD FOREIGN KEY (pos_topic) REFERENCES topics(top_id)     ON DELETE CASCADE  ON UPDATE CASCADE;
ALTER TABLE posts  ADD FOREIGN KEY (pos_by)    REFERENCES users(user_id)     ON DELETE RESTRICT ON UPDATE CASCADE;