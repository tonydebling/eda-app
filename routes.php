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

require INC_ROOT . '/app/routes/admin/example.php';
require INC_ROOT . '/app/routes/admin/uploadfile.php';
require INC_ROOT . '/app/routes/admin/eda-upload.php';

require INC_ROOT . '/app/routes/errors/404.php';

require INC_ROOT . '/app/routes/sandbox/sandbox.php';
