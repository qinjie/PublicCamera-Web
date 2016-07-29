<?php

Yii::setAlias('@siteroot', realpath(dirname(__FILE__).'/../'));

return [
    'adminEmail' => 'zqi2@np.edu.sg',
    'supportEmail' => 'zqi2@np.edu.sg',
    'user.passwordResetTokenExpire' => 3600,
    'folder.upload.files' => '/upload/files/',
    'folder.upload.reference' => '/upload/reference/',
    'folder.upload' => '/upload/',
    'file.python.crowd_index' => '/python/IAmodule.py',
    'floor.crowdindex' => 'crowdindex',
    'application' => '/web'
];
