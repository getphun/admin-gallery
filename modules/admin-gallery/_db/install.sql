CREATE TABLE IF NOT EXISTS `gallery` (
    `id` INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `user` INTEGER NOT NULL,
    `name` VARCHAR(250),
    `images` TEXT,
    `updated` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT IGNORE INTO `user_perms` ( `name`, `group`, `role`, `about` ) VALUES
    ( 'create_gallery',  'Gallery', 'Reporter', 'Allow user to create new gallery' ),
    ( 'read_gallery',    'Gallery', 'Reporter', 'Allow user to view all galleries' ),
    ( 'remove_gallery',  'Gallery', 'Reporter', 'Allow user to remove exists gallery' ),
    ( 'update_gallery',  'Gallery', 'Reporter', 'Allow user to update exists gallery' );