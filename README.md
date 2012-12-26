Infinite Rooms API
==================

api/ping
Status check to confirm availability.

api/ping/authenticated
Status check to confirm a trusted connection.

api/log/last-modified
Return the timestamp of the most recent update known to Infinite Rooms.

Moodle Import API
-----------------
api/moodle/course
api/moodle/module
api/moodle/user
api/moodle/log
Allow log information to be uploaded using HTTP PUT. The uploaded data should only be on or after the timestamp returned by api/moodle/last-modified

api/moodle/last-modified
Return the timestamp of the most recent update known to Infinite Rooms.

