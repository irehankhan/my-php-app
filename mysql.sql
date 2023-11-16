   CREATE DATABASE IF NOT EXISTS mydatabase;
   USE mydatabase;

   CREATE TABLE IF NOT EXISTS users (
       id INT AUTO_INCREMENT PRIMARY KEY,
       name VARCHAR(50) NOT NULL,
       email VARCHAR(50) NOT NULL,
       contactNumber VARCHAR(20) NOT NULL
   );

   INSERT INTO users (name, email, contactNumber) VALUES
   ('John Doe', 'john@example.com', '03005002357'),
   ('Jane Smith', 'jane@example.com', '03005002357'),
   ('Mike Johnson', 'mike@example.com', '03005002357');