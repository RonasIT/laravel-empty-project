INSERT INTO users(id, name, email, password, remember_token, set_password_hash, role_id, created_at, updated_at) VALUES
  (1, 'Gerhard Feest', 'fidel.kutch@example.com', '$2y$10$X4receiTrF24bXrEbAiChOZ8TMNPqoXuhuThgynvBdWIHZeu5HzsS', null, null, 1, '2016-10-20 11:05:00', '2016-10-20 11:05:00'),
  (2, 'Alien West', 'alien.west@example.com', 'old_password', null, 'restore_token', 2, '2016-10-20 11:05:00', '2016-10-20 11:05:00');

INSERT INTO media(id, name, owner_id, is_public, link, created_at, updated_at, deleted_at) VALUES
  (1, 'Product main photo', 1 , true, 'http://localhost/test.jpg', '2016-10-20 11:05:00', '2016-10-20 11:05:00', null),
  (2, 'Category Photo photo', 1, false, 'http://localhost/test1.jpg', '2016-10-20 11:05:00', '2016-10-20 11:05:00', null),
  (3, 'Deleted photo', 2, true, 'http://localhost/test3.jpg', '2016-10-20 11:05:00', '2016-10-20 11:05:00', '2016-10-20 11:05:00'),
  (4, 'Photo', 2, true, 'http://localhost/test4.jpg', '2016-10-20 11:05:00', '2016-10-20 11:05:00', null),
  (5, 'Private photo', 2, false, 'http://localhost/test5.jpg', '2016-10-20 11:05:00', '2016-10-20 11:05:00', null);