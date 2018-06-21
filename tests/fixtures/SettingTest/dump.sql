INSERT INTO roles(id, name, created_at, updated_at) VALUES
  (1, 'administrator', '2016-10-20 11:05:00', '2016-10-20 11:05:00'),
  (2, 'user', '2016-10-20 11:05:00', '2016-10-20 11:05:00');

INSERT INTO users(id, name, email, password, role_id, created_at, updated_at) VALUES
  (1, 'Mr Admin', 'admin@example.com', '$2y$10$X4receiTrF24bXrEbAiChOZ8TMNPqoXuhuThgynvBdWIHZeu5HzsS', 1, '2016-10-20 11:05:00', '2016-10-20 11:05:00'),
  (2, 'Another User', 'user@example.com', '$2y$10$X4receiTrF24bXrEbAiChOZ8TMNPqoXuhuThgynvBdWIHZeu5HzsS', 2, '2016-10-20 11:05:00', '2016-10-20 11:05:00');

INSERT INTO settings(key, is_public, value, created_at, updated_at) VALUES
  ('attribute', true, '{"name": "color", "value":"#000"}'::json, '2016-10-20 11:05:00', '2016-10-20 11:06:00'),
  ('settings', true, '{"timezone": "australia"}'::json, '2016-10-20 11:06:00', '2016-10-20 11:07:00'),
  ('mailgun', false, '{"api_key": "superKey", "account_id":"3495"}'::json, '2016-10-20 11:07:00', '2016-10-20 11:08:00'),
  ('states', true, '["NSW", "ACT", "NT", "QLD", "SA", "TAS", "VIC", "WA"]'::json, '2016-10-20 11:08:00', '2016-10-20 11:09:00');