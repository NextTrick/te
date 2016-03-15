-- fcb_carrier_carrier 
INSERT INTO fcb_carrier_carrier (carrierId, name, status, creationDate, alias) VALUES (1, 'Fedex', 1, now(), 'Fedex');
INSERT INTO fcb_carrier_carrier (carrierId, name, status, creationDate, alias) VALUES (2, 'DHL', 1, now(), 'Dhl');
INSERT INTO fcb_carrier_carrier (carrierId, name, status, creationDate, alias) VALUES (3, 'Ups', 1, now(), 'Ups');
INSERT INTO fcb_carrier_carrier (carrierId, name, status, creationDate, alias) VALUES (4, 'CanadaPost', 1, now(), 'CanadaPost');
INSERT INTO fcb_carrier_carrier (carrierId, name, status, creationDate, alias) VALUES (5, 'Usps', 1, now(), 'Usps');
INSERT INTO fcb_carrier_carrier (carrierId, name, status, creationDate, alias) VALUES (6, 'FedexCrossBorder', 1, now(), 'FedexCrossBorder');

-- fcb_service_serviced
INSERT INTO fcb_service_service (serviceId, endpoint, creationDate, status) VALUES (1, 'tracking', now(), 1);
INSERT INTO fcb_service_service (serviceId, endpoint, creationDate, status) VALUES (2, 'multitracking', now(), 1);
INSERT INTO fcb_service_service (serviceId, endpoint, creationDate, status) VALUES (3, 'unifed_tracking', now(), 1);

--  Test fcb_apikey_apikey
INSERT INTO fcb_apikey_apikey (apikeyId, `key`, creationDate, profileId) VALUES (null, '2342FF2343223FFFSS', now(), 1);
