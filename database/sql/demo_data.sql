INSERT INTO `shippings` (`id`, `name`, `processor`, `account_number`, `api_key`, `api_secret`, `zone_field`) VALUES
(1, 'DHL', 'dhl','310937821', 'apM6yT8sU1jN3b', 'A!6yS#0eM^7rQ!1s','cargo_dhl_id'),
(2, 'UPS Express Saver Export', 'ups', NULL, NULL, NULL,'cargo_ups_id'),
(3, 'UPS Express Export', 'ups', NULL, NULL, NULL,'cargo_ups_id'),
(4, 'UPS Expedited Expor', 'ups', NULL, NULL, NULL,'cargo_ups_id');

INSERT INTO `entegration_data` (`id`, `entegration_id`, `user_id`, `user`, `pass`, `last_date`, `days`, `statuses`, `max`, `created_at`, `updated_at`) VALUES
(2, 2, 140000, 'serdar.gulum@exporgin.com', 'z640hl5u106YCYh6K2HN3J9p95ys90KX', '2022-08-08 11:10:01', 10, 'P', 100, '2022-04-26 09:48:27', '2022-05-17 05:16:41');

INSERT INTO `payments` (`id`, `processor`, `active`, `name`, `user`, `key`, `pass`, `success_url`, `fail_url`, `callback_url`) VALUES
    (1, NULL, 0, 'Paratika', '257242', 'kTM1b41M5MYNSjJk', 'pi3iH6L5QgmNENkp', 'payment/1/success', 'payment/1/fail', 'payment/1/callback');

INSERT INTO `payments` (`id`, `processor`, `active`, `name`, `user`, `key`, `pass`, `success_url`, `fail_url`, `callback_url`) VALUES
    (2, 'paytr', 1, 'Paytr', '257242', 'kTM1b41M5MYNSjJk', 'pi3iH6L5QgmNENkp', 'payment/1/success', 'payment/1/fail', 'payment/1/callback');
