-- fcb_carrier_carrier 
INSERT INTO fcb_carrier_carrier (carrierId, name, status, creationDate, alias) VALUES (1, 'Fedex', 1, now(), 'Fedex');
INSERT INTO fcb_carrier_carrier (carrierId, name, status, creationDate, alias) VALUES (2, 'DHL', 1, now(), 'Dhl');

-- fcb_service_serviced
INSERT INTO fcb_service_service (serviceId, endpoint, creationDate, status) VALUES (1, 'tracking', now(), 1);
INSERT INTO fcb_service_service (serviceId, endpoint, creationDate, status) VALUES (2, 'multitracking', now(), 1);
INSERT INTO fcb_service_service (serviceId, endpoint, creationDate, status) VALUES (3, 'unifed_tracking', now(), 1);

--  Test fcb_apikey_apikey
INSERT INTO fcb_apikey_apikey (apikeyId, `key`, creationDate) VALUES (null, '2342FF2343223FFFSS', now());
