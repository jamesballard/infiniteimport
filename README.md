Infinite Rooms API
==============

api/ping
Status check to confirm availability.

api/ping/authenticated
Status check to confirm a trusted connection.

api/log/last-modified
Return the timestamp of the most recent update known to Infinite Rooms.

api/log/upload
Allow log information to be uploaded using HTTP PUT. The uploaded data should only be on or after the timestamp returned by api/log/last-modified

