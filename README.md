internet-forum
==============

Created an internet message board with PHP and MySQL

You must add your own database credentials to connect.php

The code assumes that an administrator account will have a level > 3. If you want the administrator cutoff to be higher than 4, you must edit some properties in the following files:
* add-board.php
* delete-board.php
* edit-board.php
* edit-users.php
* header.php
* index.php

Likewise, the code assumes that a moderator account will have a level > 2. If you want the moderator cutoff to be higher than 3, you must edit some properties in the following files:
* delete-msg.php
* delete-topic.php
* posts.php

The code uses a headers and footers. If you change header.php, the header will change on all pages except for the following 4:
* confirm-login.php
* confirm-registration.php
* login.php
* register.php
To change the header of these pages, change header2.php.
The reason for this is that header.php contains a navbar which is not relevant to these four pages.
