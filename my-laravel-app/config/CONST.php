<?php
return [
    'LOCAL' => [
        'MAX_CELL_ROW_NUM'      => 3,
        'MAX_CELL_COL_NUM'      => 3,
        'MAX_CELL_NUM'          => 9,
        'INIT_CELL_COLORS'      => implode(',', array_fill(0, 9, '#dcdcdc')),
        'THUMBNAIL_HEIGHT'      => 80,
        'THUMBNAIL_WIDTH'       => 80,
        'DETAILS_HEIGHT'        => 400,
        'DETAILS_WIDTH'         => 400,
    ],
    'SAVE_FOLDER_PATH'      => 'app/public/',
    'DELETE_FOLDER_PATH'    => 'public/',
    'DOCKER_DEV_IMAGE_NAME' => 'dockerworkspace_dev',
];
