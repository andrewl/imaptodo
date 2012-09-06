<?php
/* include configuration file */
$conf_filename = dirname(__FILE__) . '/conf.inc';

if (!file_exists($conf_filename)) {
  print 'Configuration file ' . $conf_filename . ' does not exist. Cannot run';
  exit(-1);
}

/* default settings which can be overriden in $conf_filename */
$todo = '/usr/local/bin/todo.sh';
$imap_connection = "imap.gmail.com:993/imap/ssl";
$imap_folder = 'starred';
require_once $conf_filename;

/* load all the tasks */
$tasks = array();
exec($todo . ' lsa', $tasks);

/*
select the mailbox to use. If we use 'starred' then get the flagged
messages in the inbox, otherwise just use the unseen messages in the 
specified inbox
*/
if (strtolower($imap_folder) == 'starred') {
  $imap_folder = 'INBOX';
  $search_criteria = 'FLAGGED';
}
else {
  $imap_folder = $mailbox;
  $search_criteria = 'UNSEEN';
}

/* connect to imap */
$hostname = "{" . $imap_connection . "}" . $imap_folder;
if (!$mailbox = imap_open($hostname,$username,$password)) {
  print 'Failed to connect to imap: ' . imap_last_error();
  exit(-2);
}

$total_added = 0;
$total_processed = 0;

if ($emails = imap_search($mailbox,$search_criteria)) {

  foreach($emails as $email_number) {

    /*
    iterate over each message and if we find it in the tasks
    (either in current or archived) then ignore it, otherwise add it
    as a new task
    */
    if ($overview = imap_fetch_overview($mailbox,$email_number,0)) {
      $total_processed++;
      $subject = $overview[0]->subject;
      $from = $overview[0]->from;
      $new_task_text = $subject . ' ' . $from;
      $found = FALSE;

      foreach ($tasks as $task) {
        if (strpos($task, $new_task_text) !== FALSE) {
          $found = TRUE;
        }
      }

      if (!$found) {
        $total_added++;
        $command = $todo . ' add ' . escapeshellarg($new_task_text);
        print 'Adding: ' . escapeshellarg($new_task_text) . "\n";
        exec($command);
      }

    }
    else {
      print 'Failed to retrieve overview for message: ' . imap_last_error() . "\n";
    }

  }

}

/* close the connection */
imap_close($mailbox);

print "Processed {$total_processed} emails, added {$total_added} tasks\n";
