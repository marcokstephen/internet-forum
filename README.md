internet-forum
==============

Created an internet message board with PHP and MySQL <br />
<a href="http://www.stephenmarcok.com/boards">You can view it in action here</a> <br />
And you can <a href="http://www.stephenmarcok.com/blog/development/creating-your-own-forum-with-php-and-mysql/">view my tutorial here</a>

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

The code uses headers and footers. If you change header.php, the header will change on all pages except for the following 4:
* confirm-login.php
* confirm-registration.php
* login.php
* register.php

To change the header of these pages, change header2.php. The reason for this is that header.php contains a navbar which is not relevant to these four pages. All pages can use the same footer.php

GENERAL FEATURES:
* User level system 
* Major exploits have been patched (regarding HTTP post/get manipulation)
* HTML-within-posts has been removed to prevent exploits, excluding two tags which you can find in the help menu
* User list has been added to the help page, updates automatically
* Topics are listed by most-recently-active order
* Users have unique pages showing their board account information

ADMINISTRATIVE FEATURES:
* Moderation system implemented to delete users' posts and topics
* Boards can be created/modified/deleted without needing to access the database
* Users can be modified without needing to access the database
* Users that are banned will be banned immediately, they will not need to log out to be banned
