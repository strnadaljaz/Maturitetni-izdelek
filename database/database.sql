USE taskfall_todo;

CREATE TABLE users (
    user_id INT AUTO_INCREMENT,
    username VARCHAR(255),
    email VARCHAR(255),
    user_password VARCHAR(255),
    PRIMARY KEY (user_id)
);

CREATE TABLE tasks (
    task_id INT AUTO_INCREMENT,
    task TEXT,
    user_id INT,
    task_done BOOLEAN DEFAULT 0,
    deadline DATETIME,
    PRIMARY KEY (task_id),
    FOREIGN KEY FK_TASKS_USERS (user_id) REFERENCES users(user_id)
);
