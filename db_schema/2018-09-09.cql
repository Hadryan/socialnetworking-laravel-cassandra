CREATE KEYSPACE IF NOT EXISTS friendship WITH REPLICATION = { 'class' : 'NetworkTopologyStrategy', 'datacenter1' : 3 };
USE friendship;

CREATE TABLE IF NOT EXISTS users (
    id UUID PRIMARY KEY,
    email text,
    created_ts timestamp
);

CREATE INDEX IF NOT EXISTS ON friendship.users (email);

CREATE TABLE IF NOT EXISTS user_relations (
    user_id_1 UUID,
    user_id_2 UUID,
    friend_status tinyint,
    subscribe_status tinyint,
    created_ts timestamp,
    updated_ts timestamp,
    PRIMARY KEY ((user_id_1), user_id_2)
);

CREATE INDEX IF NOT EXISTS ON friendship.user_relations (user_id_2);

CREATE TABLE IF NOT EXISTS feeds (
    id UUID PRIMARY KEY,
    sender_id UUID,
    message text,
    mentioned_ids list<uuid>,
    created_ts timestamp,
    updated_ts timestamp
);


CREATE INDEX IF NOT EXISTS ON friendship.feeds (sender_id);