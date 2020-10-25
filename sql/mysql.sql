# Reviews table
CREATE TABLE cr_reviews (
    rev_id        INT UNSIGNED NOT NULL AUTO_INCREMENT,
    dept_id       INT UNSIGNED,
    course_id     INT UNSIGNED,
    prof_id       INT UNSIGNED NOT NULL,
    rev_uid       INT UNSIGNED NOT NULL,
    icon          VARCHAR(25)  NOT NULL,
    prof_review   TEXT,
    course_review TEXT,
    syllabus_url  VARCHAR(100),
    syllabus_text TEXT,
    image_url     VARCHAR(100),
    difficulty    INT UNSIGNED NOT NULL,
    usefulness    INT UNSIGNED NOT NULL,
    effort        INT UNSIGNED NOT NULL,
    prof_effect   INT UNSIGNED NOT NULL,
    prof_fair     INT UNSIGNED NOT NULL,
    prof_avail    INT UNSIGNED NOT NULL,
    overall       INT UNSIGNED NOT NULL,
    time          TIMESTAMP    NOT NULL,
    comments      INT          NOT NULL,
    feature       BOOL         NOT NULL,
    approve       BOOL         NOT NULL,
    syllabus_mime VARCHAR(100),
    term          VARCHAR(10)  NOT NULL DEFAULT '',
    year          INT(10)      NOT NULL DEFAULT '0',
    PRIMARY KEY (rev_id),
    FOREIGN KEY (dept_id) REFERENCES cr_depts,
    FOREIGN KEY (course_id) REFERENCES cr_courses,
    FOREIGN KEY (prof_id) REFERENCES cr_profs,
    FOREIGN KEY (rev_uid) REFERENCES users
);

# Depts table
CREATE TABLE cr_depts (
    dept_id   INT UNSIGNED NOT NULL AUTO_INCREMENT,
    dept_name VARCHAR(100),
    PRIMARY KEY (dept_id)
);

# Courses table
CREATE TABLE cr_courses (
    course_id INT          NOT NULL AUTO_INCREMENT,
    dept_id   INT          NOT NULL,
    num       VARCHAR(6)   NOT NULL,
    name      VARCHAR(100),
    term      VARCHAR(10)  NOT NULL,
    year      INT UNSIGNED NOT NULL,
    units     INT UNSIGNED NOT NULL,

    cteaser   TEXT,
    creview   TEXT,

    PRIMARY KEY (course_id),
    FOREIGN KEY (dept_id) REFERENCES cr_depts
);

# Profs table
CREATE TABLE cr_profs (
    prof_id   INT         NOT NULL AUTO_INCREMENT,
    dept_id   INT         NOT NULL,
    lname     VARCHAR(50) NOT NULL,
    fname     VARCHAR(50) NOT NULL,
    image_url VARCHAR(100),
    pteaser   TEXT;
preview TEXT;
PRIMARY KEY (prof_id),
  FOREIGN KEY (dept_id) REFERENCES cr_depts
);

CREATE TABLE cr_ratings (
    rev_id     INT DEFAULT '0' NOT NULL,
    num_useful INT DEFAULT '0' NOT NULL,
    total      INT DEFAULT '0' NOT NULL,
    UNIQUE (rev_id)
);

CREATE TABLE cr_reporting (
    id   TINYINT(4)   NOT NULL AUTO_INCREMENT,
    uid  MEDIUMINT(8) NOT NULL DEFAULT '0',
    lid  MEDIUMINT(8) NOT NULL DEFAULT '0',
    rid  TINYINT(4)            DEFAULT '0',
    mess TEXT         NOT NULL,
    PRIMARY KEY (id)
)
    ENGINE = ISAM COMMENT ='users reports';


CREATE TABLE cr_photos (
    id      INT(10)      NOT NULL AUTO_INCREMENT,
    name    VARCHAR(100) NOT NULL DEFAULT '',
    img     VARCHAR(255) NOT NULL DEFAULT '',
    rating  INT(10)      NOT NULL DEFAULT '0',
    prof_id INT(10)      NOT NULL DEFAULT '0',
    uid     INT(10)      NOT NULL DEFAULT '0',
    PRIMARY KEY (id)
)
    ENGINE = ISAM;

