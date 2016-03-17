Hello!

How-to install
$ git clone https://github.com/EvgeniyaPronina/json-api.git

Make database, here is a tables and fields:
Table 'Users': id, user_email;
Table 'Session': id, Name, TimeOfEvent, Description, SpeakerIDs, maxPlaces;
Table 'Ses_Users': id, user_email, ses_id;
Table 'News': id, ParticipantId, newsTitle, newsMessage, LikesCounter;
