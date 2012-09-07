imaptodo
========

imaptodo is a simple php script which polls a mailbox on an imap server and adds the subject and sender of each message to your todotxt file.

Why?
----
So, you're using todotxt (http://todotxt.com/) to manage your todo list from the commandline and your iOS/Android device. But there's something missing. For most of us many tasks that naturally live on our todo list are sent to us via email - wouldn't it be great if we were able to automatically push tasks that arrive via email to our todotxt file? Step forward imaptodo!


How?
----
Install imaptodo in a location of your choice. You'll need to be able to run php with the php_imap from the command line. Rename the conf.inc.EXAMPLE file to conf.inc and edit the username and password settings. Edit other settings as you like - the default behaviour is to add 'Starred' items from your gmail account into your todotxt file. Take special care to ensure that the path to todo.sh is set (the $todo variable).


Bonus Points?
-------------
Add imaptodo to your crontab and run it periodically. Store your todotxt files on Dropbox and link it to your iOS/Android device. 'Star' an item in your Inbox and watch it appear on your todo list!


Todo
----
- use an API rather than command line todo.sh to add items to the todotxt file
- add options for default priority and/or context
