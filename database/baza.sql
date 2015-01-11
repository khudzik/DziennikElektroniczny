DROP TABLE activate;
DROP TABLE grades;
DROP TABLE posts;
DROP TABLE lessons;
DROP TABLE topics;
DROP TABLE parents;
DROP TABLE students;
DROP TABLE children;
DROP TABLE classes;
DROP TABLE users;
DROP TABLE categories;

CREATE TABLE users (
    user_id         INT(4)          NOT NULL AUTO_INCREMENT,
    user_login      VARCHAR(40)     NOT NULL,
    user_name       VARCHAR(40)     NOT NULL,
    user_last       VARCHAR(40)     NOT NULL,
    user_pass       VARCHAR(255)    NOT NULL,
    user_mail       VARCHAR(255)    NOT NULL,
    user_date       DATETIME        NOT NULL,
    user_level      INT(4)          NOT NULL,

    UNIQUE INDEX user_name_unique (user_login),
    PRIMARY KEY (user_id)
) ENGINE=INNODB;


CREATE TABLE students(
    student_id      INT(4)          NOT NULL AUTO_INCREMENT,
    user_id         INT(4)          NOT NULL,        
    class_id        INT(4),

    PRIMARY KEY (student_id)
) ENGINE=INNODB;


CREATE TABLE parents(
    parent_id       INT(4)          NOT NULL AUTO_INCREMENT,
    user_id         INT(4)          NOT NULL,
    children_id     INT(4),
    
    PRIMARY KEY (parent_id)
) ENGINE=INNODB;

CREATE TABLE children(
    child_id        INT(4)         NOT NULL AUTO_INCREMENT,
    child_1         INT(4),
    child_2         INT(4),
    child_3         INT(4),
    child_4         INT(4),
    child_5         INT(4),
    child_6         INT(4),

    PRIMARY KEY (child_id)
) ENGINE=INNODB;



CREATE TABLE categories (
    cat_id          INT(4)          NOT NULL AUTO_INCREMENT,
    cat_name        VARCHAR(255)    NOT NULL,
    cat_desc        VARCHAR(255)    NOT NULL,
    cat_by          INT(4)          NOT NULL,

    UNIQUE INDEX cat_name_unique (cat_name),
    PRIMARY KEY (cat_id)
) ENGINE=INNODB;


CREATE TABLE classes (
    class_id        INT(4)          NOT NULL AUTO_INCREMENT,
    cat_id          INT(4)          NOT NULL,

    PRIMARY KEY (class_id)
) ENGINE=INNODB;


CREATE TABLE topics (
    top_id          INT(4)          NOT NULL AUTO_INCREMENT,
    top_subject     VARCHAR(255)    NOT NULL,
    top_date        DATETIME        NOT NULL,
    top_cat         INT(4)          NOT NULL,
    top_by          INT(4)          NOT NULL,

    PRIMARY KEY (top_id)
) ENGINE=INNODB;

CREATE TABLE lessons(
    les_id         INT(4)          NOT NULL AUTO_INCREMENT,
    top_id         INT(4)          NOT NULL,

    PRIMARY KEY (les_id)
)ENGINE=INNODB;

CREATE TABLE grades(
    gra_id        INT(4)          NOT NULL AUTO_INCREMENT,
    les_id        INT(4)          NOT NULL,
    stu_id        INT(4)          NOT NULL,
    gd_1d         VARCHAR(40),
    gd_1          FLOAT,

    PRIMARY KEY (gra_id)
) ENGINE=INNODB;



CREATE TABLE posts (
    pos_id          INT(4)          NOT NULL AUTO_INCREMENT,
    pos_content     LONGTEXT        NOT NULL,
    pos_date        DATETIME        NOT NULL,
    pos_topic       INT(4)          NOT NULL,
    pos_by          INT(4)          NOT NULL,

    PRIMARY KEY (pos_id)
) ENGINE=INNODB;


CREATE TABLE activate(
    act_id          INT(4)          NOT NULL AUTO_INCREMENT,
    act_by          INT(4)          NOT NULL,
    act_date        DATETIME        NOT NULL,
    act_value       INT(4)          NOT NULL,
    act_flag        INT(4)          NOT NULL,

    PRIMARY KEY (act_id)
) ENGINE=INNODB;







ALTER TABLE topics   ADD FOREIGN KEY (top_cat)   REFERENCES categories(cat_id)      ON DELETE CASCADE  ON UPDATE CASCADE;
ALTER TABLE topics   ADD FOREIGN KEY (top_by)    REFERENCES users(user_id)          ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE posts    ADD FOREIGN KEY (pos_topic)   REFERENCES topics(top_id)        ON DELETE CASCADE  ON UPDATE CASCADE;
ALTER TABLE posts    ADD FOREIGN KEY (pos_by)      REFERENCES users(user_id)        ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE activate ADD FOREIGN KEY (act_by)      REFERENCES users(user_id)        ON DELETE CASCADE  ON UPDATE CASCADE;

ALTER TABLE students ADD FOREIGN KEY (user_id)     REFERENCES users(user_id)        ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE students ADD FOREIGN KEY (class_id)    REFERENCES classes(class_id)     ON DELETE CASCADE  ON UPDATE CASCADE;

ALTER TABLE parents  ADD FOREIGN KEY (user_id)     REFERENCES users(user_id)        ON DELETE CASCADE  ON UPDATE CASCADE;
ALTER TABLE parents  ADD FOREIGN KEY (children_id) REFERENCES children(child_id)    ON DELETE CASCADE  ON UPDATE CASCADE;

ALTER TABLE classes  ADD FOREIGN KEY (cat_id)      REFERENCES categories(cat_id)    ON DELETE CASCADE  ON UPDATE CASCADE;
ALTER TABLE lessons  ADD FOREIGN KEY (top_id)      REFERENCES topics(top_id)        ON DELETE CASCADE  ON UPDATE CASCADE;

ALTER TABLE grades   ADD FOREIGN KEY (les_id)      REFERENCES lessons(les_id)       ON DELETE CASCADE  ON UPDATE CASCADE;
ALTER TABLE grades   ADD FOREIGN KEY (stu_id)      REFERENCES students(student_id)  ON DELETE CASCADE  ON UPDATE CASCADE;  