
CREATE DATABASE blog_elf;
USE blog_elf;

CREATE TABLE users(
    userid INT NOT NULL AUTO_INCREMENT , 
    first_name VARCHAR(30) NOT NULL ,
    last_name VARCHAR(30) NOT NULL ,
    profile_ VARCHAR(100) NOT NULL, 
    username VARCHAR(50) NOT NULL ,
    passwd VARCHAR(125) NOT NULL,
    gmail VARCHAR(60) NOT NULL ,
    rol VARCHAR(20) NOT NULL,
    PRIMARY KEY (userid)
);

CREATE TABLE blogs(
    bid INT NOT NULL AUTO_INCREMENT ,
    abstract VARCHAR(254) NOT NULL ,
    long_description MEDIUMTEXT NOT NULL ,
    title VARCHAR(100) NOT NULL ,
    photo VARCHAR(100) NOT NULL,
    date_time  VARCHAR(50) NOT NULL,
    wid INT NOT NULL ,
    cid INT NOT NULL ,
    seen INT NOT NULL,
    PRIMARY KEY (bid)
);

CREATE TABLE category(
    cid INT NOT NULL AUTO_INCREMENT ,
    c_name VARCHAR(50) ,   
    PRIMARY KEY (cid)
);

CREATE TABLE media(
    path_file VARCHAR(255) NOT NULL,
    bid INT NOT NULL , 
    format VARCHAR(10) NOT NULL
);


CREATE TABLE feedback(
    fid INT NOT NULL AUTO_INCREMENT,
    comment VARCHAR(255) ,
    like_ BOOLEAN ,
    report TEXT,
    bid INT  ,
    userid INT, 
    PRIMARY KEY (fid)
);


-- SELECT feedback.comment , feedback.bid , blogs.title , blogs.seen , users.first_name , users.last_name ,
-- users.username ,SUM(feedback.like_) AS likes FROM blogs
-- INNER JOIN feedback ON feedback.bid = blogs.bid 
-- INNER JOIN users ON feedback.userid = users.userid 
-- WHERE blogs.wid = 2 GROUP BY feedback.like_;


-- SELECT blogs.bid, blogs.abstract, blogs.title, blogs.photo, category.c_name , blogs.date_time , blogs.seen FROM blogs JOIN category ON blogs.cid = category.cid JOIN feedback ON blogs.bid = feedback.bid GROUP BY blogs.bid HAVING COUNT(feedback.like_) > 0;
-- SELECT bid, abstract, title, photo, c_name , date_time , seen FROM blogs 
-- INNER JOIN category ON blogs.cid = category.cid ORDER BY blogs.bid 
-- LIMIT 10 WHERE blogs.seen > 0;
-- SELECT feedback.comment, COUNT(feedback.like_) as like_count FROM feedback WHERE feedback.bid = $bid GROUP BY feedback.comment;
