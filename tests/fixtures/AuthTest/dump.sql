INSERT INTO users(id, name, email, password, remember_token, role_id, created_at, updated_at) VALUES
  (1, 'Gerhard Feest', 'fidel.kutch@example.com', '$2y$12$p9Bub8AaSl7EHfoGMgaXReK7Cs50kjHswxzNPTB5B4mcoRWfHnv8u', null, 1, '2016-10-20 11:05:00', '2016-10-20 11:05:00'),
  (2, 'Alien West', 'alien.west@example.com', 'old_password', null, 2, '2016-10-20 11:05:00', '2016-10-20 11:05:00'),
  (3, 'Alien East', 'alien.ease@example.com', 'old_password', null, 2, '2016-10-20 11:05:00', '2016-10-20 11:05:00'),
  (4, 'Alien North', 'alien.north@example.com', 'old_password', null, 2, '2016-10-20 11:05:00', '2016-10-20 11:05:00'),
  (5, 'Alien South', 'alien.south@example.com', 'old_password', null, 2, '2016-10-20 11:05:00', '2016-10-20 11:05:00');

INSERT INTO password_reset_tokens(email, token, created_at) VALUES
 ('fidel.kutch@example.com11', '$2y$12$tZyxJSv7BzJ493ChpMTPWeHyC2kg9D5GyrGfKoT.4Nuwil.X5.k4e', '2018-11-11 11:00:00'),
 ('fidel.kutch@example.com', '$2y$12$ewaHBY8BFibcbMKd0Xqvy.UocBssoQs9TklnL3So3l3HrGWwEEEce', '2018-11-11 11:00:00');
