<?php

require INC_ROOT . '/app/routes/home.php';
require INC_ROOT . '/app/routes/auth/register.php';
require INC_ROOT . '/app/routes/auth/login.php';
require INC_ROOT . '/app/routes/auth/stulogin.php';
require INC_ROOT . '/app/routes/auth/activate.php';
require INC_ROOT . '/app/routes/auth/logout.php';
require INC_ROOT . '/app/routes/auth/changepassword.php';
require INC_ROOT . '/app/routes/auth/recoverpassword.php';
require INC_ROOT . '/app/routes/auth/resetpassword.php';

require INC_ROOT . '/app/routes/user/profile.php';
require INC_ROOT . '/app/routes/user/home.php';
require INC_ROOT . '/app/routes/user/all.php';

require INC_ROOT . '/app/routes/database/template.php';
require INC_ROOT . '/app/routes/database/testpoint.php';
require INC_ROOT . '/app/routes/database/testresult.php';
require INC_ROOT . '/app/routes/database/plc.php';
require INC_ROOT . '/app/routes/database/hotlists.php';
require INC_ROOT . '/app/routes/database/gethotlist.php';
require INC_ROOT . '/app/routes/database/plctable.php';

require INC_ROOT . '/app/routes/database/updateplcrecord.php';
require INC_ROOT . '/app/routes/database/getdata.php';
require INC_ROOT . '/app/routes/database/browse.php';
require INC_ROOT . '/app/routes/database/uploadresources.php';

require INC_ROOT . '/app/routes/admin/example.php';
require INC_ROOT . '/app/routes/admin/uploadfile.php';
require INC_ROOT . '/app/routes/admin/eda-upload.php';

require INC_ROOT . '/app/routes/errors/404.php';

