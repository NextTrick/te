-- fcb_service_project
INSERT INTO fcb_service_project (projectId, name) VALUES (1, 'TrackingEngine');

-- fcb_account_profile_type
INSERT INTO fcb_account_profile_type (profileTypeId, name) VALUES (1, 'Web');
INSERT INTO fcb_account_profile_type (profileTypeId, name) VALUES (2, 'Developer');

-- fcb_account_account

INSERT INTO fcb_account_account (accountId, email, `password`, number, creationDate) VALUES (1, 'angel.jara@bongous.com', 'sfsafsafsaf', '234242432', now());

-- fcb_account_profile

INSERT INTO fcb_account_profile (profileId, accountId, profileTypeId, creationDate) VALUES (1, 1, 1, now());

-- fcb_service_service
INSERT INTO fcb_service_service (serviceId, endpoint, creationDate, status, projectId) VALUES (1, 'tracking', now(), 1, 1);
INSERT INTO fcb_service_service (serviceId, endpoint, creationDate, status, projectId) VALUES (2, 'multitracking', now(), 1, 1);
INSERT INTO fcb_service_service (serviceId, endpoint, creationDate, status, projectId) VALUES (3, 'unifed_tracking', now(), 1, 1);

-- fcb_apikey_environment

INSERT INTO fcb_apikey_environment (environmentId, name) VALUES (1, 'Development');
INSERT INTO fcb_apikey_environment (environmentId, name) VALUES (2, 'Production');

--  Test fcb_apikey_apikey

INSERT INTO fcb_apikey_apikey (apikeyId, `key`, creationDate, profileId, environmentId) VALUES (1, '2342FF2343223FFFSS', now(), 1, 1);

-- fcb_statistic_service_apikey
INSERT INTO fcb_statistic_service_apikey (serviceApikeyId, apikeyId, serviceId, counter, creationDate) VALUES (1, 1, 1, 0, now());


